<?php

namespace App\Http\Controllers;

use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use App\Models\Posyandu;
use App\Models\Kegiatan;
use App\Models\JadwalStatusLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $totalPosyandu = Posyandu::where('aktif', true)->count();

        $jadwalBulanIni = JadwalBulanan::where('bulan', $now->month)
            ->where('tahun', $now->year)
            ->count();

        $totalTerjadwal = JadwalDetail::where('status', 'terjadwal')
            ->whereMonth('tgl_mulai', $now->month)
            ->whereYear('tgl_mulai', $now->year)
            ->count();

        $totalSelesai = JadwalDetail::where('status', 'selesai')
            ->whereMonth('tgl_mulai', $now->month)
            ->whereYear('tgl_mulai', $now->year)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TIME SERIES - 6 BULAN TERAKHIR
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartJadwal = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);

            $chartLabels[] = $date->translatedFormat('M Y');

            $chartJadwal[] = JadwalDetail::whereMonth(
                'tgl_mulai',
                $date->month
            )
                ->whereYear(
                    'tgl_mulai',
                    $date->year
                )
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | DISTRIBUSI KEGIATAN
        |--------------------------------------------------------------------------
        */

        $activityDistribution = Kegiatan::query()
            ->where('aktif', true)
            ->withCount([
                'jadwalDetails as total_jadwal' => function ($query) {
                    $query->whereMonth(
                        'tgl_mulai',
                        now()->month
                    )->whereYear(
                        'tgl_mulai',
                        now()->year
                    );
                }
            ])
            ->orderByDesc('total_jadwal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | LOG AKTIVITAS TERBARU
        |--------------------------------------------------------------------------
        */

        $activityLogs = JadwalStatusLog::with([
            'user',
            'jadwal',
        ])
            ->latest('created_at')
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JADWAL TERDEKAT
        |--------------------------------------------------------------------------
        */

        $upcomingSchedules = JadwalDetail::with([
            'posyandu.wilayah',
            'kegiatan',
        ])
            ->where('status', 'terjadwal')
            ->whereDate('tgl_mulai', '>=', $now->toDateString())
            ->orderBy('tgl_mulai')
            ->orderBy('jam_mulai')
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK POSYANDU
        |--------------------------------------------------------------------------
        */

        $posyanduSummary = Posyandu::query()
            ->where('aktif', true)
            ->with('wilayah')
            ->withCount([
                'jadwalDetails as total_jadwal' => function ($query) {
                    $query->whereMonth(
                        'tgl_mulai',
                        now()->month
                    )->whereYear(
                        'tgl_mulai',
                        now()->year
                    );
                },
            ])
            ->orderByDesc('total_jadwal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATUS JADWAL
        |--------------------------------------------------------------------------
        */

        $totalDraft = JadwalBulanan::where('status', 'draft')->count();

        $totalDiajukan = JadwalBulanan::where('status', 'diajukan')->count();

        $totalDisetujui = JadwalBulanan::where('status', 'disetujui')->count();

        $totalFinal = JadwalBulanan::where('status', 'final')->count();

        /*
        |--------------------------------------------------------------------------
        | DATA TAMBAHAN
        |--------------------------------------------------------------------------
        */

        $totalKegiatan = Kegiatan::where('aktif', true)->count();

        $totalJadwal = JadwalBulanan::count();

        return view('dashboard', compact(
            'totalPosyandu',
            'jadwalBulanIni',
            'totalTerjadwal',
            'totalSelesai',

            'chartLabels',
            'chartJadwal',

            'activityDistribution',
            'activityLogs',
            'upcomingSchedules',
            'posyanduSummary',

            'totalDraft',
            'totalDiajukan',
            'totalDisetujui',
            'totalFinal',

            'totalKegiatan',
            'totalJadwal'
        ));
    }
}