@extends('layouts.app')

@section('title', 'Tambah Posyandu')

@push('styles')
<style>
    .posyandu-create-page .page-card {
        border-radius: 12px;
        border: 1px solid #edf0f0;
    }

    .posyandu-create-page .page-card-header {
        padding: 18px 20px;
    }

    .posyandu-create-page .page-title-small {
        color: #344767;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .posyandu-create-page .page-description {
        color: #8898aa;
        font-size: 12px;
        margin-bottom: 0;
    }

    .posyandu-create-page .btn-jade {
        background: #174a43;
        border-color: #174a43;
        color: #ffffff;
    }

    .posyandu-create-page .btn-jade:hover,
    .posyandu-create-page .btn-jade:focus {
        background: #123d37;
        border-color: #123d37;
        color: #ffffff;
    }

    .posyandu-create-page .form-label {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .posyandu-create-page .form-control,
    .posyandu-create-page .form-select {
        border-color: #dfe5e7;
        font-size: 13px;
        border-radius: 7px;
    }

    .posyandu-create-page .form-control:focus,
    .posyandu-create-page .form-select:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 0.15rem rgba(33, 102, 92, 0.10);
    }

    .posyandu-create-page .form-text {
        color: #8898aa;
        font-size: 11px;
    }

    .posyandu-create-page .form-check-input:checked {
        background-color: #174a43;
        border-color: #174a43;
    }

    .posyandu-create-page .info-box {
        background: #f7faf9;
        border: 1px solid #e5eeec;
        border-radius: 8px;
        padding: 12px 14px;
    }

    .posyandu-create-page .info-box-title {
        color: #174a43;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .posyandu-create-page .info-box-text {
        color: #8898aa;
        font-size: 11px;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')

<div class="posyandu-create-page">

    <div class="row">

        <div class="col-lg-8 col-xl-7">

            <div class="card page-card">

                <div class="page-card-header">

                    <div class="page-title-small">
                        Tambah Posyandu
                    </div>

                    <p class="page-description">
                        Lengkapi informasi Posyandu dengan benar.
                    </p>

                </div>

                <div class="border-top"></div>

                <div class="card-body p-4">

                    <form
                        method="POST"
                        action="{{ route('posyandu.store') }}"
                        class="confirm-submit"
                        data-action="create"
                        data-message="Data Posyandu akan disimpan ke dalam sistem."
                    >

                        @csrf

                        {{-- Wilayah --}}
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
                                        {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}
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


                        {{-- Kode dan Nama Posyandu --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label
                                    for="kode_posyandu"
                                    class="form-label"
                                >
                                    Kode Posyandu
                                </label>

                                <input
                                    type="text"
                                    id="kode_posyandu"
                                    class="form-control bg-light"
                                    value="{{ $kodeBerikutnya }}"
                                    readonly
                                >

                                <div class="form-text">
                                    Kode dibuat otomatis oleh sistem.
                                </div>

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
                                    value="{{ old('nama_posyandu') }}"
                                    placeholder="Contoh: Posyandu Melati"
                                    required
                                >

                                @error('nama_posyandu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Alamat --}}
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
                            >{{ old('alamat') }}</textarea>

                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Ketua dan Kontak --}}
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
                                    value="{{ old('ketua') }}"
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
                                    value="{{ old('kontak') }}"
                                    placeholder="Contoh: 081234567890"
                                >

                                @error('kontak')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Status Posyandu --}}
                        <div class="info-box mb-4">

                            <div class="info-box-title">
                                Status Posyandu
                            </div>

                            <p class="info-box-text">
                                Posyandu aktif dapat digunakan dalam pengaturan
                                operasional dan penjadwalan.
                            </p>

                            <div class="form-check form-switch mt-3">

                                <input
                                    type="checkbox"
                                    name="aktif"
                                    value="1"
                                    id="aktif"
                                    class="form-check-input"
                                    {{ old('aktif', true) ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="aktif"
                                >
                                    Aktif
                                </label>

                            </div>

                        </div>


                        {{-- Tombol --}}
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
                                Simpan Posyandu
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection