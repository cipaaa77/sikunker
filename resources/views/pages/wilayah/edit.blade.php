@extends('layouts.app')

@section('title', 'Edit Wilayah')

@section('content')

<div class="page-header">

   

    <h1 class="page-title">
        Edit Wilayah
    </h1>

    <p class="page-description">
        Perbarui informasi wilayah yang digunakan dalam sistem Posyandu.
    </p>

</div>

<div class="row">

    <div class="col-lg-8 col-xl-7">

        <div class="card">

            <div class="card-header px-4 py-3">

                <h5 class="mb-1 fw-semibold" style="color: var(--jade-dark);">
                    Form Edit Wilayah
                </h5>

                <p class="mb-0 small text-muted">
                    Periksa kembali data sebelum menyimpan perubahan.
                </p>

            </div>

            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('wilayah.update', $wilayah) }}"
                    class="confirm-submit"
                    data-action="update"
                    data-message="Perubahan data wilayah akan disimpan ke dalam sistem."
                >

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label for="nama_wilayah" class="form-label">
                            Nama Wilayah
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_wilayah"
                            id="nama_wilayah"
                            class="form-control @error('nama_wilayah') is-invalid @enderror"
                            value="{{ old('nama_wilayah', $wilayah->nama_wilayah) }}"
                            placeholder="Masukkan nama wilayah"
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
                                value="{{ old('rw', $wilayah->rw) }}"
                                placeholder="Contoh: RW 01"
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
                                value="{{ old('kelurahan', $wilayah->kelurahan) }}"
                                placeholder="Masukkan kelurahan"
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
                            value="{{ old('kecamatan', $wilayah->kecamatan) }}"
                            placeholder="Masukkan kecamatan"
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
                            rows="4"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat wilayah"
                        >{{ old('alamat', $wilayah->alamat) }}</textarea>

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
                                id="aktif"
                                class="form-check-input"
                                {{ old('aktif', $wilayah->aktif) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="aktif"
                            >
                                <span class="fw-semibold">
                                    Wilayah Aktif
                                </span>

                                <small class="d-block text-muted mt-1">
                                    Wilayah dapat digunakan dalam data Posyandu.
                                </small>
                            </label>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">

                        <a
                            href="{{ route('wilayah.index') }}"
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
                            Perbarui Wilayah
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection