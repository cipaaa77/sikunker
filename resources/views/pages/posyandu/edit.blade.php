@extends('layouts.app')

@section('title', 'Edit Posyandu')

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

    .form-label {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-color: #dfe5e7;
        font-size: 13px;
        border-radius: 7px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 .15rem rgba(33, 102, 92, .10);
    }

    .form-text {
        color: #8898aa;
        font-size: 11px;
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
                    Edit Posyandu
                </div>

                <p class="page-description">
                    Perbarui data Posyandu yang tersimpan dalam sistem.
                </p>

            </div>

            <div class="border-top"></div>

            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('posyandu.update', $posyandu) }}"
                    class="confirm-submit"
                    data-action="update"
                    data-message="Perubahan data Posyandu akan disimpan ke dalam sistem."
                >

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label
                            for="wilayah_id"
                            class="form-label"
                        >
                            Wilayah
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="wilayah_id"
                            id="wilayah_id"
                            class="form-select @error('wilayah_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Pilih wilayah
                            </option>

                            @foreach($wilayahs as $wilayah)

                                <option
                                    value="{{ $wilayah->id }}"
                                    {{ old('wilayah_id', $posyandu->wilayah_id) == $wilayah->id ? 'selected' : '' }}
                                >
                                    {{ $wilayah->nama_wilayah }}

                                    @if($wilayah->rw)
                                        - {{ $wilayah->rw }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('wilayah_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="kode_posyandu"
                                class="form-label"
                            >
                                Kode Posyandu
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="kode_posyandu"
                                id="kode_posyandu"
                                class="form-control @error('kode_posyandu') is-invalid @enderror"
                                value="{{ old('kode_posyandu', $posyandu->kode_posyandu) }}"
                                required
                            >

                            @error('kode_posyandu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="nama_posyandu"
                                class="form-label"
                            >
                                Nama Posyandu
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_posyandu"
                                id="nama_posyandu"
                                class="form-control @error('nama_posyandu') is-invalid @enderror"
                                value="{{ old('nama_posyandu', $posyandu->nama_posyandu) }}"
                                required
                            >

                            @error('nama_posyandu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <div class="mb-3">

                        <label
                            for="alamat"
                            class="form-label"
                        >
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            id="alamat"
                            rows="4"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat Posyandu"
                        >{{ old('alamat', $posyandu->alamat) }}</textarea>

                        @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="ketua"
                                class="form-label"
                            >
                                Ketua Posyandu
                            </label>

                            <input
                                type="text"
                                name="ketua"
                                id="ketua"
                                class="form-control @error('ketua') is-invalid @enderror"
                                value="{{ old('ketua', $posyandu->ketua) }}"
                                placeholder="Nama ketua Posyandu"
                            >

                            @error('ketua')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="kontak"
                                class="form-label"
                            >
                                Kontak
                            </label>

                            <input
                                type="text"
                                name="kontak"
                                id="kontak"
                                class="form-control @error('kontak') is-invalid @enderror"
                                value="{{ old('kontak', $posyandu->kontak) }}"
                                placeholder="Contoh: 081234567890"
                            >

                            @error('kontak')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <div class="info-box mb-4">

                        <div class="info-box-title">
                            Status Posyandu
                        </div>

                        <p class="info-box-text">
                            Posyandu yang aktif dapat digunakan dalam pengaturan
                            operasional dan penjadwalan.
                        </p>

                        <div class="form-check form-switch mt-3">

                            <input
                                type="checkbox"
                                name="aktif"
                                value="1"
                                id="aktif"
                                class="form-check-input"
                                {{ old('aktif', $posyandu->aktif) ? 'checked' : '' }}
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
                            href="{{ route('posyandu.index') }}"
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
                            Perbarui Posyandu
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection