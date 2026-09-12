@extends('layouts.app')

@section('title', 'Generate Jadwal Baru')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">

        <a
            href="{{ route('generate-jadwal.index') }}"
            class="text-decoration-none text-muted"
        >
            <i class="fa-solid fa-arrow-left me-1"></i>
            Kembali
        </a>

        <h4 class="fw-bold mt-3 mb-1">
            Generate Jadwal Baru
        </h4>

        <p class="text-muted mb-0">
            Sistem akan membuat jadwal berdasarkan hari operasional aktif.
        </p>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h6 class="fw-bold mb-1">
                Pengaturan Generate
            </h6>

            <p class="text-muted small mb-0">
                Tentukan periode dan pola kegiatan yang akan digunakan.
            </p>

        </div>


        <div class="card-body px-4">

            <form
                action="{{ route('generate-jadwal.store') }}"
                method="POST"
                class="confirm-submit"
                data-title="Generate jadwal?"
                data-text="Sistem akan membuat jadwal berdasarkan hari operasional."
                data-confirm="Ya, Generate"
            >

                @csrf

                <div class="row">

                    <div class="col-lg-8 col-xl-7">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Bulan
                            </label>

                            <select
                                name="bulan"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Pilih Bulan
                                </option>

                                @foreach(range(1, 12) as $bulan)

                                    <option
                                        value="{{ $bulan }}"
                                        {{ old('bulan', now()->month) == $bulan ? 'selected' : '' }}
                                    >
                                        {{ \Carbon\Carbon::create()
                                            ->month($bulan)
                                            ->translatedFormat('F') }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Tahun
                            </label>

                            <input
                                type="number"
                                name="tahun"
                                class="form-control"
                                value="{{ old('tahun', now()->year) }}"
                                min="2020"
                                max="2100"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Posyandu
                            </label>

                            <select
                                name="posyandu_id"
                                class="form-select"
                            >

                                <option value="">
                                    Semua Posyandu Aktif
                                </option>

                                @foreach($posyandus as $posyandu)

                                    <option
                                        value="{{ $posyandu->id }}"
                                        {{ old('posyandu_id') == $posyandu->id ? 'selected' : '' }}
                                    >
                                        {{ $posyandu->nama_posyandu }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Kosongkan jika ingin generate seluruh Posyandu.
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Kegiatan
                            </label>

                            <select
                                name="kegiatan_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Pilih Kegiatan
                                </option>

                                @foreach($kegiatans as $kegiatan)

                                    <option
                                        value="{{ $kegiatan->id }}"
                                        {{ old('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}
                                    >
                                        {{ $kegiatan->nama_kegiatan }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Kegiatan ini digunakan sebagai kegiatan awal hasil generate.
                                Jadwal masih dapat diedit setelah proses selesai.
                            </div>

                        </div>


                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Tipe Kegiatan
                            </label>

                            <select
                                name="tipe_kegiatan"
                                class="form-select"
                                required
                            >

                                <option
                                    value="DG"
                                    {{ old('tipe_kegiatan', 'DG') === 'DG' ? 'selected' : '' }}
                                >
                                    DG
                                </option>

                                <option
                                    value="LG"
                                    {{ old('tipe_kegiatan') === 'LG' ? 'selected' : '' }}
                                >
                                    LG
                                </option>

                            </select>

                        </div>


                        <div class="generate-info mb-4">

                            <div class="generate-info-icon">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>

                            <div>

                                <strong>
                                    Cara kerja Generate
                                </strong>

                                <p class="mb-0 small text-muted">
                                    Sistem membaca hari operasional aktif setiap Posyandu,
                                    kemudian mencari tanggal yang sesuai dalam periode
                                    yang dipilih dan membuat detail jadwal secara otomatis.
                                </p>

                            </div>

                        </div>


                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('generate-jadwal.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-jade"
                            >
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
                                Generate Jadwal
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

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

    .generate-info {
        display: flex;
        gap: 12px;
        padding: 14px;
        border-radius: 10px;
        background: var(--jade-light);
    }

    .generate-info-icon {
        color: var(--jade);
        font-size: 18px;
    }

</style>
@endpush