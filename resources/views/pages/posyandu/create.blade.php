@extends('layouts.app')

@section('title', 'Tambah Posyandu')

@section('content')

<div class="page-header">

</div>

<div class="row">

    <div class="col-lg-8 col-xl-7">

        <div class="card">

            <div class="card-header px-4 py-3">

                <h5
                    class="mb-1 fw-semibold"
                    style="color: var(--jade-dark);"
                >
                    Form Posyandu
                </h5>

                <p class="mb-0 small text-muted">
                    Lengkapi informasi Posyandu dengan benar.
                </p>

            </div>

            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('posyandu.store') }}"
                    class="confirm-submit"
                    data-action="create"
                    data-message="Data Posyandu akan disimpan ke dalam sistem."
                >

                    @csrf

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
                                value="{{ old('kode_posyandu') }}"
                                placeholder="Contoh: POS-001"
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

                    <div class="mb-4">

                        <div class="form-check form-switch">

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
                                <span class="fw-semibold">
                                    Posyandu Aktif
                                </span>

                                <small class="d-block text-muted mt-1">
                                    Posyandu dapat digunakan dalam penjadwalan.
                                </small>
                            </label>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">

                        <a
                            href="{{ route('posyandu.index') }}"
                            class="btn btn-light border"
                        >
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="btn btn-jade"
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

@endsection
