@extends('layouts.public')

@section('title', 'Beranda Posyandu')

@php
    $schedules = $jadwals ?? collect();

    $scheduleItems = $schedules instanceof \Illuminate\Pagination\AbstractPaginator
        ? $schedules->getCollection()
        : collect($schedules);

    $selectedYear = (int) ($tahun ?? now()->year);
    $todayDate = now()->toDateString();

    // Nama hari & bulan ditulis manual supaya selalu Bahasa Indonesia (tidak bergantung locale server).
    $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $shortMonths = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];

    $statusLabels = ['done' => 'Selesai', 'ongoing' => 'Berlangsung', 'upcoming' => 'Akan datang'];

    $parse = fn ($value) => \Illuminate\Support\Carbon::parse($value);
    $fmtDate = fn ($date) => $date->format('d') . ' ' . $shortMonths[$date->month] . ' ' . $date->year;
    $formatJam = fn ($jam) => $jam ? \Illuminate\Support\Str::substr((string) $jam, 0, 5) : '--:--';

    // Satu grup per bulan. Jumlah hari (28/29/30/31) dihitung otomatis dari kalender.
    $groups = collect($monthNames)
        ->map(function ($name, $number) use ($scheduleItems, $selectedYear) {
            $details = $scheduleItems
                ->filter(fn ($s) => (int) $s->bulan === $number && (int) $s->tahun === $selectedYear)
                ->flatMap(fn ($s) => $s->details ?? collect())
                ->filter(fn ($d) => $d->tgl_mulai)
                ->sortBy(fn ($d) => \Illuminate\Support\Carbon::parse($d->tgl_mulai)->toDateString() . ' ' . $d->jam_mulai)
                ->values();

            return [
                'number' => $number,
                'name' => $name,
                'days' => \Illuminate\Support\Carbon::create($selectedYear, $number, 1)->daysInMonth,
                'details' => $details,
            ];
        })
        ->filter(fn ($group) => $group['details']->isNotEmpty());

    // ---------- Kartu "Hari Ini" & "Kegiatan Berikutnya" ----------
    $yearDetails = $groups->flatMap(fn ($group) => $group['details']);

    $startOf = fn ($d) => $parse($d->tgl_mulai)->toDateString();
    $endOf = fn ($d) => ($d->tgl_selesai ? $parse($d->tgl_selesai) : $parse($d->tgl_mulai))->toDateString();

    $todayItems = $yearDetails
        ->filter(fn ($d) => $startOf($d) <= $todayDate && $endOf($d) >= $todayDate)
        ->sortBy('jam_mulai')
        ->values();

    $nextItem = $yearDetails
        ->filter(fn ($d) => $startOf($d) > $todayDate)
        ->sortBy(fn ($d) => $startOf($d) . ' ' . $d->jam_mulai)
        ->first();

    $placeOf = fn ($d) => ($d->posyandu?->nama_posyandu ?? 'Posyandu')
        . ' · ' . ($d->posyandu?->wilayah?->nama_wilayah ?? 'Wilayah belum tersedia');

    $next = null;

    if ($nextItem) {
        $nStart = $parse($nextItem->tgl_mulai);
        $nDays = (int) now()->startOfDay()->diffInDays($nStart->copy()->startOfDay());

        $next = [
            'activity' => $nextItem->kegiatan?->nama_kegiatan ?? 'Kegiatan Posyandu',
            'when' => $nDays <= 1 ? 'Besok' : $nDays . ' hari lagi',
            'date' => $dayNames[$nStart->dayOfWeekIso] . ', ' . $fmtDate($nStart),
            'time' => $formatJam($nextItem->jam_mulai) . ' – ' . $formatJam($nextItem->jam_selesai) . ' WIB',
            'posyandu' => $nextItem->posyandu?->nama_posyandu ?? 'Posyandu',
            'region' => $nextItem->posyandu?->wilayah?->nama_wilayah ?? 'Wilayah belum tersedia',
            'note' => $nextItem->keterangan,
        ];
    }
@endphp

@push('styles')
<style>
    :root {
        --jade-950: #12372f;
        --jade-900: #173f35;
        --jade-800: #1f5c4d;
        --jade-700: #347967;
        --jade-border: #c4dbcb;
        --jade-text: #244b3b;
        --jade-muted: #678273;

        --st-done: #2e9e5b;
        --st-ongoing: #f59e0b;
        --st-upcoming: #2f80ed;
        --sunday: #d64545;
        --today: #12372f;
    }

    .landing-page {
        min-height: 100%;
        padding-bottom: 35px;
        overflow: hidden;
        background: linear-gradient(180deg, #dcebe0 0%, #edf4ee 50%, #e3eee6 100%);
        color: var(--jade-text);
    }

    .landing-container { width: 100%; max-width: 1320px; margin: 0 auto; padding: 0 24px; }

    /* ---------- Hero ---------- */
    .landing-hero {
        padding: 48px 0;
        background-image: url('{{ asset('storage/backcover2.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .landing-hero-inner {
        max-width: 760px;
        padding: 28px;
        border: 1px solid rgba(255,255,255,.55);
        border-radius: 16px;
        background: rgba(233, 244, 236, .91);
        box-shadow: 0 12px 34px rgba(18,55,47,.12);
        backdrop-filter: blur(3px);
    }

    .landing-hero-logo {
        display: block;
        width: 58px;
        height: 58px;
        margin-bottom: 18px;
        padding: 5px;
        border: 1px solid var(--jade-border);
        border-radius: 12px;
        background: #eaf4ed;
        object-fit: contain;
    }

    .landing-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        padding: 7px 11px;
        border: 1px solid #bdd8c5;
        border-radius: 6px;
        background: #d9eadf;
        color: var(--jade-900);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .7px;
        text-transform: uppercase;
    }

    .landing-hero-title {
        margin: 0 0 14px;
        color: var(--jade-950);
        font-size: clamp(30px, 4vw, 44px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.8px;
    }

    .landing-hero-title span { color: var(--jade-700); }

    .landing-hero-description {
        max-width: 580px;
        margin-bottom: 24px;
        color: #4d6b5b;
        font-size: 13px;
        line-height: 1.9;
    }

    .landing-actions { display: flex; flex-wrap: wrap; gap: 10px; }

    .landing-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 15px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 750;
        text-decoration: none;
        transition: .2s ease;
    }

    .landing-btn-primary { border-color: var(--jade-800); background: var(--jade-800); color: #fff; }
    .landing-btn-primary:hover { background: var(--jade-950); border-color: var(--jade-950); color: #fff; }
    .landing-btn-secondary { border-color: #b6d2bf; background: #e0eee4; color: var(--jade-900); }
    .landing-btn-secondary:hover { background: #cce3d3; color: var(--jade-950); }

    /* ---------- Section ---------- */
    .landing-section { padding-top: 30px; }

    .landing-kicker {
        margin-bottom: 7px;
        color: var(--jade-700);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .landing-title { margin: 0 0 6px; color: var(--jade-950); font-size: 20px; font-weight: 800; }

    .landing-description { margin: 0 0 17px; color: var(--jade-muted); font-size: 12px; line-height: 1.8; }

    /* ---------- Kartu Hari Ini & Kegiatan Berikutnya ---------- */
    .sum-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 22px;
        border: 1px solid #bfd8c7;
        border-top: 4px solid var(--jade-800);
        border-radius: 12px;
        background: linear-gradient(135deg, #e1efe5, #d0e5d7);
        box-shadow: 0 4px 14px rgba(18,55,47,.07);
    }

    .sum-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }

    .sum-head { display: flex; align-items: center; gap: 10px; }

    .sum-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--jade-800);
        color: #fff;
        font-size: 15px;
    }

    .sum-label { color: var(--jade-800); font-size: 11px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase; }

    .sum-badge {
        padding: 5px 12px;
        border-radius: 999px;
        background: var(--jade-800);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .sum-badge.is-muted { background: #bfdcc9; color: var(--jade-900); }

    .sum-title { color: var(--jade-950); font-size: 22px; font-weight: 800; line-height: 1.25; overflow-wrap: anywhere; }

    .sum-sub { margin-top: 4px; color: var(--jade-muted); font-size: 12px; }

    .sum-body { flex: 1; margin-top: 16px; padding-top: 16px; border-top: 1px solid #b6d2bf; }

    .sum-row { display: flex; align-items: flex-start; gap: 12px; }

    .sum-row + .sum-row { margin-top: 12px; }

    .sum-row-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 32px;
        height: 32px;
        border-radius: 8px;
        background: #bfdcc9;
        color: var(--jade-900);
        font-size: 12px;
    }

    .sum-row small { display: block; color: #5f7f6d; font-size: 10.5px; font-weight: 700; line-height: 1.4; }

    .sum-row b { display: block; color: var(--jade-950); font-size: 13px; line-height: 1.4; overflow-wrap: anywhere; }

    .sum-row .sum-muted { display: block; margin-top: 1px; color: var(--jade-muted); font-size: 11px; line-height: 1.5; overflow-wrap: anywhere; }

    /* ---------- Filter ---------- */
    .schedule-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        padding: 13px;
        border: 1px solid #c2d9ca;
        border-radius: 11px;
        background: #dcebe0;
    }

    .schedule-search { position: relative; flex: 1 1 270px; max-width: 460px; }

    .schedule-search > i {
        position: absolute;
        top: 50%;
        left: 13px;
        color: var(--jade-700);
        transform: translateY(-50%);
        pointer-events: none;
    }

    .schedule-search input,
    .schedule-month-filter {
        min-height: 41px;
        border: 1px solid #bed6c6;
        border-radius: 7px;
        background: #f1f7f2;
        color: var(--jade-950);
        font-size: 12px;
    }

    .schedule-search input { padding-left: 37px; }

    .schedule-search input:focus,
    .schedule-month-filter:focus {
        border-color: var(--jade-700);
        box-shadow: 0 0 0 .15rem rgba(31,92,77,.12);
    }

    .schedule-month-filter { min-width: 165px; }

    /* ---------- Jadwal (Gantt) ---------- */
    .gantt-panel { overflow: hidden; border: 1px solid #bdd6c5; border-radius: 11px; background: #fff; }

    .gantt-scroll { overflow-x: auto; }

    .gantt { min-width: 1200px; }

    .g-month-title {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: var(--jade-900);
        color: #fff;
        font-size: 13px;
        font-weight: 800;
    }

    .g-month + .g-month .g-month-title { border-top: 3px solid #fff; }

    .g-month-count {
        padding: 3px 9px;
        border-radius: 999px;
        background: rgba(255,255,255,.18);
        font-size: 10px;
    }

    .g-row { display: flex; align-items: stretch; border-bottom: 1px solid #e1ebe4; }

    .g-info {
        display: grid;
        grid-template-columns: 200px 120px 130px 170px;
        flex: 0 0 620px;
        align-items: center;
    }

    .g-cell { min-width: 0; padding: 10px 12px; }

    .g-main { color: var(--jade-950); font-size: 12px; font-weight: 800; line-height: 1.4; overflow-wrap: anywhere; }

    .g-main.is-time { white-space: nowrap; font-variant-numeric: tabular-nums; }

    .g-sub { margin-top: 2px; color: #6b8576; font-size: 10.5px; line-height: 1.4; overflow-wrap: anywhere; }

    .g-head { background: #e3eee6; border-bottom: 1px solid #c9dccf; }

    .g-head .g-cell {
        padding-top: 11px;
        padding-bottom: 11px;
        color: var(--jade-800);
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

    .g-days { display: flex; flex: 1; min-width: 580px; }

    .g-day {
        flex: 1;
        padding: 11px 0;
        border-left: 1px solid #cfe0d5;
        color: #355a49;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
    }

    .g-day.is-sunday { background: #fbe4e4; color: #c53030; }
    .g-day.is-today { background: var(--today); color: #fff; }

    .g-track {
        position: relative;
        flex: 1;
        min-width: 580px;
        background-image: linear-gradient(to right, #e3ece6 1px, transparent 1px);
        background-size: calc(100% / var(--cols)) 100%;
    }

    .g-today-col { position: absolute; top: 0; bottom: 0; background: rgba(18,55,47,.08); }

    .is-done { --c: var(--st-done); }
    .is-ongoing { --c: var(--st-ongoing); }
    .is-upcoming { --c: var(--st-upcoming); }

    .g-bar {
        position: absolute;
        top: 50%;
        height: 16px;
        min-width: 8px;
        border-radius: 4px;
        background: var(--c);
        box-shadow: 0 1px 2px rgba(0,0,0,.18);
        transform: translateY(-50%);
    }

    .g-row-item:hover { background: #f3f9f5; }

    .g-pill {
        display: none;
        margin-top: 5px;
        padding: 3px 9px;
        border-radius: 999px;
        background: var(--c);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
    }

    .g-pill.is-ongoing { color: #3a2500; }

    .g-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding: 12px 16px;
        border-top: 1px solid #c9dccf;
        background: #f3f8f4;
        color: var(--jade-950);
        font-size: 11px;
        font-weight: 700;
    }

    .g-legend span { display: inline-flex; align-items: center; gap: 7px; }

    .g-swatch { display: inline-block; width: 18px; height: 10px; border-radius: 3px; background: var(--c); }

    .g-swatch.is-sunday { --c: var(--sunday); }
    .g-swatch.is-today { --c: var(--today); }

    /* ---------- Kosong & info ---------- */
    .schedule-no-results,
    .landing-empty {
        padding: 38px 18px;
        border: 1px dashed #b8d3c0;
        border-radius: 11px;
        background: #dfeee3;
        color: var(--jade-muted);
        font-size: 12px;
        text-align: center;
    }

    .schedule-no-results { display: none; margin-top: 12px; }

    .landing-empty h3 { margin-bottom: 7px; color: var(--jade-950); font-size: 15px; font-weight: 800; }

    .landing-info {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 20px;
        border: 1px solid #bfd8c7;
        border-radius: 11px;
        background: linear-gradient(120deg, #d5e8da, #e1eee4);
    }

    .landing-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 9px;
        background: #c6e1ce;
        color: var(--jade-900);
        font-size: 17px;
    }

    .landing-info .landing-description { margin-bottom: 0; }

    .landing-more { display: flex; justify-content: center; margin-top: 20px; }

    .landing-more a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 8px;
        background: var(--jade-800);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    .landing-more a:hover { background: var(--jade-950); color: #fff; }

    /* ---------- HP ---------- */
    @media (max-width: 767.98px) {
        .landing-container { padding-right: 16px; padding-left: 16px; }
        .landing-hero { padding: 28px 0; }
        .landing-hero-inner { padding: 20px; }
        .landing-section { padding-top: 24px; }
        .sum-card { padding: 18px; }
        .sum-title { font-size: 20px; }
        .schedule-search { flex-basis: 100%; max-width: 100%; }
        .schedule-month-filter { flex: 1; }

        .gantt { min-width: 0; }
        .g-head, .g-track { display: none; }
        .g-info { display: block; flex: 1; padding: 12px 14px; }
        .g-cell { padding: 0; }
        .g-cell + .g-cell { margin-top: 3px; }
        .g-cell[data-label]::before { content: attr(data-label) ': '; color: #6b8576; font-size: 10.5px; }
        .g-cell > div { display: inline; }
        .g-cell .g-sub::before { content: ' · '; }
        .g-cell:first-child .g-sub::before { content: ''; }
        .g-pill { display: inline-block; }
    }

    @media (max-width: 420px) {
        .landing-actions { flex-direction: column; }
        .landing-btn { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="landing-page">

    {{-- Hero --}}
    <section class="landing-hero">
        <div class="landing-container">
            <div class="landing-hero-inner">

             

                <div class="landing-eyebrow">
                    <i class="fas fa-heart-pulse"></i>
                    Informasi Jadwal Posyandu
                </div>

                <p class="landing-hero-description">
                    Temukan jadwal pelayanan Posyandu berdasarkan tanggal,
                    lokasi, dan jenis kegiatan.
                </p>

              
            </div>
        </div>
    </section>

    <div class="landing-container">

        {{-- Hari Ini & Kegiatan Berikutnya --}}
        <section class="landing-section">
            <div class="row g-3">

                <div class="col-md-6">
                    <div class="sum-card">

                        <div class="sum-top">
                            <div class="sum-head">
                                <span class="sum-icon"><i class="fas fa-calendar-day"></i></span>
                                <span class="sum-label">Hari Ini</span>
                            </div>

                            @if($todayItems->isNotEmpty())
                                <span class="sum-badge">{{ $todayItems->count() }} kegiatan</span>
                            @else
                                <span class="sum-badge is-muted">Tidak ada kegiatan</span>
                            @endif
                        </div>

                        <div class="sum-title">{{ $dayNames[now()->dayOfWeekIso] }}</div>
                        <div class="sum-sub">{{ now()->day }} {{ $monthNames[now()->month] }} {{ now()->year }}</div>

                        <div class="sum-body">
                            @forelse($todayItems->take(3) as $item)
                                <div class="sum-row">
                                    <span class="sum-row-icon"><i class="fas fa-clock"></i></span>
                                    <div>
                                        <small>{{ $formatJam($item->jam_mulai) }} – {{ $formatJam($item->jam_selesai) }} WIB</small>
                                        <b>{{ $item->kegiatan?->nama_kegiatan ?? 'Kegiatan Posyandu' }}</b>
                                        <span class="sum-muted">{{ $placeOf($item) }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="sum-row">
                                    <span class="sum-row-icon"><i class="fas fa-circle-info"></i></span>
                                    <div>
                                        <small>Informasi</small>
                                        <b>Tidak ada kegiatan Posyandu hari ini.</b>
                                        @if($next)
                                            <span class="sum-muted">Jadwal terdekat: {{ $next['when'] }} · {{ $next['date'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforelse

                            @if($todayItems->count() > 3)
                                <div class="sum-row">
                                    <span class="sum-row-icon"><i class="fas fa-ellipsis"></i></span>
                                    <div>
                                        <small>Lainnya</small>
                                        <b>+{{ $todayItems->count() - 3 }} kegiatan lagi hari ini</b>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="sum-card">

                        <div class="sum-top">
                            <div class="sum-head">
                                <span class="sum-icon"><i class="fas fa-forward"></i></span>
                                <span class="sum-label">Kegiatan Mendatang</span>
                            </div>

                            @if($next)
                                <span class="sum-badge">{{ $next['when'] }}</span>
                            @else
                                <span class="sum-badge is-muted">Belum ada</span>
                            @endif
                        </div>

                        @if($next)
                            <div class="sum-title">{{ $next['activity'] }}</div>
                            <div class="sum-sub">{{ $next['date'] }}</div>

                            <div class="sum-body">
                                <div class="sum-row">
                                    <span class="sum-row-icon"><i class="fas fa-clock"></i></span>
                                    <div>
                                        <small>Waktu</small>
                                        <b>{{ $next['time'] }}</b>
                                    </div>
                                </div>

                                <div class="sum-row">
                                    <span class="sum-row-icon"><i class="fas fa-location-dot"></i></span>
                                    <div>
                                        <small>Tempat</small>
                                        <b>{{ $next['posyandu'] }}</b>
                                        <span class="sum-muted">{{ $next['region'] }}</span>
                                    </div>
                                </div>

                                @if(!empty($next['note']))
                                    <div class="sum-row">
                                        <span class="sum-row-icon"><i class="fas fa-note-sticky"></i></span>
                                        <div>
                                            <small>Keterangan</small>
                                            <b>{{ $next['note'] }}</b>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="sum-title">Belum ada jadwal</div>
                            <div class="sum-sub">Belum ada kegiatan berikutnya yang tersedia.</div>

                            <div class="sum-body">
                                <div class="sum-row">
                                    <span class="sum-row-icon"><i class="fas fa-circle-info"></i></span>
                                    <div>
                                        <small>Informasi</small>
                                        <b>Jadwal baru akan tampil setelah disetujui pengelola.</b>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </section>

        {{-- Jadwal --}}
        <section class="landing-section" id="jadwal-terbaru">

            <div class="landing-kicker">
                <i class="fas fa-calendar-week me-1"></i>
                Kalender Pelayanan
            </div>

            <h2 class="landing-title">Jadwal Posyandu {{ $selectedYear }}</h2>

            <p class="landing-description">
                Setiap batang menunjukkan tanggal pelayanan di bulan tersebut.
                Cari nama Posyandu, kegiatan, atau wilayah, atau pilih satu bulan.
            </p>

            @if($groups->isEmpty())

                <div class="landing-empty">
                    <h3>Belum Ada Jadwal Tersedia</h3>
                    Jadwal yang telah disetujui pengelola akan muncul di halaman ini setelah tersedia.
                </div>

            @else

                <div class="schedule-toolbar">
                    <div class="schedule-search">
                        <i class="fas fa-search"></i>
                        <input
                            type="search"
                            id="scheduleSearch"
                            class="form-control"
                            placeholder="Cari Posyandu, kegiatan, atau wilayah..."
                            aria-label="Cari jadwal"
                        >
                    </div>

                    <select id="scheduleMonthFilter" class="form-select schedule-month-filter" aria-label="Filter bulan">
                        <option value="">Semua Bulan</option>
                        @foreach($groups as $group)
                            <option value="{{ $group['number'] }}">{{ $group['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="gantt-panel">
                    <div class="gantt-scroll">
                        <div class="gantt">

                            @foreach($groups as $group)

                                @php
                                    $isCurrentMonth = $selectedYear === now()->year && $group['number'] === now()->month;
                                @endphp

                                <div class="g-month" data-month="{{ $group['number'] }}" style="--cols: {{ $group['days'] }}">

                                    <div class="g-month-title">
                                        {{ $group['name'] }} {{ $selectedYear }}
                                        <span class="g-month-count">{{ $group['details']->count() }} kegiatan</span>
                                    </div>

                                    <div class="g-row g-head">
                                        <div class="g-info">
                                            <div class="g-cell">Kegiatan</div>
                                            <div class="g-cell">Hari</div>
                                            <div class="g-cell">Waktu</div>
                                            <div class="g-cell">Tempat</div>
                                        </div>

                                        <div class="g-days">
                                            @for($day = 1; $day <= $group['days']; $day++)
                                                @php $date = \Illuminate\Support\Carbon::create($selectedYear, $group['number'], $day); @endphp
                                                <div class="g-day {{ $date->isSunday() ? 'is-sunday' : '' }} {{ $date->toDateString() === $todayDate ? 'is-today' : '' }}">{{ $day }}</div>
                                            @endfor
                                        </div>
                                    </div>

                                    @foreach($group['details'] as $detail)

                                        @php
                                            $posyandu = $detail->posyandu;
                                            $wilayah = $posyandu?->wilayah;

                                            $start = $parse($detail->tgl_mulai);
                                            $end = $detail->tgl_selesai ? $parse($detail->tgl_selesai) : $start->copy();

                                            $multiDay = $end->toDateString() !== $start->toDateString();
                                            $endDay = $end->month === $start->month ? $end->day : $group['days'];

                                            $left = ($start->day - 1) / $group['days'] * 100;
                                            $width = ($endDay - $start->day + 1) / $group['days'] * 100;

                                            $status = $end->toDateString() < $todayDate
                                                ? 'done'
                                                : ($start->toDateString() > $todayDate ? 'upcoming' : 'ongoing');

                                            $activity = $detail->kegiatan?->nama_kegiatan ?? 'Kegiatan Posyandu';
                                            $posyanduName = $posyandu?->nama_posyandu ?? 'Posyandu';
                                            $region = $wilayah?->nama_wilayah ?? 'Wilayah belum tersedia';
                                            $rw = $wilayah?->rw;

                                            // Jangan tampilkan RW dua kali kalau nama wilayah sudah memuatnya.
                                            $showRw = $rw && !\Illuminate\Support\Str::contains(
                                                \Illuminate\Support\Str::lower($region),
                                                \Illuminate\Support\Str::lower((string) $rw)
                                            );

                                            $time = $formatJam($detail->jam_mulai) . ' – ' . $formatJam($detail->jam_selesai);

                                            $dayText = $multiDay
                                                ? $dayNames[$start->dayOfWeekIso] . ' – ' . $dayNames[$end->dayOfWeekIso]
                                                : $dayNames[$start->dayOfWeekIso];

                                            $dateText = $multiDay
                                                ? $start->format('d') . '–' . $fmtDate($end)
                                                : $fmtDate($start);

                                            $search = \Illuminate\Support\Str::lower(
                                                $activity . ' ' . $posyanduName . ' ' . $region . ' ' . ($detail->keterangan ?? '')
                                            );
                                        @endphp

                                        <div class="g-row g-row-item is-{{ $status }}" data-search="{{ $search }}"
                                             title="{{ $statusLabels[$status] }} · {{ $activity }} · {{ $dayText }}, {{ $dateText }} · {{ $time }} WIB · {{ $posyanduName }}">

                                            <div class="g-info">
                                                <div class="g-cell" data-label="Kegiatan">
                                                    <div class="g-main">{{ $activity }}</div>
                                                    @if(!empty($detail->keterangan))
                                                        <div class="g-sub">{{ $detail->keterangan }}</div>
                                                    @endif
                                                    <span class="g-pill is-{{ $status }}">{{ $statusLabels[$status] }}</span>
                                                </div>

                                                <div class="g-cell" data-label="Hari">
                                                    <div class="g-main">{{ $dayText }}</div>
                                                    <div class="g-sub">{{ $dateText }}</div>
                                                </div>

                                                <div class="g-cell" data-label="Waktu">
                                                    <div class="g-main is-time">{{ $time }}</div>
                                                    <div class="g-sub">WIB</div>
                                                </div>

                                                <div class="g-cell" data-label="Tempat">
                                                    <div class="g-main">{{ $posyanduName }}</div>
                                                    <div class="g-sub">{{ $region }}@if($showRw) · RW {{ $rw }}@endif</div>
                                                </div>
                                            </div>

                                            <div class="g-track">
                                                @if($isCurrentMonth)
                                                    <span class="g-today-col" style="left: {{ (now()->day - 1) / $group['days'] * 100 }}%; width: {{ 100 / $group['days'] }}%"></span>
                                                @endif

                                                <div class="g-bar" style="left: {{ $left }}%; width: {{ $width }}%"></div>
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                            @endforeach

                        </div>
                    </div>

                    <div class="g-legend">
                        <span><i class="g-swatch is-done"></i>Selesai</span>
                        <span><i class="g-swatch is-ongoing"></i>Berlangsung</span>
                        <span><i class="g-swatch is-upcoming"></i>Akan datang</span>
                        <span><i class="g-swatch is-sunday"></i>Hari Minggu</span>
                        <span><i class="g-swatch is-today"></i>Hari ini</span>
                    </div>
                </div>

                <div class="schedule-no-results" id="scheduleNoResults">
                    <i class="fas fa-magnifying-glass me-1"></i>
                    Tidak ada jadwal yang sesuai dengan pencarian atau filter.
                </div>

            @endif

           
        </section>

        {{-- Informasi --}}
        <section class="landing-section">
            <div class="landing-info">
                <div class="landing-info-icon"><i class="fas fa-circle-info"></i></div>

                <div>
                    <h2 class="landing-title" style="font-size:15px">Informasi Jadwal</h2>

                    <p class="landing-description">
                        Jadwal pada halaman ini merupakan informasi pelayanan
                        yang telah dirilis. Periksa kembali tanggal,
                        waktu, dan wilayah Posyandu sebelum berkunjung.
                    </p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const months = document.querySelectorAll('.g-month');
    const searchInput = document.getElementById('scheduleSearch');
    const monthFilter = document.getElementById('scheduleMonthFilter');
    const noResults = document.getElementById('scheduleNoResults');

    if (!months.length) {
        return;
    }

    function applyFilters() {
        const keyword = searchInput.value.toLowerCase().trim();
        const selected = monthFilter.value;
        let total = 0;

        months.forEach(function (month) {
            let shown = 0;

            month.querySelectorAll('.g-row-item').forEach(function (row) {
                const visible = row.dataset.search.includes(keyword)
                    && (!selected || month.dataset.month === selected);

                row.style.display = visible ? '' : 'none';

                if (visible) {
                    shown++;
                }
            });

            month.style.display = shown ? '' : 'none';
            total += shown;
        });

        noResults.style.display = total ? 'none' : 'block';
    }

    searchInput.addEventListener('input', applyFilters);
    monthFilter.addEventListener('change', applyFilters);
});
</script>
@endpush