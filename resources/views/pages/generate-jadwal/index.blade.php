@extends('layouts.app')

@section('title', 'Generate Jadwal')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Generate Jadwal
            </h4>

            <p class="text-muted mb-0">
                Buat jadwal otomatis berdasarkan hari operasional Posyandu.
            </p>
        </div>

        <a
            href="{{ route('generate-jadwal.create') }}"
            class="btn btn-jade"
        >
            <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
            Generate Baru
        </a>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

                <div>
                    <div class="text-muted small">
                        Total Jadwal
                    </div>

                    <div class="summary-value">
                        {{ $totalJadwal }}
                    </div>
                </div>
            </div>

        </div>


        <div class="col-md-4">

            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>

                <div>
                    <div class="text-muted small">
                        Total Detail
                    </div>

                    <div class="summary-value">
                        {{ $totalDetail }}
                    </div>
                </div>
            </div>

        </div>


        <div class="col-md-4">

            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fa-solid fa-house-medical"></i>
                </div>

                <div>
                    <div class="text-muted small">
                        Posyandu Aktif
                    </div>

                    <div class="summary-value">
                        {{ $totalPosyandu }}
                    </div>
                </div>
            </div>

        </div>

    </div>


    <div class="row g-4">

        @forelse($jadwals as $jadwal)

            @php
                $total = $jadwal->details_count;
                $selesai = $jadwal->jumlah_selesai;
                $dibatalkan = $jadwal->jumlah_dibatalkan;
                $terjadwal = $total - $selesai - $dibatalkan;
            @endphp

            <div class="col-xl-4 col-lg-6">

                <div class="schedule-card h-100">

                    <div class="schedule-card-header">

                        <div>

                            <div class="small text-muted">
                                PERIODE
                            </div>

                            <h5 class="fw-bold mb-0">
                                {{ \Carbon\Carbon::create()
                                    ->month($jadwal->bulan)
                                    ->translatedFormat('F') }}
                                {{ $jadwal->tahun }}
                            </h5>

                        </div>


                        @if($jadwal->status === 'draft')

                            <span class="badge text-bg-secondary">
                                Draft
                            </span>

                        @elseif($jadwal->status === 'diajukan')

                            <span class="badge text-bg-warning">
                                Diajukan
                            </span>

                        @elseif($jadwal->status === 'disetujui')

                            <span class="badge text-bg-success">
                                Disetujui
                            </span>

                        @elseif($jadwal->status === 'ditolak')

                            <span class="badge text-bg-danger">
                                Ditolak
                            </span>

                        @else

                            <span class="badge bg-jade">
                                Final
                            </span>

                        @endif

                    </div>


                    <div class="schedule-card-body">

                        <div class="row g-2 mb-3">

                            <div class="col-4">
                                <div class="stat-box">
                                    <strong>{{ $total }}</strong>
                                    <small>Total</small>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="stat-box">
                                    <strong>{{ $terjadwal }}</strong>
                                    <small>Terjadwal</small>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="stat-box">
                                    <strong>{{ $selesai }}</strong>
                                    <small>Selesai</small>
                                </div>
                            </div>

                        </div>


                        <div class="small text-muted mb-2">
                            Posyandu dalam jadwal
                        </div>

                        <div class="d-flex flex-wrap gap-2">

                            @foreach(
                                $jadwal->details
                                    ->pluck('posyandu.nama_posyandu')
                                    ->unique()
                                    ->take(5)
                                as $namaPosyandu
                            )

                                <span class="posyandu-chip">
                                    {{ $namaPosyandu }}
                                </span>

                            @endforeach

                        </div>

                    </div>


                    <div class="schedule-card-footer">

                        <a
                            href="{{ route('generate-jadwal.show', $jadwal) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="fa-solid fa-eye me-1"></i>
                            Detail
                        </a>


                        @if(in_array($jadwal->status, ['draft', 'ditolak']))

                            <a
                                href="{{ route('jadwal.edit', $jadwal) }}"
                                class="btn btn-sm btn-jade"
                            >
                                <i class="fa-solid fa-pen me-1"></i>
                                Atur
                            </a>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="empty-state">

                    <i class="fa-regular fa-calendar-xmark fa-2x mb-3"></i>

                    <h6 class="fw-bold">
                        Belum ada jadwal
                    </h6>

                    <p class="text-muted mb-3">
                        Buat jadwal bulanan terlebih dahulu.
                    </p>

                    <a
                        href="{{ route('jadwal.create') }}"
                        class="btn btn-jade"
                    >
                        Buat Jadwal Bulanan
                    </a>

                </div>

            </div>

        @endforelse

    </div>


    @if($jadwals->hasPages())

        <div class="mt-4">
            {{ $jadwals->links() }}
        </div>

    @endif

</div>

@endsection


@push('styles')
<style>

    .btn-jade {
        background: var(--jade);
        border-color: var(--jade);
        color: #fff;
    }

    .btn-jade:hover {
        background: var(--jade-dark);
        border-color: var(--jade-dark);
        color: #fff;
    }

    .bg-jade {
        background: var(--jade) !important;
        color: #fff;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e8eeee;
        border-radius: 14px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--jade-light);
        color: var(--jade);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .summary-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--jade-dark);
    }

    .schedule-card {
        background: #fff;
        border: 1px solid #e7eeee;
        border-radius: 14px;
        overflow: hidden;
        transition: .2s ease;
    }

    .schedule-card:hover {
        transform: translateY(-2px);
        border-color: #cfdedb;
    }

    .schedule-card-header {
        padding: 18px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #edf1f1;
    }

    .schedule-card-body {
        padding: 18px;
    }

    .schedule-card-footer {
        padding: 14px 18px;
        border-top: 1px solid #edf1f1;
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .stat-box {
        background: #f7fafa;
        border-radius: 9px;
        padding: 10px 5px;
        text-align: center;
    }

    .stat-box strong {
        display: block;
        color: var(--jade-dark);
        font-size: 18px;
    }

    .stat-box small {
        color: #7a8785;
        font-size: 11px;
    }

    .posyandu-chip {
        background: var(--jade-light);
        color: var(--jade-dark);
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .empty-state {
        background: #fff;
        border: 1px dashed #ccd9d7;
        border-radius: 14px;
        padding: 60px 20px;
        text-align: center;
        color: #879390;
    }

</style>
@endpush