<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\JadwalDetail;
use App\Models\JadwalBulanan;
use App\Models\JadwalStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        /*
        |--------------------------------------------------------------------------
        | POSYANDU AKTIF
        |--------------------------------------------------------------------------
        */

        $totalPosyandu = Posyandu::query()
            ->where('aktif', true)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR DETAIL JADWAL
        |--------------------------------------------------------------------------
        |
        | Hanya mengambil jadwal dari Posyandu yang masih aktif.
        |
        */

        $activeScheduleQuery = JadwalDetail::query()
            ->whereHas('posyandu', function ($query) {
                $query->where('aktif', true);
            });

        /*
        |--------------------------------------------------------------------------
        | JADWAL BULAN INI
        |--------------------------------------------------------------------------
        */

        $jadwalBulanIni = (clone $activeScheduleQuery)
            ->whereMonth('tgl_mulai', $currentMonth)
            ->whereYear('tgl_mulai', $currentYear)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL TERJADWAL
        |--------------------------------------------------------------------------
        */
$totalTerjadwal = (clone $activeScheduleQuery)
    ->whereHas('jadwal', function ($query) {
        $query->where('status', 'disetujui');
    })
    ->whereDate('tgl_mulai', '>=', $today)
    ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL SELESAI
        |--------------------------------------------------------------------------
        */

  $totalSelesai = (clone $activeScheduleQuery)
    ->whereHas('jadwal', function ($query) {
        $query->where('status', 'disetujui');
    })
    ->whereDate('tgl_mulai', '<', $today)
    ->count();
        /*
        |--------------------------------------------------------------------------
        | STATUS JADWAL BULANAN
        |--------------------------------------------------------------------------
        */

        $totalDraft = JadwalBulanan::query()
            ->where('status', 'draft')
            ->count();

        $totalDiajukan = JadwalBulanan::query()
            ->where('status', 'diajukan')
            ->count();

        $totalDisetujui = JadwalBulanan::query()
            ->where('status', 'disetujui')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL JADWAL BULANAN
        |--------------------------------------------------------------------------
        */

        $totalJadwal = JadwalBulanan::query()->count();

        /*
        |--------------------------------------------------------------------------
        | JADWAL TERDEKAT
        |--------------------------------------------------------------------------
        |
        | Jadwal Posyandu nonaktif tidak akan ditampilkan.
        |
        */

    $upcomingSchedules = JadwalDetail::query()
    ->with([
        'posyandu.wilayah',
        'kegiatan',
        'jadwal',
    ])
    ->whereHas('posyandu', function ($query) {
        $query->where('aktif', true);
    })
    ->whereHas('jadwal', function ($query) {
        $query->where('status', 'disetujui');
    })
    ->whereDate('tgl_mulai', '>=', $today)
    ->orderBy('tgl_mulai')
    ->orderBy('jam_mulai')
    ->limit(5)
    ->get();

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PERUBAHAN STATUS
        |--------------------------------------------------------------------------
        */

        $activityLogs = JadwalStatusLog::query()
            ->with([
                'user',
                'jadwal',
            ])
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN PER POSYANDU
        |--------------------------------------------------------------------------
        */

        $posyanduSummary = Posyandu::query()
            ->where('aktif', true)
            ->with('wilayah')
            ->withCount([
                'jadwalDetails as total_jadwal' => function ($query) {
                    $query->whereHas('jadwal', function ($jadwalQuery) {
                        $jadwalQuery->whereIn('status', [
                            'draft',
                            'diajukan',
                            'disetujui',
                        ]);
                    });
                },
            ])
            ->orderByDesc('total_jadwal')
            ->orderBy('nama_posyandu')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DISTRIBUSI KEGIATAN
        |--------------------------------------------------------------------------
        */

        $activityDistribution = JadwalDetail::query()
            ->select(
                'kegiatan_id',
                DB::raw('COUNT(*) as total_jadwal')
            )
            ->with('kegiatan')
            ->whereHas('posyandu', function ($query) {
                $query->where('aktif', true);
            })
            ->groupBy('kegiatan_id')
            ->orderByDesc('total_jadwal')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA CHART 6 BULAN TERAKHIR
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartJadwal = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()
                ->startOfMonth()
                ->subMonths($i);

            $chartLabels[] = $date->translatedFormat('M Y');

            $chartJadwal[] = JadwalDetail::query()
                ->whereHas('posyandu', function ($query) {
                    $query->where('aktif', true);
                })
                ->whereMonth('tgl_mulai', $date->month)
                ->whereYear('tgl_mulai', $date->year)
                ->count();
        }

     return view('dashboard', compact(
    'totalPosyandu',
    'jadwalBulanIni',
    'totalTerjadwal',
    'totalSelesai',
    'totalDraft',
    'totalDiajukan',
    'totalDisetujui',
    'totalJadwal',
    'upcomingSchedules',
    'activityLogs',
    'posyanduSummary',
    'activityDistribution',
    'chartLabels',
    'chartJadwal'
));
    }
}