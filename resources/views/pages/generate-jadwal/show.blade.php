@extends('layouts.app')

@section('title', 'Detail Generate Jadwal')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>

            <a
                href="{{ route('generate-jadwal.index') }}"
                class="text-decoration-none text-muted"
            >
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali
            </a>

            <h4 class="fw-bold mt-3 mb-1">
                Detail Jadwal
            </h4>

            <p class="text-muted mb-0">

                Periode

                <strong>
                    {{ \Carbon\Carbon::create()
                        ->month($jadwal->bulan)
                        ->translatedFormat('F') }}
                    {{ $jadwal->tahun }}
                </strong>

            </p>

        </div>


        <div class="d-flex gap-2">

            @if(in_array($jadwal->status, ['draft', 'ditolak']))

                <a
                    href="{{ route('jadwal.edit', $jadwal) }}"
                    class="btn btn-jade"
                >
                    <i class="fa-solid fa-pen me-1"></i>
                    Atur Jadwal
                </a>

            @endif

            <a
                href="{{ route('jadwal.show', $jadwal) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fa-solid fa-eye me-1"></i>
                Detail Transaksi
            </a>

        </div>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="fa-solid fa-calendar"></i>
                </div>

                <div>
                    <small>Total Kegiatan</small>
                    <strong>
                        {{ $jadwal->details->count() }}
                    </strong>
                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <div>
                    <small>Terjadwal</small>
                    <strong>
                        {{ $jadwal->details->where('status', 'terjadwal')->count() }}
                    </strong>
                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <small>Selesai</small>
                    <strong>
                        {{ $jadwal->details->where('status', 'selesai')->count() }}
                    </strong>
                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="fa-solid fa-house"></i>
                </div>

                <div>
                    <small>Posyandu</small>
                    <strong>
                        {{ $groupedDetails->count() }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    @forelse($groupedDetails as $posyanduId => $details)

        @php
            $posyandu = $details->first()->posyandu;
        @endphp

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 p-4">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="small text-muted mb-1">
                            POSYANDU
                        </div>

                        <h5 class="fw-bold mb-1">
                            {{ $posyandu->nama_posyandu }}
                        </h5>

                        <div class="text-muted small">

                            <i class="fa-solid fa-location-dot me-1"></i>

                            {{ $posyandu->wilayah->nama_wilayah ?? '-' }}

                        </div>

                    </div>


                    <span class="schedule-count">
                        {{ $details->count() }} kegiatan
                    </span>

                </div>

            </div>


            <div class="card-body px-4 pt-0">

                <div class="row g-3">

                    @foreach($details as $detail)

                        <div class="col-xl-4 col-lg-6">

                            <div class="detail-card">

                                <div class="detail-card-top">

                                    <div>

                                        <div class="detail-date">

                                            {{ $detail->tgl_mulai
                                                ? $detail->tgl_mulai->translatedFormat('d F Y')
                                                : '-' }}

                                        </div>

                                        <div class="detail-time">

                                            <i class="fa-regular fa-clock me-1"></i>

                                            {{ $detail->jam_mulai
                                                ? \Carbon\Carbon::parse($detail->jam_mulai)->format('H:i')
                                                : '--:--' }}

                                            -

                                            {{ $detail->jam_selesai
                                                ? \Carbon\Carbon::parse($detail->jam_selesai)->format('H:i')
                                                : '--:--' }}

                                        </div>

                                    </div>


                                    @if($detail->status === 'terjadwal')

                                        <span class="badge text-bg-primary">
                                            Terjadwal
                                        </span>

                                    @elseif($detail->status === 'selesai')

                                        <span class="badge text-bg-success">
                                            Selesai
                                        </span>

                                    @else

                                        <span class="badge text-bg-danger">
                                            Dibatalkan
                                        </span>

                                    @endif

                                </div>


                                <div class="detail-card-body">

                                    <div class="small text-muted mb-1">
                                        KEGIATAN
                                    </div>

                                    <div class="fw-bold mb-2">
                                        {{ $detail->kegiatan->nama_kegiatan }}
                                    </div>


                                    <div class="d-flex gap-2">

                                        <span class="type-chip">
                                            {{ $detail->tipe_kegiatan }}
                                        </span>

                                        @if($detail->keterangan)

                                            <span
                                                class="text-muted small text-truncate"
                                            >
                                                {{ $detail->keterangan }}
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @empty

        <div class="empty-state">

            <i class="fa-regular fa-calendar-xmark fa-2x mb-3"></i>

            <h6 class="fw-bold">
                Belum ada detail jadwal
            </h6>

            <p class="text-muted">
                Belum ada kegiatan yang berhasil digenerate.
            </p>

        </div>

    @endforelse

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

    .summary-card {
        background: #fff;
        border: 1px solid #e8eeee;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--jade-light);
        color: var(--jade);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .summary-card small {
        display: block;
        color: #7d8987;
        margin-bottom: 2px;
    }

    .summary-card strong {
        font-size: 20px;
        color: var(--jade-dark);
    }

    .schedule-count {
        background: var(--jade-light);
        color: var(--jade-dark);
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .detail-card {
        border: 1px solid #e5eceb;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        height: 100%;
    }

    .detail-card-top {
        padding: 14px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        border-bottom: 1px solid #edf1f1;
    }

    .detail-date {
        font-weight: 700;
        color: var(--jade-dark);
    }

    .detail-time {
        color: #687673;
        font-size: 13px;
        margin-top: 4px;
    }

    .detail-card-body {
        padding: 14px;
    }

    .type-chip {
        display: inline-block;
        background: var(--jade-light);
        color: var(--jade-dark);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
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