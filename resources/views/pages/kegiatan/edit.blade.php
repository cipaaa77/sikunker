@extends('layouts.app')

@section('title', 'Edit Kegiatan')

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

    .form-label {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control {
        border-color: #dfe5e7;
        font-size: 13px;
        border-radius: 7px;
    }

    .form-control:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 .15rem rgba(33, 102, 92, .10);
    }

    .form-check-input:checked {
        background-color: #174a43;
        border-color: #174a43;
    }

    .form-check-input:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 .15rem rgba(33, 102, 92, .10);
    }

    .info-box {
        background: #f7faf9;
        border: 1px solid #e5eeec;
        border-radius: 8px;
        padding: 12px 14px;
    }

    .info-box-title {
        color: #174a43;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 3px;
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

                <div class="page-title-small">
                    Edit Kegiatan
                </div>

                <p class="page-description">
                    Perbarui data kegiatan yang tersimpan dalam sistem.
                </p>

            </div>

            <div class="border-top"></div>

            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('kegiatan.update', $kegiatan) }}"
                    class="confirm-submit"
                    data-action="update"
                    data-message="Perubahan data kegiatan akan disimpan ke dalam sistem."
                >

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="kode_kegiatan"
                                class="form-label"
                            >
                                Kode Kegiatan
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="kode_kegiatan"
                                id="kode_kegiatan"
                                class="form-control @error('kode_kegiatan') is-invalid @enderror"
                                value="{{ old('kode_kegiatan', $kegiatan->kode_kegiatan) }}"
                                required
                            >

                            @error('kode_kegiatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="nama_kegiatan"
                                class="form-label"
                            >
                                Nama Kegiatan
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_kegiatan"
                                id="nama_kegiatan"
                                class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                                required
                            >

                            @error('nama_kegiatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <div class="mb-4">

                        <label
                            for="deskripsi"
                            class="form-label"
                        >
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            rows="5"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Masukkan deskripsi kegiatan"
                        >{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>

                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="info-box mb-4">

                        <div class="info-box-title">
                            Status Kegiatan
                        </div>

                        <p class="info-box-text">
                            Kegiatan aktif dapat dipilih ketika membuat jadwal Posyandu.
                        </p>

                        <div class="form-check form-switch mt-3">

                            <input
                                type="checkbox"
                                name="aktif"
                                value="1"
                                id="aktif"
                                class="form-check-input"
                                {{ old('aktif', $kegiatan->aktif) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="aktif"
                            >
                                Aktif
                            </label>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">

                        <a
                            href="{{ route('kegiatan.index') }}"
                            class="btn btn-outline-secondary btn-sm"
                        >
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="btn btn-jade btn-sm"
                        >
                            <i class="fas fa-save me-1"></i>
                            Perbarui Kegiatan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection