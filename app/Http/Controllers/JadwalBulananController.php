<?php

namespace App\Http\Controllers;

use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use App\Models\Posyandu;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalBulananController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

public function index(Request $request)
{
    $query = JadwalBulanan::query()
        ->with([
            'pembuat',
            'approver',
            'details.posyandu.wilayah',
            'details.kegiatan',
        ])
        ->orderByDesc('tahun')
        ->orderByDesc('bulan');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('tahun')) {
        $query->where('tahun', $request->tahun);
    }

    $jadwals = $query
        ->paginate(10)
        ->withQueryString();

    $tahunList = JadwalBulanan::query()
        ->select('tahun')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

    return view(
        'pages.jadwal.index',
        compact(
            'jadwals',
            'tahunList'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $posyandus = Posyandu::query()
            ->where('aktif', true)
            ->with([
                'wilayah',
                'hariOperasionals' => function ($query) {
                    $query
                        ->where('aktif', true)
                        ->orderBy('hari')
                        ->orderBy('jam_mulai');
                },
            ])
            ->orderBy('nama_posyandu')
            ->get();

        $kegiatans = Kegiatan::query()
            ->where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        return view(
            'pages.jadwal.create',
            compact(
                'posyandus',
                'kegiatans'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode' => [
                'required',
                'date_format:Y-m',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
            ],

            'details.*.posyandu_id' => [
                'required',
                'integer',
                'exists:posyandus,id',
            ],

            'details.*.kegiatan_id' => [
                'required',
                'integer',
                'exists:kegiatans,id',
            ],

            'details.*.tgl_mulai' => [
                'required',
                'date',
            ],

            'details.*.tgl_selesai' => [
                'required',
                'date',
            ],

            'details.*.jam_mulai' => [
                'required',
                'date_format:H:i',
            ],

            'details.*.jam_selesai' => [
                'required',
                'date_format:H:i',
            ],

            'details.*.keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $periode = Carbon::createFromFormat(
            'Y-m',
            $validated['periode']
        );

        $bulan = $periode->month;
        $tahun = $periode->year;

        $this->validateDetailDates(
            $validated['details'],
            $bulan,
            $tahun
        );

        $this->validateDetailTimes(
            $validated['details']
        );

        $this->validateScheduleConflicts(
            $validated['details']
        );

        $jadwal = DB::transaction(function () use (
            $validated,
            $bulan,
            $tahun
        ) {
            $alreadyExists = JadwalBulanan::query()
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->exists();

            if ($alreadyExists) {
                throw ValidationException::withMessages([
                    'periode' => 'Jadwal untuk bulan dan tahun tersebut sudah tersedia.',
                ]);
            }

            /*
             * Status ini milik tabel jadwal_bulanans.
             * BUKAN milik tabel jadwal_details.
             */
            $jadwal = JadwalBulanan::create([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'draft',
                'catatan' => $validated['catatan'] ?? null,
                'dibuat_oleh' => auth()->id(),
            ]);

            foreach ($validated['details'] as $detail) {
                $jadwal->details()->create([
                    'posyandu_id' => $detail['posyandu_id'],
                    'kegiatan_id' => $detail['kegiatan_id'],
                    'tgl_mulai' => $detail['tgl_mulai'],
                    'tgl_selesai' => $detail['tgl_selesai'],
                    'jam_mulai' => $detail['jam_mulai'],
                    'jam_selesai' => $detail['jam_selesai'],
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            return $jadwal;
        });

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal bulanan berhasil dibuat.'
            );
    }

 /*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

public function edit(JadwalBulanan $jadwal)
{
    /*
    |--------------------------------------------------------------------------
    | Jadwal disetujui tidak boleh diedit
    |--------------------------------------------------------------------------
    */

    if ($jadwal->status === 'disetujui') {
        return redirect()
            ->route('jadwal.show', $jadwal)
            ->with(
                'error',
                'Jadwal yang sudah disetujui tidak dapat diubah.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Load seluruh relasi yang dibutuhkan halaman edit
    |--------------------------------------------------------------------------
    */

    $jadwal->load([
        'details' => function ($query) {
            $query
                ->orderBy('tgl_mulai')
                ->orderBy('jam_mulai');
        },

        'details.posyandu',

        'details.kegiatan',

        'approval',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Ambil master Posyandu aktif
    |--------------------------------------------------------------------------
    */

    $posyandus = Posyandu::query()
        ->where('aktif', true)
        ->with([
            'wilayah',

            'hariOperasionals' => function ($query) {
                $query
                    ->where('aktif', true)
                    ->orderBy('hari')
                    ->orderBy('jam_mulai');
            },
        ])
        ->orderBy('nama_posyandu')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Ambil master kegiatan aktif
    |--------------------------------------------------------------------------
    */

    $kegiatans = Kegiatan::query()
        ->where('aktif', true)
        ->orderBy('nama_kegiatan')
        ->get();

    return view(
        'pages.jadwal.edit',
        compact(
            'jadwal',
            'posyandus',
            'kegiatans'
        )
    );
}

   /*
|--------------------------------------------------------------------------
| UPDATE JADWAL
|--------------------------------------------------------------------------
*/

public function update(
    Request $request,
    JadwalBulanan $jadwal
) {
    /*
    |--------------------------------------------------------------------------
    | Jadwal yang sudah disetujui tidak dapat diubah
    |--------------------------------------------------------------------------
    */

    if ($jadwal->status === 'disetujui') {
        return redirect()
            ->route('jadwal.show', $jadwal)
            ->with(
                'error',
                'Jadwal yang sudah disetujui tidak dapat diubah.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Tentukan aksi form
    |--------------------------------------------------------------------------
    |
    | save   = hanya menyimpan perubahan
    | submit = menyimpan perubahan dan mengajukan ke koordinator
    |
    */

    $action = $request->input('action', 'save');

    if (!in_array($action, ['save', 'submit'], true)) {
        $action = 'save';
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi input
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'catatan' => [
            'nullable',
            'string',
            'max:5000',
        ],

        'details' => [
            'required',
            'array',
            'min:1',
        ],

        'details.*.id' => [
            'nullable',
            'integer',
        ],

        'details.*.posyandu_id' => [
            'required',
            'integer',
            'exists:posyandus,id',
        ],

        'details.*.kegiatan_id' => [
            'required',
            'integer',
            'exists:kegiatans,id',
        ],

        'details.*.tgl_mulai' => [
            'required',
            'date',
        ],

        'details.*.tgl_selesai' => [
            'required',
            'date',
            'after_or_equal:details.*.tgl_mulai',
        ],

        'details.*.jam_mulai' => [
            'required',
            'date_format:H:i',
        ],

        'details.*.jam_selesai' => [
            'required',
            'date_format:H:i',
        ],

        'details.*.keterangan' => [
            'nullable',
            'string',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Validasi tanggal
    |--------------------------------------------------------------------------
    */

    $this->validateDetailDates(
        $validated['details'],
        (int) $jadwal->bulan,
        (int) $jadwal->tahun
    );

    /*
    |--------------------------------------------------------------------------
    | Validasi jam
    |--------------------------------------------------------------------------
    */

    $this->validateDetailTimes(
        $validated['details']
    );

    /*
    |--------------------------------------------------------------------------
    | Validasi bentrok jadwal
    |--------------------------------------------------------------------------
    */

    $this->validateScheduleConflicts(
        $validated['details']
    );

    /*
    |--------------------------------------------------------------------------
    | Simpan seluruh perubahan dalam transaction
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $validated,
        $jadwal,
        $action
    ) {
        $statusLama = $jadwal->status;

        /*
        |--------------------------------------------------------------------------
        | Tentukan status baru
        |--------------------------------------------------------------------------
        */

        $statusBaru = $statusLama;

        if (
            $action === 'submit' &&
            in_array($statusLama, ['draft', 'ditolak'], true)
        ) {
            $statusBaru = 'diajukan';
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan catatan dan status jadwal utama
        |--------------------------------------------------------------------------
        */

        $jadwal->catatan = $validated['catatan'] ?? null;
        $jadwal->status = $statusBaru;

        /*
        |--------------------------------------------------------------------------
        | Jika diajukan kembali, reset data persetujuan sebelumnya
        |--------------------------------------------------------------------------
        */

        if (
            $statusBaru === 'diajukan' &&
            $statusLama === 'ditolak'
        ) {
            $jadwal->approved_by = null;
            $jadwal->approved_at = null;
        }

        $jadwal->save();

        /*
        |--------------------------------------------------------------------------
        | Simpan detail kegiatan
        |--------------------------------------------------------------------------
        */

        $detailIds = [];

        foreach ($validated['details'] as $detail) {
            /*
            |--------------------------------------------------------------------------
            | Update detail lama
            |--------------------------------------------------------------------------
            */

            if (!empty($detail['id'])) {
                $jadwalDetail = $jadwal->details()
                    ->where('id', $detail['id'])
                    ->first();

                if ($jadwalDetail) {
                    $jadwalDetail->update([
                        'posyandu_id' => $detail['posyandu_id'],
                        'kegiatan_id' => $detail['kegiatan_id'],
                        'tgl_mulai' => $detail['tgl_mulai'],
                        'tgl_selesai' => $detail['tgl_selesai'],
                        'jam_mulai' => $detail['jam_mulai'],
                        'jam_selesai' => $detail['jam_selesai'],
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]);

                    $detailIds[] = $jadwalDetail->id;
                }
            } else {
                /*
                |--------------------------------------------------------------------------
                | Tambah detail baru
                |--------------------------------------------------------------------------
                */

                $jadwalDetail = $jadwal->details()->create([
                    'posyandu_id' => $detail['posyandu_id'],
                    'kegiatan_id' => $detail['kegiatan_id'],
                    'tgl_mulai' => $detail['tgl_mulai'],
                    'tgl_selesai' => $detail['tgl_selesai'],
                    'jam_mulai' => $detail['jam_mulai'],
                    'jam_selesai' => $detail['jam_selesai'],
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);

                $detailIds[] = $jadwalDetail->id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus detail yang dihapus dari halaman edit
        |--------------------------------------------------------------------------
        */

        $queryHapus = $jadwal->details();

        if (!empty($detailIds)) {
            $queryHapus
                ->whereNotIn('id', $detailIds)
                ->delete();
        } else {
            $queryHapus->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan log hanya jika status berubah
        |--------------------------------------------------------------------------
        */

        if ($statusLama !== $statusBaru) {
            $keteranganLog = match ($statusBaru) {
                'diajukan' => $statusLama === 'ditolak'
                    ? 'Jadwal diperbaiki dan diajukan kembali kepada koordinator.'
                    : 'Jadwal diajukan kepada koordinator.',

                default => 'Status jadwal diperbarui.',
            };

            $jadwal->statusLogs()->create([
                'user_id' => auth()->id(),
                'status_lama' => $statusLama,
                'status_baru' => $statusBaru,
                'keterangan' => $keteranganLog,
            ]);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Pesan berdasarkan aksi
    |--------------------------------------------------------------------------
    */

    if ($action === 'submit') {
        return redirect()
            ->route('jadwal.index')
            ->with(
                'success',
                'Perubahan jadwal berhasil disimpan dan diajukan kepada koordinator.'
            );
    }

    return redirect()
        ->route('jadwal.edit', $jadwal)
        ->with(
            'success',
            'Perubahan jadwal berhasil disimpan.'
        );
}

    /*
    |--------------------------------------------------------------------------
    | GENERATE SEMUA POSYANDU
    |--------------------------------------------------------------------------
    */

    public function generate(JadwalBulanan $jadwal)
    {
        if ($jadwal->status === 'disetujui') {
            return back()->with(
                'error',
                'Jadwal yang sudah disetujui tidak dapat digenerate ulang.'
            );
        }

        $posyandus = Posyandu::query()
            ->where('aktif', true)
            ->with([
                'hariOperasionals' => function ($query) {
                    $query
                        ->where('aktif', true)
                        ->orderBy('hari')
                        ->orderBy('jam_mulai');
                },
            ])
            ->get();

        $kegiatanDefault = Kegiatan::query()
            ->where('aktif', true)
            ->orderBy('id')
            ->first();

        if (!$kegiatanDefault) {
            return back()->with(
                'error',
                'Belum ada master kegiatan aktif.'
            );
        }

        DB::transaction(function () use (
            $jadwal,
            $posyandus,
            $kegiatanDefault
        ) {
            foreach ($posyandus as $posyandu) {
                $hariOperasional = $posyandu
                    ->hariOperasionals
                    ->first();

                if (!$hariOperasional) {
                    continue;
                }

                $tanggal = $this->tanggalPertamaSesuaiHari(
                    $jadwal->tahun,
                    $jadwal->bulan,
                    $hariOperasional->hari
                );

                if (!$tanggal) {
                    continue;
                }

                $alreadyExists = $jadwal->details()
                    ->where('posyandu_id', $posyandu->id)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                $jadwal->details()->create([
                    'posyandu_id' => $posyandu->id,
                    'kegiatan_id' => $kegiatanDefault->id,
                    'tgl_mulai' => $tanggal,
                    'tgl_selesai' => $tanggal,
                    'jam_mulai' => $hariOperasional->jam_mulai,
                    'jam_selesai' => $hariOperasional->jam_selesai,
                    'keterangan' => null,
                ]);
            }
        });

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal semua Posyandu berhasil digenerate.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE SATU POSYANDU
    |--------------------------------------------------------------------------
    */

    public function generateOne(
        Request $request,
        JadwalBulanan $jadwal
    ) {
        if ($jadwal->status === 'disetujui') {
            return back()->with(
                'error',
                'Jadwal yang sudah disetujui tidak dapat digenerate.'
            );
        }

        $validated = $request->validate([
            'posyandu_id' => [
                'required',
                'integer',
                'exists:posyandus,id',
            ],
        ]);

        $posyandu = Posyandu::query()
            ->where('aktif', true)
            ->with([
                'hariOperasionals' => function ($query) {
                    $query
                        ->where('aktif', true)
                        ->orderBy('hari')
                        ->orderBy('jam_mulai');
                },
            ])
            ->findOrFail(
                $validated['posyandu_id']
            );

        $kegiatanDefault = Kegiatan::query()
            ->where('aktif', true)
            ->orderBy('id')
            ->first();

        if (!$kegiatanDefault) {
            return back()->with(
                'error',
                'Belum ada master kegiatan aktif.'
            );
        }

        $hariOperasional = $posyandu
            ->hariOperasionals
            ->first();

        if (!$hariOperasional) {
            return back()->with(
                'error',
                'Posyandu tersebut belum memiliki hari operasional.'
            );
        }

        $tanggal = $this->tanggalPertamaSesuaiHari(
            $jadwal->tahun,
            $jadwal->bulan,
            $hariOperasional->hari
        );

        if (!$tanggal) {
            return back()->with(
                'error',
                'Tanggal operasional tidak ditemukan.'
            );
        }

        $jadwal->details()->updateOrCreate(
            [
                'posyandu_id' => $posyandu->id,
            ],
            [
                'kegiatan_id' => $kegiatanDefault->id,
                'tgl_mulai' => $tanggal,
                'tgl_selesai' => $tanggal,
                'jam_mulai' => $hariOperasional->jam_mulai,
                'jam_selesai' => $hariOperasional->jam_selesai,
                'keterangan' => null,
            ]
        );

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal satu Posyandu berhasil digenerate.'
            );
    }

    /*
|--------------------------------------------------------------------------
| DESTROY / HAPUS JADWAL
|--------------------------------------------------------------------------
*/

public function destroy(JadwalBulanan $jadwal)
{
    /*
     * Jadwal yang sudah disetujui tidak boleh dihapus.
     */
    if ($jadwal->status === 'disetujui') {
        return redirect()
            ->route('jadwal.index')
            ->with(
                'error',
                'Jadwal yang sudah disetujui tidak dapat dihapus.'
            );
    }

    DB::transaction(function () use ($jadwal) {
        /*
         * Hapus seluruh detail jadwal terlebih dahulu.
         */
        $jadwal->details()->delete();

        /*
         * Setelah detail terhapus, hapus jadwal induknya.
         */
        $jadwal->delete();
    });

    return redirect()
        ->route('jadwal.index')
        ->with(
            'success',
            'Jadwal bulanan berhasil dihapus.'
        );
}
    /*
    |--------------------------------------------------------------------------
    | PDF SEMUA JADWAL
    |--------------------------------------------------------------------------
    */

    public function pdf(JadwalBulanan $jadwal)
    {
        $jadwal->load([
            'pembuat',
            'approver',
            'details' => function ($query) {
                $query
                    ->orderBy('tgl_mulai')
                    ->orderBy('jam_mulai');
            },
            'details.posyandu.wilayah',
            'details.kegiatan',
        ]);

        $pdf = Pdf::loadView(
            'pages.jadwal.pdf',
            compact('jadwal')
        );

        $pdf->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(
            'jadwal-posyandu-' .
            $jadwal->tahun .
            '-' .
            str_pad(
                $jadwal->bulan,
                2,
                '0',
                STR_PAD_LEFT
            ) .
            '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PDF SATU POSYANDU
    |--------------------------------------------------------------------------
    */

    public function pdfOne(
        JadwalBulanan $jadwal,
        Posyandu $posyandu
    ) {
        $jadwal->load([
            'pembuat',
            'details' => function ($query) use ($posyandu) {
                $query
                    ->where('posyandu_id', $posyandu->id)
                    ->orderBy('tgl_mulai')
                    ->orderBy('jam_mulai');
            },
            'details.posyandu.wilayah',
            'details.kegiatan',
        ]);

        $pdf = Pdf::loadView(
            'pages.jadwal.pdf',
            compact(
                'jadwal',
                'posyandu'
            )
        );

        $pdf->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(
            'jadwal-' .
            \Str::slug($posyandu->nama_posyandu) .
            '-' .
            $jadwal->tahun .
            '-' .
            str_pad(
                $jadwal->bulan,
                2,
                '0',
                STR_PAD_LEFT
            ) .
            '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER TANGGAL
    |--------------------------------------------------------------------------
    */

    private function tanggalPertamaSesuaiHari(
        int $tahun,
        int $bulan,
        $hari
    ) {
        $tanggal = Carbon::create(
            $tahun,
            $bulan,
            1
        );

        /*
         * Nilai hari:
         * 1 = Senin
         * 2 = Selasa
         * 3 = Rabu
         * 4 = Kamis
         * 5 = Jumat
         * 6 = Sabtu
         * 7 = Minggu
         */

        $hariTarget = (int) $hari;

        if (
            $hariTarget < 1 ||
            $hariTarget > 7
        ) {
            return null;
        }

        while (
            (int) $tanggal->dayOfWeekIso !== $hariTarget
        ) {
            $tanggal->addDay();

            if ($tanggal->month !== $bulan) {
                return null;
            }
        }

        return $tanggal->format('Y-m-d');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI TANGGAL DETAIL
    |--------------------------------------------------------------------------
    */

    private function validateDetailDates(
        array $details,
        int $bulan,
        int $tahun
    ) {
        foreach ($details as $detail) {
            $mulai = Carbon::parse(
                $detail['tgl_mulai']
            );

            $selesai = Carbon::parse(
                $detail['tgl_selesai']
            );

            if (
                $mulai->month !== $bulan ||
                $mulai->year !== $tahun
            ) {
                throw ValidationException::withMessages([
                    'details' =>
                        'Tanggal mulai harus berada pada bulan jadwal.',
                ]);
            }

            if (
                $selesai->month !== $bulan ||
                $selesai->year !== $tahun
            ) {
                throw ValidationException::withMessages([
                    'details' =>
                        'Tanggal selesai harus berada pada bulan jadwal.',
                ]);
            }

            if ($selesai->lt($mulai)) {
                throw ValidationException::withMessages([
                    'details' =>
                        'Tanggal selesai tidak boleh sebelum tanggal mulai.',
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI JAM DETAIL
    |--------------------------------------------------------------------------
    */

    private function validateDetailTimes(
        array $details
    ) {
        foreach ($details as $detail) {
            if (
                $detail['jam_selesai'] <=
                $detail['jam_mulai']
            ) {
                throw ValidationException::withMessages([
                    'details' =>
                        'Jam selesai harus lebih besar dari jam mulai.',
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI BENTROK JADWAL
    |--------------------------------------------------------------------------
    */

    private function validateScheduleConflicts(
        array $details
    ) {
        $grouped = collect($details)
            ->groupBy(function ($detail) {
                return $detail['posyandu_id'] .
                    '|' .
                    $detail['tgl_mulai'];
            });

        foreach ($grouped as $items) {
            if ($items->count() <= 1) {
                continue;
            }

            $sorted = $items
                ->sortBy('jam_mulai')
                ->values();

            for (
                $i = 0;
                $i < $sorted->count() - 1;
                $i++
            ) {
                $current = $sorted[$i];
                $next = $sorted[$i + 1];

                if (
                    $next['jam_mulai'] <
                    $current['jam_selesai']
                ) {
                    throw ValidationException::withMessages([
                        'details' =>
                            'Terdapat jadwal bentrok pada Posyandu yang sama.',
                    ]);
                }
            }
        }
    }


    // SHOW
    public function show(JadwalBulanan $jadwal)
{
    $jadwal->load([
        'pembuat',
        'approver',
        'details' => function ($query) {
            $query
                ->orderBy('tgl_mulai')
                ->orderBy('jam_mulai');
        },
        'details.posyandu.wilayah',
        'details.kegiatan',
        'approval',
    ]);

    return view(
        'pages.jadwal.show',
        compact('jadwal')
    );
}

/*
|--------------------------------------------------------------------------
| SUBMIT DRAFT / DITOLAK KE KOORDINATOR
|--------------------------------------------------------------------------
*/

public function submit(JadwalBulanan $jadwal)
{
    /*
    |--------------------------------------------------------------------------
    | Validasi user login
    |--------------------------------------------------------------------------
    */

    if (!auth()->check()) {
        abort(
            403,
            'Anda harus login terlebih dahulu.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi role admin
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    $role = strtolower((string) (
        $user->role
        ?? $user->nama_role
        ?? ''
    ));

    if (!in_array($role, [
        'admin',
        'administrator',
        'superadmin',
    ], true)) {
        abort(
            403,
            'Hanya admin yang dapat mengajukan jadwal.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi status
    |--------------------------------------------------------------------------
    */

    if (!in_array($jadwal->status, [
        'draft',
        'ditolak',
    ], true)) {
        return back()->with(
            'error',
            'Hanya jadwal draft atau ditolak yang dapat diajukan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pastikan memiliki detail
    |--------------------------------------------------------------------------
    */

    if ($jadwal->details()->count() === 0) {
        return back()->with(
            'error',
            'Jadwal belum memiliki detail kegiatan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Simpan pengajuan
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($jadwal) {
        $statusLama = $jadwal->status;

        $jadwal->status = 'diajukan';

        if ($statusLama === 'ditolak') {
            $jadwal->approved_by = null;
            $jadwal->approved_at = null;
        }

        $jadwal->save();

        $jadwal->statusLogs()->create([
            'user_id' => auth()->id(),
            'status_lama' => $statusLama,
            'status_baru' => 'diajukan',
            'keterangan' => $statusLama === 'ditolak'
                ? 'Jadwal diajukan kembali kepada koordinator.'
                : 'Jadwal diajukan kepada koordinator.',
        ]);
    });

    return redirect()
        ->route('jadwal.index')
        ->with(
            'success',
            'Jadwal berhasil diajukan kepada koordinator.'
        );
}
/*
|--------------------------------------------------------------------------
| DIAJUKAN -> DISETUJUI
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DIAJUKAN -> DISETUJUI
|--------------------------------------------------------------------------
*/

public function approve(
    Request $request,
    JadwalBulanan $jadwal
) {
    /*
    |--------------------------------------------------------------------------
    | Validasi login
    |--------------------------------------------------------------------------
    */

    if (!auth()->check()) {
        abort(
            403,
            'Anda harus login terlebih dahulu.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi role koordinator
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    $role = strtolower((string) (
        $user->role
        ?? $user->nama_role
        ?? ''
    ));

    if (!in_array($role, [
        'koordinator',
        'coordinator',
    ], true)) {
        abort(
            403,
            'Anda tidak memiliki hak untuk menyetujui jadwal.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi status
    |--------------------------------------------------------------------------
    */

    if ($jadwal->status !== 'diajukan') {
        return redirect()
            ->route('jadwal.index')
            ->with(
                'error',
                'Hanya jadwal yang diajukan yang dapat disetujui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi catatan koordinator
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'catatan_koordinator' => [
            'nullable',
            'string',
            'max:5000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Simpan persetujuan
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $jadwal,
        $validated
    ) {
        $catatan = $validated['catatan_koordinator'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Update jadwal utama
        |--------------------------------------------------------------------------
        */

        $jadwal->status = 'disetujui';
        $jadwal->approved_by = auth()->id();
        $jadwal->approved_at = now();
        $jadwal->save();

        /*
        |--------------------------------------------------------------------------
        | Simpan data approval
        |--------------------------------------------------------------------------
        */

        $jadwal->approval()->updateOrCreate(
            [
                'jadwal_id' => $jadwal->id,
            ],
            [
                'user_id' => auth()->id(),
                'status' => 'disetujui',
                'catatan' => $catatan,
                'approved_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan log status
        |--------------------------------------------------------------------------
        */

        $jadwal->statusLogs()->create([
            'user_id' => auth()->id(),
            'status_lama' => 'diajukan',
            'status_baru' => 'disetujui',
            'keterangan' => $catatan,
        ]);
    });

    return redirect()
        ->route('jadwal.index')
        ->with(
            'success',
            'Jadwal berhasil disetujui.'
        );
}
/*
|--------------------------------------------------------------------------
| DIAJUKAN -> DITOLAK
|--------------------------------------------------------------------------
*/

public function reject(
    Request $request,
    JadwalBulanan $jadwal
) {
    /*
    |--------------------------------------------------------------------------
    | Pastikan user sudah login
    |--------------------------------------------------------------------------
    */

    if (!auth()->check()) {
        abort(403, 'Anda harus login terlebih dahulu.');
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi role koordinator
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    $role = strtolower((string) (
        $user->role
        ?? $user->nama_role
        ?? ''
    ));

    if (!in_array($role, [
        'koordinator',
        'coordinator',
    ], true)) {
        abort(
            403,
            'Anda tidak memiliki hak untuk menolak jadwal.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi status
    |--------------------------------------------------------------------------
    */

    if ($jadwal->status !== 'diajukan') {
        return redirect()
            ->route('jadwal.index')
            ->with(
                'error',
                'Hanya jadwal yang diajukan yang dapat ditolak.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi catatan penolakan
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'catatan_koordinator' => [
            'required',
            'string',
            'max:5000',
        ],
    ], [
        'catatan_koordinator.required' =>
            'Catatan penolakan wajib diisi.',
    ]);

    $catatanPenolakan = trim(
        $validated['catatan_koordinator']
    );

    /*
    |--------------------------------------------------------------------------
    | Simpan penolakan
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $jadwal,
        $catatanPenolakan
    ) {

        /*
        |--------------------------------------------------------------------------
        | PENTING:
        | Simpan catatan ke tabel jadwal_bulanans
        | agar muncul kembali di halaman edit admin.
        |--------------------------------------------------------------------------
        */

        $jadwal->update([
            'status' => 'ditolak',

            'catatan' => $catatanPenolakan,

            'approved_by' => auth()->id(),

            'approved_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan data approval
        |--------------------------------------------------------------------------
        */

        $jadwal->approval()->updateOrCreate(
            [
                'jadwal_id' => $jadwal->id,
            ],
            [
                'user_id' => auth()->id(),

                'status' => 'ditolak',

                'catatan' => $catatanPenolakan,

                'approved_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan riwayat status
        |--------------------------------------------------------------------------
        */

        $jadwal->statusLogs()->create([
            'user_id' => auth()->id(),

            'status_lama' => 'diajukan',

            'status_baru' => 'ditolak',

            'keterangan' => $catatanPenolakan,
        ]);
    });

    return redirect()
        ->route('jadwal.index')
        ->with(
            'success',
            'Jadwal berhasil ditolak dan catatan penolakan telah disimpan.'
        );
}
}