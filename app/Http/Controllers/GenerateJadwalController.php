<?php

namespace App\Http\Controllers;

use App\Models\HariOperasional;
use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use App\Models\Kegiatan;
use App\Models\Posyandu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateJadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalBulanan::withCount([
            'details',
            'details as jumlah_selesai' => fn ($query) =>
                $query->where('status', 'selesai'),
            'details as jumlah_dibatalkan' => fn ($query) =>
                $query->where('status', 'dibatalkan'),
        ])
            ->with([
                'details.posyandu',
                'details.kegiatan',
            ])
            ->latest('tahun')
            ->latest('bulan')
            ->paginate(12);

        $totalJadwal = JadwalBulanan::count();

        $totalDetail = JadwalDetail::count();

        $totalPosyandu = Posyandu::where('aktif', true)->count();

        return view('pages.generate-jadwal.index', compact(
            'jadwals',
            'totalJadwal',
            'totalDetail',
            'totalPosyandu'
        ));
    }

    public function create()
    {
        $posyandus = Posyandu::where('aktif', true)
            ->orderBy('nama_posyandu')
            ->get();

        $kegiatans = Kegiatan::where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        return view('pages.generate-jadwal.create', compact(
            'posyandus',
            'kegiatans'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2100'],
            'posyandu_id' => ['nullable', 'exists:posyandus,id'],
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'tipe_kegiatan' => ['required', 'in:DG,LG'],
        ], [
            'bulan.required' => 'Bulan wajib dipilih.',
            'tahun.required' => 'Tahun wajib diisi.',
            'posyandu_id.exists' => 'Posyandu tidak ditemukan.',
            'kegiatan_id.required' => 'Kegiatan wajib dipilih.',
            'kegiatan_id.exists' => 'Kegiatan tidak ditemukan.',
            'tipe_kegiatan.required' => 'Tipe kegiatan wajib dipilih.',
        ]);

        $jadwal = JadwalBulanan::firstOrCreate(
            [
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
            ],
            [
                'status' => 'draft',
                'dibuat_oleh' => auth()->id(),
            ]
        );

        if (!in_array($jadwal->status, ['draft', 'ditolak'])) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal tersebut sudah diajukan, disetujui, atau final.'
                );
        }

        $startDate = Carbon::create(
            $validated['tahun'],
            $validated['bulan'],
            1
        )->startOfMonth();

        $endDate = $startDate->copy()->endOfMonth();

        $hariOperasionals = HariOperasional::query()
            ->where('aktif', true)
            ->when(
                $validated['posyandu_id'] ?? null,
                fn ($query, $posyanduId) =>
                    $query->where('posyandu_id', $posyanduId)
            )
            ->with('posyandu')
            ->get()
            ->groupBy('hari');

        if ($hariOperasionals->isEmpty()) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Belum ada hari operasional aktif untuk Posyandu yang dipilih.'
                );
        }

        $jumlahGenerate = 0;

        DB::transaction(function () use (
            $jadwal,
            $startDate,
            $endDate,
            $hariOperasionals,
            $validated,
            &$jumlahGenerate
        ) {
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $hari = $currentDate->dayOfWeekIso;

                $operasionals = $hariOperasionals->get(
                    $hari,
                    collect()
                );

                foreach ($operasionals as $operasional) {

                    $sudahAda = $jadwal->details()
                        ->where('posyandu_id', $operasional->posyandu_id)
                        ->whereDate('tgl_mulai', $currentDate->toDateString())
                        ->exists();

                    if ($sudahAda) {
                        continue;
                    }

                    $jadwal->details()->create([
                        'posyandu_id' => $operasional->posyandu_id,
                        'kegiatan_id' => $validated['kegiatan_id'],
                        'tgl_mulai' => $currentDate->toDateString(),
                        'tgl_selesai' => $currentDate->toDateString(),
                        'jam_mulai' => $operasional->jam_mulai
                            ? Carbon::parse($operasional->jam_mulai)->format('H:i')
                            : null,
                        'jam_selesai' => $operasional->jam_selesai
                            ? Carbon::parse($operasional->jam_selesai)->format('H:i')
                            : null,
                        'tipe_kegiatan' => $validated['tipe_kegiatan'],
                        'status' => 'terjadwal',
                    ]);

                    $jumlahGenerate++;
                }

                $currentDate->addDay();
            }

            if ($jadwal->status === 'ditolak') {
                $jadwal->update([
                    'status' => 'draft',
                ]);
            }
        });

        if ($jumlahGenerate === 0) {
            return redirect()
                ->route('generate-jadwal.show', $jadwal)
                ->with(
                    'warning',
                    'Tidak ada jadwal baru yang dibuat. Detail pada periode tersebut mungkin sudah tersedia.'
                );
        }

        return redirect()
            ->route('generate-jadwal.show', $jadwal)
            ->with(
                'success',
                $jumlahGenerate . ' jadwal berhasil digenerate.'
            );
    }

    public function show(JadwalBulanan $jadwal)
    {
        $jadwal->load([
            'dibuatOleh',
            'details' => function ($query) {
                $query
                    ->with([
                        'posyandu.wilayah',
                        'kegiatan',
                    ])
                    ->orderBy('tgl_mulai')
                    ->orderBy('jam_mulai');
            },
        ]);

        $groupedDetails = $jadwal->details->groupBy(
            fn ($detail) => $detail->posyandu_id
        );

        return view(
            'pages.generate-jadwal.show',
            compact('jadwal', 'groupedDetails')
        );
    }
}