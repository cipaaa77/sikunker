@extends('layouts.app')

@section('title', 'Detail Kegiatan')

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

    .detail-value-muted {
        color: #344767;
        font-size: 13px;
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

    .table thead th {
        white-space: nowrap;
    }

    .table tbody td {
        white-space: nowrap;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 767px) {

        .page-card-header {
            padding: 15px;
        }

        .detail-item {
            padding: 11px 0;
        }

    }

</style>

@endpush

@section('content')

<div class="row">

    <div class="col-lg-8 col-xl-7">

        <div class="card page-card mb-4">

            <div class="page-card-header">

                <div class="d-flex justify-content-between align-items-center gap-3">

                    <div>

                        <div class="page-title-small">
                            Detail Kegiatan
                        </div>

                        <p class="page-description">
                            Informasi lengkap data kegiatan.
                        </p>

                    </div>

                    @if($kegiatan->aktif)

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
                        <i class="fas fa-list-check"></i>
                    </div>

                    <div>

                        <div
                            class="fw-semibold"
                            style="
                                color: #344767;
                                font-size: 16px;
                            "
                        >
                            {{ $kegiatan->nama_kegiatan }}
                        </div>

                        <div
                            class="text-muted"
                            style="font-size: 11px;"
                        >
                            {{ $kegiatan->kode_kegiatan }}
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Kode Kegiatan
                            </div>

                            <div class="detail-value">
                                {{ $kegiatan->kode_kegiatan }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Nama Kegiatan
                            </div>

                            <div class="detail-value">
                                {{ $kegiatan->nama_kegiatan }}
                            </div>

                        </div>

                    </div>

                    <div class="col-12">

                        <div class="detail-item">

                            <div class="detail-label">
                                Deskripsi
                            </div>

                            <div class="detail-value-muted">
                                {{ $kegiatan->deskripsi ?: '-' }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Status
                            </div>

                            <div>

                                @if($kegiatan->aktif)

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

                    </div>

                    <div class="col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Digunakan Dalam Jadwal
                            </div>

                            <div class="detail-value">
                                {{ $kegiatan->jadwalDetails->count() }}
                                jadwal
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-box-title">
                                Dibuat
                            </div>

                            <p class="info-box-text">
                                {{ $kegiatan->created_at?->format('d M Y H:i') ?: '-' }}
                            </p>

                        </div>

                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">

                        <div class="info-box">

                            <div class="info-box-title">
                                Terakhir Diperbarui
                            </div>

                            <p class="info-box-text">
                                {{ $kegiatan->updated_at?->format('d M Y H:i') ?: '-' }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white px-4 py-3 border-top">

                <div class="d-flex justify-content-between align-items-center">

                    <a
                        href="{{ route('kegiatan.index') }}"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <div>

                        <a
                            href="{{ route('kegiatan.edit', $kegiatan) }}"
                            class="btn btn-outline-jade btn-sm"
                        >
                            <i class="fas fa-pen me-1"></i>
                            Edit
                        </a>

                        @if(!$kegiatan->jadwalDetails->count())

                            <form
                                action="{{ route('kegiatan.destroy', $kegiatan) }}"
                                method="POST"
                                class="d-inline delete-form"
                                data-name="{{ $kegiatan->nama_kegiatan }}"
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

                        @endif

                    </div>

                </div>

            </div>

        </div>

        @if($kegiatan->jadwalDetails->count())

            <div class="card page-card">

                <div class="page-card-header">

                    <div class="page-title-small">
                        Penggunaan Kegiatan
                    </div>

                    <p class="page-description">
                        Daftar jadwal yang menggunakan kegiatan ini.
                    </p>

                </div>

                <div class="border-top"></div>

                <div class="table-responsive">

                    <table class="table table-hover align-items-center mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4">
                                    No
                                </th>

                                <th>
                                    Posyandu
                                </th>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($kegiatan->jadwalDetails as $detail)

                                <tr>

                                    <td class="ps-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <div class="fw-semibold text-dark">
                                            {{ $detail->posyandu->nama_posyandu }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $detail->posyandu->kode_posyandu }}
                                        </small>

                                    </td>

                                    <td>

                                        {{ $detail->tgl_mulai->format('d M Y') }}

                                        @if($detail->tgl_selesai != $detail->tgl_mulai)

                                            -
                                            {{ $detail->tgl_selesai->format('d M Y') }}

                                        @endif

                                    </td>

                                    <td>

                                        @if($detail->status === 'terjadwal')

                                            <span class="badge badge-active">
                                                Terjadwal
                                            </span>

                                        @elseif($detail->status === 'selesai')

                                            <span class="badge bg-light text-dark">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="badge badge-inactive">
                                                Dibatalkan
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection