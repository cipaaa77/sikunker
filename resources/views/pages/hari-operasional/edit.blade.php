@extends('layouts.app')

@section('title', 'Edit Hari Operasional')

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

    .form-check-input:checked {
        background-color: #174a43;
        border-color: #174a43;
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
                    Edit Hari Operasional
                </div>

                <p class="page-description">
                    Perbarui hari dan jam operasional Posyandu.
                </p>

            </div>

            <div class="border-top"></div>

            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('hari-operasional.update', $hariOperasional) }}"
                    class="confirm-submit"
                    data-action="update"
                    data-message="Perubahan hari operasional akan disimpan ke dalam sistem."
                >

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label
                            for="posyandu_id"
                            class="form-label"
                        >
                            Posyandu
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="posyandu_id"
                            id="posyandu_id"
                            class="form-select @error('posyandu_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Pilih Posyandu
                            </option>

                            @foreach($posyandus as $posyandu)

                                <option
                                    value="{{ $posyandu->id }}"
                                    {{ old('posyandu_id', $hariOperasional->posyandu_id) == $posyandu->id ? 'selected' : '' }}
                                >
                                    {{ $posyandu->nama_posyandu }}
                                    - {{ $posyandu->kode_posyandu }}
                                </option>

                            @endforeach

                        </select>

                        @error('posyandu_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="hari"
                            class="form-label"
                        >
                            Hari Operasional
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="hari"
                            id="hari"
                            class="form-select @error('hari') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Pilih hari
                            </option>

                            @foreach([
                                1 => 'Senin',
                                2 => 'Selasa',
                                3 => 'Rabu',
                                4 => 'Kamis',
                                5 => 'Jumat',
                                6 => 'Sabtu',
                                7 => 'Minggu'
                            ] as $value => $nama)

                                <option
                                    value="{{ $value }}"
                                    {{ old('hari', $hariOperasional->hari) == $value ? 'selected' : '' }}
                                >
                                    {{ $nama }}
                                </option>

                            @endforeach

                        </select>

                        @error('hari')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="jam_mulai"
                                class="form-label"
                            >
                                Jam Mulai
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                name="jam_mulai"
                                id="jam_mulai"
                                class="form-control @error('jam_mulai') is-invalid @enderror"
                                value="{{ old('jam_mulai', $hariOperasional->jam_mulai ? substr($hariOperasional->jam_mulai, 0, 5) : '') }}"
                                required
                            >

                            @error('jam_mulai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="jam_selesai"
                                class="form-label"
                            >
                                Jam Selesai
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                name="jam_selesai"
                                id="jam_selesai"
                                class="form-control @error('jam_selesai') is-invalid @enderror"
                                value="{{ old('jam_selesai', $hariOperasional->jam_selesai ? substr($hariOperasional->jam_selesai, 0, 5) : '') }}"
                                required
                            >

                            @error('jam_selesai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <div class="info-box mb-4">

                        <div class="info-box-title">
                            Status Operasional
                        </div>

                        <p class="info-box-text">
                            Hari operasional aktif dapat digunakan sebagai acuan
                            pembuatan jadwal Posyandu.
                        </p>

                        <div class="form-check form-switch mt-3">

                            <input
                                type="checkbox"
                                name="aktif"
                                value="1"
                                id="aktif"
                                class="form-check-input"
                                {{ old('aktif', $hariOperasional->aktif) ? 'checked' : '' }}
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
                            href="{{ route('hari-operasional.index') }}"
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
                            Perbarui Hari Operasional
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection