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
        if ($jadwal->status === 'disetujui') {
            return redirect()
                ->route('jadwal.show', $jadwal)
                ->with(
                    'error',
                    'Jadwal yang sudah disetujui tidak dapat diubah.'
                );
        }

        $jadwal->load([
            'details' => function ($query) {
                $query
                    ->orderBy('tgl_mulai')
                    ->orderBy('jam_mulai');
            },
            'details.posyandu',
            'details.kegiatan',
        ]);

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
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        JadwalBulanan $jadwal
    ) {
        if ($jadwal->status === 'disetujui') {
            return back()->with(
                'error',
                'Jadwal yang sudah disetujui tidak dapat diubah.'
            );
        }

        $validated = $request->validate([
            'catatan' => [
                'nullable',
                'string',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
            ],

            'details.*.id' => [
                'nullable',
                'integer',
                'exists:jadwal_details,id',
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

        $this->validateDetailDates(
            $validated['details'],
            $jadwal->bulan,
            $jadwal->tahun
        );

        $this->validateDetailTimes(
            $validated['details']
        );

        $this->validateScheduleConflicts(
            $validated['details']
        );

        DB::transaction(function () use (
            $jadwal,
            $validated
        ) {
            $jadwal->update([
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $existingIds = [];

            foreach ($validated['details'] as $detail) {
                if (!empty($detail['id'])) {
                    $jadwalDetail = JadwalDetail::query()
                        ->where('jadwal_id', $jadwal->id)
                        ->where('id', $detail['id'])
                        ->firstOrFail();

                    $jadwalDetail->update([
                        'posyandu_id' => $detail['posyandu_id'],
                        'kegiatan_id' => $detail['kegiatan_id'],
                        'tgl_mulai' => $detail['tgl_mulai'],
                        'tgl_selesai' => $detail['tgl_selesai'],
                        'jam_mulai' => $detail['jam_mulai'],
                        'jam_selesai' => $detail['jam_selesai'],
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]);

                    $existingIds[] = $jadwalDetail->id;
                } else {
                    $newDetail = $jadwal->details()->create([
                        'posyandu_id' => $detail['posyandu_id'],
                        'kegiatan_id' => $detail['kegiatan_id'],
                        'tgl_mulai' => $detail['tgl_mulai'],
                        'tgl_selesai' => $detail['tgl_selesai'],
                        'jam_mulai' => $detail['jam_mulai'],
                        'jam_selesai' => $detail['jam_selesai'],
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]);

                    $existingIds[] = $newDetail->id;
                }
            }

            $deleteQuery = $jadwal->details();

            if (count($existingIds) > 0) {
                $deleteQuery->whereNotIn(
                    'id',
                    $existingIds
                );
            }

            $deleteQuery->delete();
        });

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal berhasil diperbarui.'
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
}