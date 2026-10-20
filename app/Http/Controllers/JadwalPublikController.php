<?php

namespace App\Http\Controllers;

use App\Models\JadwalBulanan;
use App\Models\Posyandu;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class JadwalPublikController extends Controller
{
    /**
     * Landing page masyarakat.
     * GET /
     *
     * Hanya menampilkan jadwal bulanan berstatus disetujui.
     */
    public function landing(Request $request)
    {
        $tahun = (int) $request->query('tahun', now()->year);

        $totalPosyandu = Posyandu::query()
            ->where('aktif', true)
            ->count();

        // Status diperiksa pada tabel jadwal_bulanans.
        $queryDisetujui = JadwalBulanan::query()
            ->where('status', 'disetujui')
            ->whereHas('details');

        $totalJadwal = (clone $queryDisetujui)
            ->where('tahun', $tahun)
            ->count();

        $jadwals = (clone $queryDisetujui)
            ->where('tahun', $tahun)
            ->with([
                'details' => function ($query) {
                    $query
                        ->with([
                            'posyandu.wilayah',
                            'kegiatan',
                        ])
                        ->orderBy('tgl_mulai')
                        ->orderBy('jam_mulai');
                },
            ])
            ->orderBy('bulan')
            ->get();

        return view('public.landing', compact(
            'totalPosyandu',
            'totalJadwal',
            'jadwals',
            'tahun'
        ));
    }

    /**
     * Rekap jadwal publik.
     * GET /jadwal-publik
     *
     * Hanya jadwal bulanan berstatus disetujui yang ditampilkan.
     */
    public function jadwal(Request $request)
    {
        $validated = $request->validate([
            'mulai' => [
                'nullable',
                'date',
            ],
            'sampai' => [
                'nullable',
                'date',
                'after_or_equal:mulai',
            ],
            'wilayah_id' => [
                'nullable',
                'integer',
                'exists:wilayahs,id',
            ],
            'posyandu_id' => [
                'nullable',
                'integer',
                'exists:posyandus,id',
            ],
        ]);

        $mulai = $validated['mulai'] ?? null;
        $sampai = $validated['sampai'] ?? null;
        $wilayahId = $validated['wilayah_id'] ?? null;
        $posyanduId = $validated['posyandu_id'] ?? null;

        // Filter detail untuk eager loading.
        $filterDetails = function ($query) use (
            $mulai,
            $sampai,
            $wilayahId,
            $posyanduId
        ) {
            $query
                ->with([
                    'posyandu.wilayah',
                    'kegiatan',
                ])
                ->when(
                    $mulai,
                    fn ($q) => $q->whereDate(
                        'tgl_mulai',
                        '>=',
                        $mulai
                    )
                )
                ->when(
                    $sampai,
                    fn ($q) => $q->whereDate(
                        'tgl_mulai',
                        '<=',
                        $sampai
                    )
                )
                ->when(
                    $posyanduId,
                    fn ($q) => $q->where(
                        'posyandu_id',
                        $posyanduId
                    )
                )
                ->when(
                    $wilayahId,
                    fn ($q) => $q->whereHas(
                        'posyandu',
                        fn ($p) => $p->where(
                            'wilayah_id',
                            $wilayahId
                        )
                    )
                )
                ->orderBy('tgl_mulai')
                ->orderBy('jam_mulai');
        };

        $jadwals = JadwalBulanan::query()
            // Filter status hanya pada tabel jadwal_bulanans.
            ->where('status', 'disetujui')
            ->whereHas('details', function ($query) use (
                $mulai,
                $sampai,
                $wilayahId,
                $posyanduId
            ) {
                $query
                    ->when(
                        $mulai,
                        fn ($q) => $q->whereDate(
                            'tgl_mulai',
                            '>=',
                            $mulai
                        )
                    )
                    ->when(
                        $sampai,
                        fn ($q) => $q->whereDate(
                            'tgl_mulai',
                            '<=',
                            $sampai
                        )
                    )
                    ->when(
                        $posyanduId,
                        fn ($q) => $q->where(
                            'posyandu_id',
                            $posyanduId
                        )
                    )
                    ->when(
                        $wilayahId,
                        fn ($q) => $q->whereHas(
                            'posyandu',
                            fn ($p) => $p->where(
                                'wilayah_id',
                                $wilayahId
                            )
                        )
                    );
            })
            ->with([
                'details' => $filterDetails,
            ])
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(12)
            ->withQueryString();

        $wilayahs = Wilayah::query()
            ->orderBy('nama_wilayah')
            ->get();

        $posyandus = Posyandu::query()
            ->where('aktif', true)
            ->with('wilayah')
            ->when(
                $wilayahId,
                fn ($q) => $q->where(
                    'wilayah_id',
                    $wilayahId
                )
            )
            ->orderBy('nama_posyandu')
            ->get();

        return view('public.jadwal', compact(
            'jadwals',
            'wilayahs',
            'posyandus',
            'mulai',
            'sampai',
            'wilayahId',
            'posyanduId'
        ));
    }
}
