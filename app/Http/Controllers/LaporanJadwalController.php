<?php

namespace App\Http\Controllers;

use App\Models\JadwalBulanan;
use App\Models\Posyandu;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanJadwalController extends Controller
{
    /**
     * Menampilkan laporan jadwal.
     *
     * GET /laporan-jadwal
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $mulai = $request->input('mulai');
        $sampai = $request->input('sampai');
        $status = $request->input('status');
        $posyanduId = $request->input('posyandu_id');
        $kegiatanId = $request->input('kegiatan_id');

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'mulai' => [
                'nullable',
                'date',
            ],

            'sampai' => [
                'nullable',
                'date',
                'after_or_equal:mulai',
            ],

            'status' => [
                'nullable',
                'string',
                'in:draft,diajukan,disetujui,ditolak',
            ],

            'posyandu_id' => [
                'nullable',
                'integer',
            ],

            'kegiatan_id' => [
                'nullable',
                'integer',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | KONVERSI DATETIME
        |--------------------------------------------------------------------------
        */

        $mulaiDateTime = null;
        $sampaiDateTime = null;

        if ($mulai) {
            $mulaiDateTime = Carbon::parse($mulai)
                ->format('Y-m-d H:i:s');
        }

        if ($sampai) {
            $sampaiDateTime = Carbon::parse($sampai)
                ->format('Y-m-d H:i:s');
        }

        /*
        |--------------------------------------------------------------------------
        | QUERY JADWAL
        |--------------------------------------------------------------------------
        |
        | Status berada pada tabel jadwal_bulanans.
        | Detail berada pada tabel jadwal_details.
        |
        */

        $query = JadwalBulanan::query()
            ->with([
                'details' => function ($detailQuery) use (
                    $posyanduId,
                    $kegiatanId,
                    $mulaiDateTime,
                    $sampaiDateTime
                ) {
                    $detailQuery
                        ->with([
                            'posyandu',
                            'kegiatan',
                        ])

                        /*
                        | Filter Posyandu
                        */
                        ->when(
                            $posyanduId,
                            function ($q) use ($posyanduId) {
                                $q->where(
                                    'posyandu_id',
                                    $posyanduId
                                );
                            }
                        )

                        /*
                        | Filter Kegiatan
                        */
                        ->when(
                            $kegiatanId,
                            function ($q) use ($kegiatanId) {
                                $q->where(
                                    'kegiatan_id',
                                    $kegiatanId
                                );
                            }
                        )

                        /*
                        | Filter Dari Tanggal/Waktu
                        */
                        ->when(
                            $mulaiDateTime,
                            function ($q) use ($mulaiDateTime) {
                                $q->whereRaw(
                                    'TIMESTAMP(tgl_mulai, jam_mulai) >= ?',
                                    [$mulaiDateTime]
                                );
                            }
                        )

                        /*
                        | Filter Sampai Tanggal/Waktu
                        */
                        ->when(
                            $sampaiDateTime,
                            function ($q) use ($sampaiDateTime) {
                                $q->whereRaw(
                                    'TIMESTAMP(tgl_mulai, jam_mulai) <= ?',
                                    [$sampaiDateTime]
                                );
                            }
                        )

                        ->orderBy('tgl_mulai', 'asc')
                        ->orderBy('jam_mulai', 'asc');
                },
            ])

            /*
            |--------------------------------------------------------------------------
            | FILTER STATUS JADWAL INDUK
            |--------------------------------------------------------------------------
            */

            ->when(
                $status,
                function ($q) use ($status) {
                    $q->where('status', $status);
                }
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER POSYANDU PADA DETAIL
            |--------------------------------------------------------------------------
            */

            ->when(
                $posyanduId,
                function ($q) use ($posyanduId) {
                    $q->whereHas(
                        'details',
                        function ($detailQuery) use ($posyanduId) {
                            $detailQuery->where(
                                'posyandu_id',
                                $posyanduId
                            );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER KEGIATAN PADA DETAIL
            |--------------------------------------------------------------------------
            */

            ->when(
                $kegiatanId,
                function ($q) use ($kegiatanId) {
                    $q->whereHas(
                        'details',
                        function ($detailQuery) use ($kegiatanId) {
                            $detailQuery->where(
                                'kegiatan_id',
                                $kegiatanId
                            );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER TANGGAL MULAI PADA DETAIL
            |--------------------------------------------------------------------------
            */

            ->when(
                $mulaiDateTime,
                function ($q) use ($mulaiDateTime) {
                    $q->whereHas(
                        'details',
                        function ($detailQuery) use ($mulaiDateTime) {
                            $detailQuery->whereRaw(
                                'TIMESTAMP(tgl_mulai, jam_mulai) >= ?',
                                [$mulaiDateTime]
                            );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER TANGGAL SAMPAI PADA DETAIL
            |--------------------------------------------------------------------------
            */

            ->when(
                $sampaiDateTime,
                function ($q) use ($sampaiDateTime) {
                    $q->whereHas(
                        'details',
                        function ($detailQuery) use ($sampaiDateTime) {
                            $detailQuery->whereRaw(
                                'TIMESTAMP(tgl_mulai, jam_mulai) <= ?',
                                [$sampaiDateTime]
                            );
                        }
                    );
                }
            )

            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->orderByDesc('id');

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwals = $query->get();

        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN DETAIL DENGAN STATUS JADWAL INDUK
        |--------------------------------------------------------------------------
        |
        | Ini bagian penting agar setiap detail membawa status
        | dari jadwal induknya sendiri.
        |
        */

        $details = collect();

        foreach ($jadwals as $jadwal) {
            foreach ($jadwal->details as $detail) {
                $detail->jadwal_status = $jadwal->status;
                $detail->jadwal_id = $jadwal->id;
                $detail->jadwal_tahun = $jadwal->tahun;
                $detail->jadwal_bulan = $jadwal->bulan;

                $details->push($detail);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalJadwal = $jadwals->count();

        $totalDetail = $details->count();

        $totalPosyandu = $details
            ->pluck('posyandu_id')
            ->filter()
            ->unique()
            ->count();

        $totalKegiatan = $details
            ->pluck('kegiatan_id')
            ->filter()
            ->unique()
            ->count();

        $totalHari = $details
            ->pluck('tgl_mulai')
            ->filter()
            ->unique()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK STATUS
        |--------------------------------------------------------------------------
        |
        | Hanya 4 status.
        |
        */

        $statistikStatus = [
            'draft' => $jadwals
                ->where('status', 'draft')
                ->count(),

            'diajukan' => $jadwals
                ->where('status', 'diajukan')
                ->count(),

            'disetujui' => $jadwals
                ->where('status', 'disetujui')
                ->count(),

            'ditolak' => $jadwals
                ->where('status', 'ditolak')
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | MASTER POSYANDU
        |--------------------------------------------------------------------------
        */

      $posyandus = Posyandu::query()
        ->with('wilayah')
        ->where('aktif', true)
        ->orderBy('nama_posyandu')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | MASTER KEGIATAN
        |--------------------------------------------------------------------------
        */

        $kegiatans = Kegiatan::query()
            ->where('aktif', true)
            ->orderBy('nama_kegiatan')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pages.laporan-jadwal.index',
            compact(
                'jadwals',
                'details',
                'totalJadwal',
                'totalDetail',
                'totalPosyandu',
                'totalKegiatan',
                'totalHari',
                'statistikStatus',
                'posyandus',
                'kegiatans',
                'mulai',
                'sampai',
                'status',
                'posyanduId',
                'kegiatanId'
            )
        );
    }
}