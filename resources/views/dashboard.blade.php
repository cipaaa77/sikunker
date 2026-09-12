@extends('layouts.app')

@section('content')

<style>
    .dashboard {
        padding: 24px 0;
    }

    .page-heading {
        margin-bottom: 22px;
    }

    .page-heading h4 {
        font-size: 22px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    .page-heading p {
        font-size: 13px;
        color: #ffffff;
        margin: 5px 0 0;
    }

    .dashboard-date {
        color: #ffffff;
        font-size: 12px;
    }

    .kpi-card,
    .panel {
        background: #ffffff;
        border: 1px solid #edf0ef;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .025);
    }

    .kpi-card {
        padding: 18px;
    }

    .kpi-label {
        font-size: 11px;
        color: #7b8794;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .kpi-value {
        font-size: 27px;
        font-weight: 700;
        color: #1f2937;
        margin-top: 7px;
    }

    .kpi-description {
        font-size: 11px;
        color: #9aa5ad;
        margin-top: 5px;
    }

    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .icon-jade {
        background: #e8f3f0;
        color: #176653;
    }

    .icon-blue {
        background: #edf4fc;
        color: #3978b8;
    }

    .icon-orange {
        background: #fff5e8;
        color: #d88a25;
    }

    .icon-green {
        background: #eaf7f1;
        color: #299467;
    }

    .panel-header {
        padding: 17px 18px 0;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .panel-subtitle {
        font-size: 11px;
        color: #8a949e;
        margin-top: 3px;
    }

    .panel-body {
        padding: 16px 18px;
    }

    .chart-box {
        height: 285px;
        position: relative;
    }

    .status-row {
        padding: 11px 0;
        border-bottom: 1px solid #f0f2f2;
    }

    .status-row:last-child {
        border-bottom: 0;
    }

    .status-name {
        font-size: 12px;
        color: #53616a;
    }

    .status-number {
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .dot-draft {
        background: #94a3b8;
    }

    .dot-submit {
        background: #e9a23b;
    }

    .dot-approved {
        background: #4387c5;
    }

    .dot-total {
        background: #2da06f;
    }

    .schedule-row {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f2;
    }

    .schedule-row:last-child {
        border-bottom: 0;
    }

    .date-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: #edf6f3;
        color: #176653;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .date-day {
        font-size: 16px;
        font-weight: 700;
        line-height: 1;
    }

    .date-month {
        font-size: 8px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .schedule-name {
        font-size: 12px;
        font-weight: 600;
        color: #27343a;
    }

    .schedule-detail {
        font-size: 10px;
        color: #89939b;
        margin-top: 3px;
    }

    .schedule-time {
        font-size: 10px;
        color: #176653;
        font-weight: 600;
        white-space: nowrap;
    }

    .activity-row {
        display: flex;
        gap: 11px;
        padding: 11px 0;
        border-bottom: 1px solid #f0f2f2;
    }

    .activity-row:last-child {
        border-bottom: 0;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #edf6f3;
        color: #176653;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .activity-title {
        font-size: 11px;
        font-weight: 600;
        color: #37434a;
    }

    .activity-meta {
        font-size: 10px;
        color: #8b959d;
        margin-top: 3px;
    }

    .badge-status {
        font-size: 9px;
        font-weight: 600;
        padding: 4px 7px;
        border-radius: 7px;
        white-space: nowrap;
    }

    .badge-draft {
        background: #f1f4f6;
        color: #71808a;
    }

    .badge-diajukan {
        background: #fff3df;
        color: #c68120;
    }

    .badge-disetujui {
        background: #eaf3fb;
        color: #3978b8;
    }

    .count-badge {
        display: inline-flex;
        min-width: 27px;
        height: 25px;
        padding: 0 7px;
        align-items: center;
        justify-content: center;
        background: #edf6f3;
        color: #176653;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
    }

    .empty {
        padding: 25px 10px;
        text-align: center;
        color: #929ba1;
        font-size: 11px;
    }

    .section-gap {
        margin-top: 18px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f7f9f8;
        color: #71808a;
        border: 0;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 11px 12px;
    }

    .table tbody td {
        font-size: 11px;
        color: #4b5961;
        padding: 12px;
        border-color: #f0f2f2;
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: 0;
    }

    @media (max-width: 768px) {
        .dashboard {
            padding: 16px 0;
        }

        .chart-box {
            height: 240px;
        }

        .kpi-value {
            font-size: 23px;
        }

        .schedule-time {
            display: none;
        }
    }
</style>

<div class="container-fluid dashboard">

    {{-- HEADER --}}
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end">

        <div>
            <h4>Dashboard</h4>
            <p>Ringkasan aktivitas dan penjadwalan Posyandu.</p>
        </div>

        <div class="dashboard-date mt-2 mt-md-0">
            <i class="far fa-calendar-alt me-1"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>

    </div>

    {{-- KPI --}}
    <div class="row g-3">

        <div class="col-xl-3 col-md-6 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Posyandu Aktif</div>
                        <div class="kpi-value">{{ $totalPosyandu }}</div>
                        <div class="kpi-description">
                            Total Posyandu aktif
                        </div>
                    </div>

                    <div class="kpi-icon icon-jade">
                        <i class="fas fa-house-medical"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Jadwal Bulan Ini</div>
                        <div class="kpi-value">{{ $jadwalBulanIni }}</div>
                        <div class="kpi-description">
                            Kegiatan bulan berjalan
                        </div>
                    </div>

                    <div class="kpi-icon icon-blue">
                        <i class="fas fa-calendar-days"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Terjadwal</div>
                        <div class="kpi-value">{{ $totalTerjadwal }}</div>
                        <div class="kpi-description">
                            Kegiatan yang akan datang
                        </div>
                    </div>

                    <div class="kpi-icon icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Selesai</div>
                        <div class="kpi-value">{{ $totalSelesai }}</div>
                        <div class="kpi-description">
                            Kegiatan yang telah selesai
                        </div>
                    </div>

                    <div class="kpi-icon icon-green">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ANALYTICS --}}
    <div class="row g-3 section-gap">

        {{-- CHART --}}
        <div class="col-lg-8">
            <div class="panel h-100">

                <div class="panel-header">
                    <div class="panel-title">Tren Kegiatan</div>
                    <div class="panel-subtitle">
                        Jumlah kegiatan dalam 6 bulan terakhir
                    </div>
                </div>

                <div class="panel-body">
                    <div class="chart-box">
                        <canvas id="scheduleChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

        {{-- STATUS JADWAL --}}
        <div class="col-lg-4">
            <div class="panel h-100">

                <div class="panel-header">
                    <div class="panel-title">Status Jadwal</div>
                    <div class="panel-subtitle">
                        Ringkasan seluruh jadwal bulanan
                    </div>
                </div>

                <div class="panel-body">

                    <div class="status-row d-flex justify-content-between">
                        <span class="status-name">
                            <span class="status-dot dot-draft"></span>
                            Draft
                        </span>

                        <span class="status-number">
                            {{ $totalDraft }}
                        </span>
                    </div>

                    <div class="status-row d-flex justify-content-between">
                        <span class="status-name">
                            <span class="status-dot dot-submit"></span>
                            Diajukan
                        </span>

                        <span class="status-number">
                            {{ $totalDiajukan }}
                        </span>
                    </div>

                    <div class="status-row d-flex justify-content-between">
                        <span class="status-name">
                            <span class="status-dot dot-approved"></span>
                            Disetujui
                        </span>

                        <span class="status-number">
                            {{ $totalDisetujui }}
                        </span>
                    </div>

                    <div class="mt-3 pt-2 border-top d-flex justify-content-between">
                        <span class="small fw-semibold">
                            Total Jadwal
                        </span>

                        <span class="count-badge">
                            {{ $totalJadwal }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- JADWAL TERDEKAT + AKTIVITAS --}}
    <div class="row g-3 section-gap">

        {{-- JADWAL TERDEKAT --}}
        <div class="col-lg-7">
            <div class="panel h-100">

                <div class="panel-header d-flex justify-content-between align-items-start">

                    <div>
                        <div class="panel-title">Jadwal Terdekat</div>
                        <div class="panel-subtitle">
                            Kegiatan dari Posyandu aktif
                        </div>
                    </div>

                    <a href="{{ route('jadwal.index') }}"
                       class="small text-decoration-none"
                       style="color:#176653">
                        Lihat semua
                    </a>

                </div>

                <div class="panel-body">

                    @forelse($upcomingSchedules as $schedule)

                        <div class="schedule-row">

                            <div class="date-box">
                                <div class="date-day">
                                    {{ optional($schedule->tgl_mulai)->format('d') }}
                                </div>

                                <div class="date-month">
                                    {{ optional($schedule->tgl_mulai)->translatedFormat('M') }}
                                </div>
                            </div>

                            <div class="flex-grow-1">

                                <div class="schedule-name">
                                    {{ $schedule->posyandu->nama_posyandu ?? 'Posyandu' }}
                                </div>

                                <div class="schedule-detail">

                                    {{ $schedule->kegiatan->nama_kegiatan ?? 'Kegiatan' }}

                                    @if($schedule->posyandu?->wilayah)
                                        · {{ $schedule->posyandu->wilayah->nama_wilayah }}
                                    @endif

                                </div>

                            </div>

                            <div class="schedule-time">
                                {{ $schedule->jam_mulai
                                    ? \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i')
                                    : '--:--'
                                }}
                            </div>

                        </div>

                    @empty

                        <div class="empty">
                            Belum ada jadwal terdekat.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        {{-- AKTIVITAS TERBARU --}}
        <div class="col-lg-5">
            <div class="panel h-100">

                <div class="panel-header">
                    <div class="panel-title">Aktivitas Terbaru</div>
                    <div class="panel-subtitle">
                        Riwayat perubahan jadwal
                    </div>
                </div>

                <div class="panel-body">

                    @forelse($activityLogs as $log)

                        <div class="activity-row">

                            <div class="activity-icon">

                                @if($log->status_baru === 'disetujui')
                                    <i class="fas fa-check"></i>
                                @elseif($log->status_baru === 'ditolak')
                                    <i class="fas fa-xmark"></i>
                                @elseif($log->status_baru === 'diajukan')
                                    <i class="fas fa-paper-plane"></i>
                                @else
                                    <i class="fas fa-pen"></i>
                                @endif

                            </div>

                            <div class="flex-grow-1">

                                <div class="d-flex justify-content-between gap-2">

                                    <div class="activity-title">

                                        @if($log->jadwal)
                                            Jadwal
                                            {{ str_pad($log->jadwal->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $log->jadwal->tahun }}
                                        @else
                                            Jadwal
                                        @endif

                                    </div>

                                    <span class="badge-status
                                        @if($log->status_baru === 'draft')
                                            badge-draft
                                        @elseif($log->status_baru === 'diajukan')
                                            badge-diajukan
                                        @elseif($log->status_baru === 'disetujui')
                                            badge-disetujui
                                        @endif
                                    ">
                                        {{ strtoupper($log->status_baru) }}
                                    </span>

                                </div>

                                <div class="activity-meta">

                                    {{ $log->user->name ?? 'System' }}

                                    @if($log->created_at)
                                        · {{ $log->created_at->format('d M Y, H:i') }}
                                    @endif

                                    @if($log->keterangan)
                                        · {{ $log->keterangan }}
                                    @endif

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty">
                            Belum ada aktivitas.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIK POSYANDU + KEGIATAN --}}
    <div class="row g-3 section-gap">

        {{-- STATISTIK POSYANDU --}}
        <div class="col-lg-8">
            <div class="panel">

                <div class="panel-header">
                    <div class="panel-title">Statistik Posyandu</div>
                    <div class="panel-subtitle">
                        Jumlah jadwal dari Posyandu aktif
                    </div>
                </div>

                <div class="panel-body">

                    <div class="table-responsive">

                        <table class="table">

                            <thead>
                                <tr>
                                    <th>Posyandu</th>
                                    <th>Wilayah</th>
                                    <th class="text-center">Kegiatan</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($posyanduSummary as $posyandu)

                                    <tr>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $posyandu->nama_posyandu }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $posyandu->kode_posyandu ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $posyandu->wilayah->nama_wilayah ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            <span class="count-badge">
                                                {{ $posyandu->total_jadwal }}
                                            </span>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="3">
                                            <div class="empty">
                                                Belum ada data Posyandu aktif.
                                            </div>
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>

        {{-- DISTRIBUSI KEGIATAN --}}
        <div class="col-lg-4">
            <div class="panel h-100">

                <div class="panel-header">
                    <div class="panel-title">Kegiatan</div>
                    <div class="panel-subtitle">
                        Distribusi kegiatan dari Posyandu aktif
                    </div>
                </div>

                <div class="panel-body">

                    @forelse($activityDistribution as $activity)

                        <div class="status-row d-flex justify-content-between align-items-center">

                            <div>
                                <div class="status-name">
                                    {{ $activity->kegiatan->nama_kegiatan ?? 'Kegiatan' }}
                                </div>

                                <small class="text-muted">
                                    {{ $activity->kegiatan->kode_kegiatan ?? '-' }}
                                </small>
                            </div>

                            <span class="count-badge">
                                {{ $activity->total_jadwal }}
                            </span>

                        </div>

                    @empty

                        <div class="empty">
                            Belum ada data kegiatan.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chart = document.getElementById('scheduleChart');

    if (chart) {
        const ctx = chart.getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 280);

        gradient.addColorStop(0, 'rgba(23,102,83,.22)');
        gradient.addColorStop(1, 'rgba(23,102,83,0)');

        new Chart(ctx, {
            type: 'line',

            data: {
                labels: @json($chartLabels),

                datasets: [{
                    label: 'Kegiatan',
                    data: @json($chartJadwal),
                    borderColor: '#176653',
                    backgroundColor: gradient,
                    fill: true,
                    tension: .4,
                    borderWidth: 2.5,
                    pointRadius: 3.5,
                    pointHoverRadius: 5
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        displayColors: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0,
                            color: '#879198'
                        },

                        grid: {
                            color: 'rgba(0,0,0,.05)'
                        }
                    },

                    x: {
                        ticks: {
                            color: '#879198'
                        },

                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>

@endpush