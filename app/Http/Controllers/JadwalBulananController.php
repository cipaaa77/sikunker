<?php

namespace App\Http\Controllers;

use App\Exports\JadwalBulananExport;
use App\Models\HariOperasional;
use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use App\Models\Kegiatan;
use App\Models\Posyandu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class JadwalBulananController extends Controller
{
    public function index()
    {
        $jadwals = JadwalBulanan::with('dibuatOleh')
            ->latest('tahun')
            ->latest('bulan')
            ->paginate(10);

        return view('pages.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $posyandus = Posyandu::where('aktif', true)
            ->with('wilayah')
            ->orderBy('nama_posyandu')
            ->get();

        $kegiatans = Kegiatan::where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        return view('pages.jadwal.create', compact(
            'posyandus',
            'kegiatans'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'tahun' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],
            'catatan' => [
                'nullable',
                'string',
            ],
        ], [
            'bulan.required' => 'Bulan wajib dipilih.',
            'bulan.between' => 'Bulan tidak valid.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.min' => 'Tahun tidak valid.',
            'tahun.max' => 'Tahun tidak valid.',
        ]);

        $exists = JadwalBulanan::where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal untuk bulan dan tahun tersebut sudah tersedia.'
                );
        }

        $jadwal = DB::transaction(function () use ($validated) {
            $jadwal = JadwalBulanan::create([
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'status' => 'draft',
                'catatan' => $validated['catatan'] ?? null,
                'dibuat_oleh' => auth()->id(),
            ]);

            $this->createStatusLog(
                $jadwal,
                null,
                'draft',
                'Jadwal bulanan dibuat.'
            );

            return $jadwal;
        });

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal bulanan berhasil dibuat. Silakan susun detail jadwal.'
            );
    }

    public function show(JadwalBulanan $jadwal)
    {
        $jadwal->load([
            'dibuatOleh',
            'approvedBy',
            'details' => function ($query) {
                $query
                    ->orderBy('tgl_mulai')
                    ->orderBy('jam_mulai');
            },
            'details.posyandu.wilayah',
            'details.kegiatan',
            'approvals.user',
            'statusLogs.user',
        ]);

        return view('pages.jadwal.show', compact('jadwal'));
    }

    public function edit(JadwalBulanan $jadwal)
    {
        $jadwal->load([
            'details' => function ($query) {
                $query
                    ->orderBy('tgl_mulai')
                    ->orderBy('jam_mulai');
            },
            'details.posyandu',
            'details.kegiatan',
        ]);

        $posyandus = Posyandu::where('aktif', true)
            ->with('wilayah')
            ->orderBy('nama_posyandu')
            ->get();

        $kegiatans = Kegiatan::where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        $hariOperasionals = HariOperasional::where('aktif', true)
            ->with('posyandu')
            ->orderBy('posyandu_id')
            ->orderBy('hari')
            ->get();

        return view('pages.jadwal.edit', compact(
            'jadwal',
            'posyandus',
            'kegiatans',
            'hariOperasionals'
        ));
    }

    public function update(Request $request, JadwalBulanan $jadwal)
    {
        if ($jadwal->status === 'final') {
            return back()->with(
                'error',
                'Jadwal yang sudah final tidak dapat diubah.'
            );
        }

        $validated = $request->validate([
            'catatan' => [
                'nullable',
                'string',
            ],

            'details' => [
                'nullable',
                'array',
            ],

            'details.*.id' => [
                'nullable',
                'integer',
                'exists:jadwal_details,id',
            ],

            'details.*.posyandu_id' => [
                'required',
                'exists:posyandus,id',
            ],

            'details.*.kegiatan_id' => [
                'required',
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
                'nullable',
                'date_format:H:i',
            ],

            'details.*.jam_selesai' => [
                'nullable',
                'date_format:H:i',
            ],

            'details.*.tipe_kegiatan' => [
                'required',
                'in:DG,LG',
            ],

            'details.*.status' => [
                'nullable',
                'in:terjadwal,selesai,dibatalkan',
            ],

            'details.*.keterangan' => [
                'nullable',
                'string',
            ],
        ], [
            'details.*.posyandu_id.required' =>
                'Posyandu wajib dipilih.',

            'details.*.kegiatan_id.required' =>
                'Kegiatan wajib dipilih.',

            'details.*.tgl_mulai.required' =>
                'Tanggal mulai wajib diisi.',

            'details.*.tgl_selesai.required' =>
                'Tanggal selesai wajib diisi.',

            'details.*.tipe_kegiatan.required' =>
                'Tipe kegiatan wajib dipilih.',
        ]);

        $details = $validated['details'] ?? [];

        $this->validateDetailDates(
            $details,
            $jadwal
        );

        $this->validateDetailTimes(
            $details
        );

        $this->validateScheduleConflicts(
            $details
        );

        DB::transaction(function () use (
            $jadwal,
            $validated,
            $details
        ) {
            $jadwal->update([
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $existingIds = [];

            foreach ($details as $detail) {
                if (!empty($detail['id'])) {
                    $jadwalDetail = JadwalDetail::where('jadwal_id', $jadwal->id)
                        ->where('id', $detail['id'])
                        ->firstOrFail();

                    $jadwalDetail->update([
                        'posyandu_id' => $detail['posyandu_id'],
                        'kegiatan_id' => $detail['kegiatan_id'],
                        'tgl_mulai' => $detail['tgl_mulai'],
                        'tgl_selesai' => $detail['tgl_selesai'],
                        'jam_mulai' => $detail['jam_mulai'] ?? null,
                        'jam_selesai' => $detail['jam_selesai'] ?? null,
                        'tipe_kegiatan' => $detail['tipe_kegiatan'],
                        'status' => $detail['status'] ?? 'terjadwal',
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]);

                    $existingIds[] = $jadwalDetail->id;
                } else {
                    $newDetail = $jadwal->details()->create([
                        'posyandu_id' => $detail['posyandu_id'],
                        'kegiatan_id' => $detail['kegiatan_id'],
                        'tgl_mulai' => $detail['tgl_mulai'],
                        'tgl_selesai' => $detail['tgl_selesai'],
                        'jam_mulai' => $detail['jam_mulai'] ?? null,
                        'jam_selesai' => $detail['jam_selesai'] ?? null,
                        'tipe_kegiatan' => $detail['tipe_kegiatan'],
                        'status' => $detail['status'] ?? 'terjadwal',
                        'keterangan' => $detail['keterangan'] ?? null,
                    ]);

                    $existingIds[] = $newDetail->id;
                }
            }

            $query = $jadwal->details();

            if (count($existingIds) > 0) {
                $query->whereNotIn('id', $existingIds);
            }

            $query->delete();
        });

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal berhasil disimpan.'
            );
    }

    public function destroy(JadwalBulanan $jadwal)
    {
        if (!in_array($jadwal->status, ['draft', 'ditolak'])) {
            return back()->with(
                'error',
                'Jadwal hanya dapat dihapus ketika berstatus draft atau ditolak.'
            );
        }

        $jadwal->delete();

        return redirect()
            ->route('jadwal.index')
            ->with(
                'success',
                'Jadwal berhasil dihapus.'
            );
    }

    public function generate(JadwalBulanan $jadwal)
    {
        if (!in_array($jadwal->status, ['draft', 'ditolak'])) {
            return back()->with(
                'error',
                'Jadwal tidak dapat digenerate pada status saat ini.'
            );
        }

        $hariOperasionals = HariOperasional::where('aktif', true)
            ->with('posyandu')
            ->get()
            ->groupBy('hari');

        $kegiatans = Kegiatan::where('aktif', true)
            ->orderBy('id')
            ->get();

        if ($kegiatans->isEmpty()) {
            return back()->with(
                'error',
                'Belum ada kegiatan aktif yang dapat digunakan.'
            );
        }

        $startDate = Carbon::create(
            $jadwal->tahun,
            $jadwal->bulan,
            1
        )->startOfMonth();

        $endDate = $startDate->copy()->endOfMonth();

        DB::transaction(function () use (
            $jadwal,
            $hariOperasionals,
            $kegiatans,
            $startDate,
            $endDate
        ) {
            $jadwal->details()->delete();

            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $hari = $currentDate->dayOfWeekIso;

                $operasionals = $hariOperasionals->get(
                    $hari,
                    collect()
                );

                foreach ($operasionals as $operasional) {
                    $kegiatan = $kegiatans->first();

                    if (!$kegiatan) {
                        continue;
                    }

                    $jadwal->details()->create([
                        'posyandu_id' => $operasional->posyandu_id,
                        'kegiatan_id' => $kegiatan->id,
                        'tgl_mulai' => $currentDate->toDateString(),
                        'tgl_selesai' => $currentDate->toDateString(),
                        'jam_mulai' => $operasional->jam_mulai,
                        'jam_selesai' => $operasional->jam_selesai,
                        'tipe_kegiatan' => 'DG',
                        'status' => 'terjadwal',
                        'keterangan' => null,
                    ]);
                }

                $currentDate->addDay();
            }
        });

        return redirect()
            ->route('jadwal.edit', $jadwal)
            ->with(
                'success',
                'Jadwal berhasil digenerate berdasarkan hari operasional.'
            );
    }

    public function pdf(JadwalBulanan $jadwal)
    {
        $jadwal->load([
            'dibuatOleh',
            'approvedBy',
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

        $pdf->setPaper('a4', 'landscape');

        $filename = sprintf(
            'jadwal-posyandu-%d-%02d.pdf',
            $jadwal->tahun,
            $jadwal->bulan
        );

        return $pdf->download($filename);
    }

    public function excel(JadwalBulanan $jadwal)
    {
        $filename = sprintf(
            'jadwal-posyandu-%d-%02d.xlsx',
            $jadwal->tahun,
            $jadwal->bulan
        );

        return Excel::download(
            new JadwalBulananExport($jadwal),
            $filename
        );
    }

    private function validateDetailDates(
        array $details,
        JadwalBulanan $jadwal
    ): void {
        $startDate = Carbon::create(
            $jadwal->tahun,
            $jadwal->bulan,
            1
        )->startOfMonth();

        $endDate = $startDate->copy()->endOfMonth();

        foreach ($details as $index => $detail) {
            $mulai = Carbon::parse($detail['tgl_mulai']);
            $selesai = Carbon::parse($detail['tgl_selesai']);

            if ($mulai->lt($startDate) || $mulai->gt($endDate)) {
                throw ValidationException::withMessages([
                    "details.$index.tgl_mulai" =>
                        'Tanggal kegiatan harus berada di bulan jadwal.',
                ]);
            }

            if ($selesai->lt($startDate) || $selesai->gt($endDate)) {
                throw ValidationException::withMessages([
                    "details.$index.tgl_selesai" =>
                        'Tanggal kegiatan harus berada di bulan jadwal.',
                ]);
            }

            if ($selesai->lt($mulai)) {
                throw ValidationException::withMessages([
                    "details.$index.tgl_selesai" =>
                        'Tanggal selesai tidak boleh sebelum tanggal mulai.',
                ]);
            }
        }
    }

    private function validateDetailTimes(array $details): void
    {
        foreach ($details as $index => $detail) {
            if (
                !empty($detail['jam_mulai']) &&
                !empty($detail['jam_selesai'])
            ) {
                if (
                    $detail['jam_selesai'] <=
                    $detail['jam_mulai']
                ) {
                    throw ValidationException::withMessages([
                        "details.$index.jam_selesai" =>
                            'Jam selesai harus lebih besar dari jam mulai.',
                    ]);
                }
            }
        }
    }

    private function validateScheduleConflicts(array $details): void
    {
        $count = count($details);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $a = $details[$i];
                $b = $details[$j];

                if (
                    (int) $a['posyandu_id'] !==
                    (int) $b['posyandu_id']
                ) {
                    continue;
                }

                $aStart = Carbon::parse($a['tgl_mulai']);
                $aEnd = Carbon::parse($a['tgl_selesai']);

                $bStart = Carbon::parse($b['tgl_mulai']);
                $bEnd = Carbon::parse($b['tgl_selesai']);

                $dateOverlap =
                    $aStart->lte($bEnd) &&
                    $bStart->lte($aEnd);

                if (!$dateOverlap) {
                    continue;
                }

                if (
                    empty($a['jam_mulai']) ||
                    empty($a['jam_selesai']) ||
                    empty($b['jam_mulai']) ||
                    empty($b['jam_selesai'])
                ) {
                    throw ValidationException::withMessages([
                        'details' =>
                            'Terdapat jadwal yang berpotensi bentrok pada Posyandu yang sama. Pastikan tanggal dan jam kegiatan sudah diatur.',
                    ]);
                }

                $aStartTime = Carbon::createFromFormat(
                    'H:i',
                    $a['jam_mulai']
                );

                $aEndTime = Carbon::createFromFormat(
                    'H:i',
                    $a['jam_selesai']
                );

                $bStartTime = Carbon::createFromFormat(
                    'H:i',
                    $b['jam_mulai']
                );

                $bEndTime = Carbon::createFromFormat(
                    'H:i',
                    $b['jam_selesai']
                );

                $timeOverlap =
                    $aStartTime->lt($bEndTime) &&
                    $bStartTime->lt($aEndTime);

                if ($timeOverlap) {
                    throw ValidationException::withMessages([
                        'details' =>
                            'Terdapat jadwal bentrok pada Posyandu yang sama. Silakan periksa kembali tanggal dan jam kegiatan.',
                    ]);
                }
            }
        }
    }

    private function createStatusLog(
        JadwalBulanan $jadwal,
        ?string $statusLama,
        string $statusBaru,
        ?string $keterangan = null
    ): void {
        $jadwal->statusLogs()->create([
            'user_id' => auth()->id(),
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'keterangan' => $keterangan,
            'created_at' => now(),
        ]);
    }
}