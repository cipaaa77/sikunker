@extends('layouts.app')

@section('title', 'Tambah Wilayah')
@section('breadcrumb', 'Wilayah / Tambah')
@section('page-title', 'Tambah Wilayah')

@push('styles')
<style>
    .form-card {
        border-radius: 12px;
    }

    .form-section-title {
        color: #174a43;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .form-section-description {
        color: #8898aa;
        font-size: 12px;
        margin-bottom: 22px;
    }

    .form-label {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .required {
        color: #dc3545;
    }

    .form-control {
        min-height: 40px;
        font-size: 13px;
    }

    textarea.form-control {
        min-height: 90px;
    }

    .form-check-label {
        color: #525f7f;
        font-size: 13px;
    }

    .form-hint {
        color: #8898aa;
        font-size: 11px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8 col-xl-7">
        <div class="card form-card">
            <div class="card-body p-4">

                <div class="mb-4">
                    <div class="form-section-title">
                        Informasi Wilayah
                    </div>

                    <p class="form-section-description">
                        Masukkan informasi wilayah yang akan digunakan untuk mengelompokkan Posyandu.
                    </p>
                </div>

                <form method="POST" action="{{ route('wilayah.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_wilayah" class="form-label">
                            Nama Wilayah <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_wilayah"
                            id="nama_wilayah"
                            class="form-control @error('nama_wilayah') is-invalid @enderror"
                            value="{{ old('nama_wilayah') }}"
                            placeholder="Contoh: RW 01"
                            required
                        >

                        @error('nama_wilayah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rw" class="form-label">
                                RW
                            </label>

                            <input
                                type="text"
                                name="rw"
                                id="rw"
                                class="form-control @error('rw') is-invalid @enderror"
                                value="{{ old('rw') }}"
                                placeholder="Contoh: 01"
                            >

                            @error('rw')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kelurahan" class="form-label">
                                Kelurahan
                            </label>

                            <input
                                type="text"
                                name="kelurahan"
                                id="kelurahan"
                                class="form-control @error('kelurahan') is-invalid @enderror"
                                value="{{ old('kelurahan') }}"
                                placeholder="Nama kelurahan"
                            >

                            @error('kelurahan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">
                            Kecamatan
                        </label>

                        <input
                            type="text"
                            name="kecamatan"
                            id="kecamatan"
                            class="form-control @error('kecamatan') is-invalid @enderror"
                            value="{{ old('kecamatan') }}"
                            placeholder="Nama kecamatan"
                        >

                        @error('kecamatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            id="alamat"
                            rows="3"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Alamat lengkap wilayah"
                        >{{ old('alamat') }}</textarea>

                        @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input
                                type="checkbox"
                                name="aktif"
                                value="1"
                                class="form-check-input"
                                id="aktif"
                                {{ old('aktif', true) ? 'checked' : '' }}
                            >

                            <label class="form-check-label" for="aktif">
                                Wilayah Aktif
                            </label>
                        </div>

                        <div class="form-hint mt-1">
                            Wilayah aktif dapat digunakan dalam data Posyandu.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <a
                            href="{{ route('wilayah.index') }}"
                            class="btn btn-light btn-sm"
                        >
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary btn-sm"
                        >
                            <i class="fas fa-save me-1"></i>
                            Simpan Wilayah
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection