@extends('layouts.app')

@section('title', 'Detail Hari Operasional')

@push('styles')

<style>

    .page-card {
        border-radius: 12px;
        border: 1px solid #edf0f0;
    }

    .page-card-header {
        padding: 18px 20px;
    }

    .page-title-small {
        color: #344767;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .page-description {
        color: #8898aa;
        font-size: 12px;
        margin-bottom: 0;
    }

    .btn-jade {
        background: #174a43;
        border-color: #174a43;
        color: #fff;
    }

    .btn-jade:hover {
        background: #123d37;
        border-color: #123d37;
        color: #fff;
    }

    .btn-outline-jade {
        border: 1px solid #21665c;
        color: #21665c;
        background: #fff;
    }

    .btn-outline-jade:hover {
        background: #e8f2f0;
        color: #174a43;
    }

    .detail-item {
        padding: 13px 0;
        border-bottom: 1px solid #edf0f0;
    }

    .detail-item:last-child {
        border-bottom: 0;
    }

    .detail-label {
        color: #8898aa;
        font-size: 11px;
        margin-bottom: 4px;
    }

    .detail-value {
        color: #344767;
        font-size: 13px;
        font-weight: 600;
    }

    .detail-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #e8f2f0;
        color: #174a43;
        font-size: 13px;
        flex-shrink: 0;
    }

    .badge-active {
        background: #e8f2f0;
        color: #174a43;
        font-weight: 600;
    }

    .badge-inactive {
        background: #f1f3f5;
        color: #6c757d;
        font-weight: 600;
    }

    .info-box {
        background: #f7faf9;
        border: 1px solid #e5eeec;
        border-radius: 8px;
        padding: 14px;
    }

    .info-box-title {
        color: #174a43;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-box-text {
        color: #8898aa;
        font-size: 11px;
        margin-bottom: 0;
    }

</style>

@endpush

@section('content')

<div class="row">

    <div class="col-lg-8 col-xl-7">

        <div class="card page-card">

            <div class="page-card-header">

                <div class="d-flex justify-content-between align-items-center gap-3">

                    <div>

                        <div class="page-title-small">
                            Detail Hari Operasional
                        </div>

                        <p class="page-description">
                            Informasi lengkap hari dan jam operasional.
                        </p>

                    </div>

                    @if($hariOperasional->aktif)

                        <span class="badge badge-active">
                            Aktif
                        </span>

                    @else

                        <span class="badge badge-inactive">
                            Tidak Aktif
                        </span>

                    @endif

                </div>

            </div>

            <div class="border-top"></div>

            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-3 mb-4">

                    <div class="detail-icon">

                        <i class="fas fa-calendar-days"></i>

                    </div>

                    <div>

                        <div
                            class="fw-semibold"
                            style="
                                color: #344767;
                                font-size: 16px;
                            "
                        >
                            {{ $hariOperasional->posyandu->nama_posyandu }}
                        </div>

                        <div
                            class="text-muted"
                            style="font-size: 11px;"
                        >
                            {{ $hariOperasional->posyandu->kode_posyandu }}
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Posyandu
                            </div>

                            <div class="detail-value">
                                {{ $hariOperasional->posyandu->nama_posyandu }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Kode Posyandu
                            </div>

                            <div class="detail-value">
                                {{ $hariOperasional->posyandu->kode_posyandu }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Hari Operasional
                            </div>

                            <div class="detail-value">
                                {{ $hariOperasional->nama_hari }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Status
                            </div>

                            @if($hariOperasional->aktif)

                                <span class="badge badge-active">
                                    Aktif
                                </span>

                            @else

                                <span class="badge badge-inactive">
                                    Tidak Aktif
                                </span>

                            @endif

                        </div>

                    </div>

                    <div class="col-12">

                        <div class="detail-item">

                            <div class="detail-label">
                                Jam Operasional
                            </div>

                            <div class="detail-value">

                                {{ \Carbon\Carbon::parse($hariOperasional->jam_mulai)->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse($hariOperasional->jam_selesai)->format('H:i') }}

                                WIB

                            </div>

                        </div>

                    </div>

                </div>

                <div class="info-box mt-3">

                    <div class="info-box-title">
                        Acuan Penjadwalan
                    </div>

                    <p class="info-box-text">
                        Data hari dan jam operasional ini akan menjadi
                        salah satu acuan sistem ketika melakukan penyusunan
                        jadwal kegiatan Posyandu.
                    </p>

                </div>

            </div>

            <div class="card-footer bg-white px-4 py-3 border-top">

                <div class="d-flex justify-content-between align-items-center">

                    <a
                        href="{{ route('hari-operasional.index') }}"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <div>

                        <a
                            href="{{ route('hari-operasional.edit', $hariOperasional) }}"
                            class="btn btn-outline-jade btn-sm"
                        >
                            <i class="fas fa-pen me-1"></i>
                            Edit
                        </a>

                        <form
                            action="{{ route('hari-operasional.destroy', $hariOperasional) }}"
                            method="POST"
                            class="d-inline delete-form"
                            data-name="{{ $hariOperasional->posyandu->nama_posyandu . ' - ' . $hariOperasional->nama_hari }}"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-outline-danger btn-sm"
                            >
                                <i class="fas fa-trash me-1"></i>
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection