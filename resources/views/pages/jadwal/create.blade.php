@extends('layouts.app')

@section('title', 'Buat Jadwal Bulanan')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('jadwal.index') }}"
           class="text-decoration-none text-muted">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Kembali
        </a>

        <h4 class="fw-bold mt-3 mb-1">
            Buat Jadwal Bulanan
        </h4>

        <p class="text-muted mb-0">
            Tentukan periode jadwal sebelum mengatur detail kegiatan.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-lg-8 col-xl-7">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Bulan
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="bulan"
                                class="form-select @error('bulan') is-invalid @enderror"
                                required
                            >
                                <option value="">Pilih bulan</option>

                                @for($bulan = 1; $bulan <= 12; $bulan++)

                                    <option
                                        value="{{ $bulan }}"
                                        {{ old('bulan') == $bulan ? 'selected' : '' }}
                                    >
                                        {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                                    </option>

                                @endfor

                            </select>

                            @error('bulan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Tahun
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="tahun"
                                class="form-control @error('tahun') is-invalid @enderror"
                                value="{{ old('tahun', now()->year) }}"
                                min="2020"
                                max="2100"
                                required
                            >

                            @error('tahun')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Catatan
                            </label>

                            <textarea
                                name="catatan"
                                rows="4"
                                class="form-control @error('catatan') is-invalid @enderror"
                                placeholder="Catatan tambahan untuk jadwal..."
                            >{{ old('catatan') }}</textarea>

                            @error('catatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('jadwal.index') }}"
                                class="btn btn-light border"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-jade"
                            >
                                <i class="fa-solid fa-arrow-right me-1"></i>
                                Lanjut Atur Jadwal
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
</style>
@endpush