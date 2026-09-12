@extends('layouts.app')

@section('title', 'Tambah Jadwal Bulanan')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-calendar-plus me-2"></i>
                Tambah Jadwal Bulanan
            </h4>

            <p class="text-muted mb-0">
                Buat jadwal kegiatan Posyandu.
            </p>
        </div>

        <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>
                <i class="bi bi-exclamation-triangle me-1"></i>
                Terdapat kesalahan:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Utama --}}
    <form
        id="jadwalForm"
        action="{{ route('jadwal.store') }}"
        method="POST"
    >
        @csrf

        {{-- Informasi Jadwal --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>
                    Informasi Jadwal
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="periode" class="form-label fw-semibold">
                            Periode Jadwal
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="month"
                            id="periode"
                            name="periode"
                            class="form-control @error('periode') is-invalid @enderror"
                            value="{{ old('periode', now()->format('Y-m')) }}"
                            required
                        >

                        @error('periode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="catatan" class="form-label fw-semibold">
                            Catatan
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="3"
                            class="form-control @error('catatan') is-invalid @enderror"
                            placeholder="Masukkan catatan jika diperlukan..."
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Detail Jadwal --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Detail Jadwal
                    </h5>

                    <button
                        type="button"
                        id="btnTambahDetail"
                        class="btn btn-light btn-sm"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Detail
                    </button>
                </div>
            </div>

            <div class="card-body">

                <div id="detailContainer"></div>

                <div
                    id="emptyState"
                    class="alert alert-info text-center d-none"
                >
                    Belum ada detail jadwal.
                </div>

            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-end gap-2">
            <a
                href="{{ route('jadwal.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-x-circle me-1"></i>
                Batal
            </a>

            <button
                type="submit"
                id="btnSimpan"
                class="btn btn-primary"
            >
                <i class="bi bi-save me-1"></i>
                Simpan Jadwal
            </button>
        </div>

    </form>
</div>
@endsection

@push('styles')
<style>
    .detail-card {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        background-color: #f8f9fa;
        padding: 16px;
        margin-bottom: 16px;
    }

    .detail-title {
        font-weight: 600;
        margin-bottom: 16px;
    }

    .required {
        color: #dc3545;
    }
</style>
@endpush

@php
    /*
    |--------------------------------------------------------------------------
    | Konversi Data PHP Menjadi JSON
    |--------------------------------------------------------------------------
    */

    $posyanduJson = collect($posyandus ?? [])->values()->toJson();

    $kegiatanJson = collect($kegiatans ?? [])->values()->toJson();

    $oldDetailsJson = collect(old('details', []))->values()->toJson();
@endphp

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | Data Master
        |--------------------------------------------------------------------------
        */

        const posyanduMaster = {!! $posyanduJson !!};

        const kegiatanMaster = {!! $kegiatanJson !!};

        const oldDetails = {!! $oldDetailsJson !!};

        /*
        |--------------------------------------------------------------------------
        | Element
        |--------------------------------------------------------------------------
        */

        const form = document.getElementById('jadwalForm');

        const detailContainer =
            document.getElementById('detailContainer');

        const emptyState =
            document.getElementById('emptyState');

        const btnTambahDetail =
            document.getElementById('btnTambahDetail');

        const btnSimpan =
            document.getElementById('btnSimpan');

        let detailIndex = 0;

        /*
        |--------------------------------------------------------------------------
        | Helper Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Nama Posyandu
        |--------------------------------------------------------------------------
        */

        function getNamaPosyandu(posyandu) {
            return (
                posyandu.nama_posyandu ??
                posyandu.nama ??
                posyandu.name ??
                ''
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Nama Kegiatan
        |--------------------------------------------------------------------------
        */

        function getNamaKegiatan(kegiatan) {
            return (
                kegiatan.nama_kegiatan ??
                kegiatan.nama ??
                kegiatan.name ??
                ''
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Option Posyandu
        |--------------------------------------------------------------------------
        */

        function generatePosyanduOptions() {
            let html = `
                <option value="">
                    -- Pilih Posyandu --
                </option>
            `;

            posyanduMaster.forEach(function (posyandu) {
                html += `
                    <option value="${posyandu.id}">
                        ${escapeHtml(getNamaPosyandu(posyandu))}
                    </option>
                `;
            });

            return html;
        }

        /*
        |--------------------------------------------------------------------------
        | Option Kegiatan
        |--------------------------------------------------------------------------
        */

        function generateKegiatanOptions() {
            let html = `
                <option value="">
                    -- Pilih Kegiatan --
                </option>
            `;

            kegiatanMaster.forEach(function (kegiatan) {
                html += `
                    <option value="${kegiatan.id}">
                        ${escapeHtml(getNamaKegiatan(kegiatan))}
                    </option>
                `;
            });

            return html;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Empty State
        |--------------------------------------------------------------------------
        */

        function updateEmptyState() {
            const jumlahDetail =
                detailContainer.querySelectorAll('.detail-card').length;

            if (jumlahDetail === 0) {
                emptyState.classList.remove('d-none');
            } else {
                emptyState.classList.add('d-none');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Nomor Detail
        |--------------------------------------------------------------------------
        */

        function updateDetailNumber() {
            const cards =
                detailContainer.querySelectorAll('.detail-card');

            cards.forEach(function (card, index) {
                const title =
                    card.querySelector('.detail-title');

                if (title) {
                    title.textContent =
                        'Detail Jadwal #' + (index + 1);
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Tambah Detail
        |--------------------------------------------------------------------------
        */

        function tambahDetail(data = {}) {
            const index = detailIndex++;

            const card = document.createElement('div');

            card.className = 'detail-card';

            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="detail-title mb-0">
                        Detail Jadwal #${index + 1}
                    </h6>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger btn-remove-detail"
                    >
                        <i class="bi bi-trash me-1"></i>
                        Hapus
                    </button>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Posyandu
                            <span class="required">*</span>
                        </label>

                        <select
                            name="details[${index}][posyandu_id]"
                            class="form-select"
                            required
                        >
                            ${generatePosyanduOptions()}
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Kegiatan
                            <span class="required">*</span>
                        </label>

                        <select
                            name="details[${index}][kegiatan_id]"
                            class="form-select"
                            required
                        >
                            ${generateKegiatanOptions()}
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Mulai
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_mulai]"
                            class="form-control tgl-mulai"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Selesai
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_selesai]"
                            class="form-control tgl-selesai"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jam Mulai
                            <span class="required">*</span>
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_mulai]"
                            class="form-control jam-mulai"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jam Selesai
                            <span class="required">*</span>
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_selesai]"
                            class="form-control jam-selesai"
                            required
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Keterangan
                        </label>

                        <textarea
                            name="details[${index}][keterangan]"
                            class="form-control"
                            rows="2"
                            placeholder="Masukkan keterangan..."
                        ></textarea>
                    </div>

                </div>
            `;

            detailContainer.appendChild(card);

            const posyanduSelect = card.querySelector(
                `select[name="details[${index}][posyandu_id]"]`
            );

            const kegiatanSelect = card.querySelector(
                `select[name="details[${index}][kegiatan_id]"]`
            );

            const tglMulai = card.querySelector('.tgl-mulai');

            const tglSelesai = card.querySelector('.tgl-selesai');

            const jamMulai = card.querySelector('.jam-mulai');

            const jamSelesai = card.querySelector('.jam-selesai');

            const keterangan = card.querySelector('textarea');

            posyanduSelect.value =
                data.posyandu_id ?? '';

            kegiatanSelect.value =
                data.kegiatan_id ?? '';

            tglMulai.value =
                data.tgl_mulai ?? '';

            tglSelesai.value =
                data.tgl_selesai ?? '';

            jamMulai.value =
                data.jam_mulai ?? '';

            jamSelesai.value =
                data.jam_selesai ?? '';

            keterangan.value =
                data.keterangan ?? '';

            updateEmptyState();

            updateDetailNumber();
        }

        /*
        |--------------------------------------------------------------------------
        | Tambah Detail
        |--------------------------------------------------------------------------
        */

        btnTambahDetail.addEventListener('click', function () {
            tambahDetail();
        });

        /*
        |--------------------------------------------------------------------------
        | Hapus Detail
        |--------------------------------------------------------------------------
        */

        detailContainer.addEventListener('click', function (event) {
            const button =
                event.target.closest('.btn-remove-detail');

            if (!button) {
                return;
            }

            const card =
                button.closest('.detail-card');

            if (card) {
                card.remove();
            }

            updateEmptyState();

            updateDetailNumber();
        });

        /*
        |--------------------------------------------------------------------------
        | Validasi Tanggal
        |--------------------------------------------------------------------------
        */

        detailContainer.addEventListener('change', function (event) {
            const card =
                event.target.closest('.detail-card');

            if (!card) {
                return;
            }

            const tglMulai =
                card.querySelector('.tgl-mulai');

            const tglSelesai =
                card.querySelector('.tgl-selesai');

            if (
                tglMulai.value &&
                tglSelesai.value &&
                tglSelesai.value < tglMulai.value
            ) {
                tglSelesai.setCustomValidity(
                    'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.'
                );
            } else {
                tglSelesai.setCustomValidity('');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Validasi Jam
        |--------------------------------------------------------------------------
        */

        detailContainer.addEventListener('change', function (event) {
            const card =
                event.target.closest('.detail-card');

            if (!card) {
                return;
            }

            const jamMulai =
                card.querySelector('.jam-mulai');

            const jamSelesai =
                card.querySelector('.jam-selesai');

            if (
                jamMulai.value &&
                jamSelesai.value &&
                jamSelesai.value <= jamMulai.value
            ) {
                jamSelesai.setCustomValidity(
                    'Jam selesai harus lebih besar dari jam mulai.'
                );
            } else {
                jamSelesai.setCustomValidity('');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Submit Form
        |--------------------------------------------------------------------------
        */

        form.addEventListener('submit', function (event) {
            const jumlahDetail =
                detailContainer.querySelectorAll('.detail-card').length;

            if (jumlahDetail === 0) {
                event.preventDefault();

                alert('Minimal harus ada satu detail jadwal.');

                return;
            }

            btnSimpan.disabled = true;

            btnSimpan.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span>
                Menyimpan...
            `;
        });

        /*
        |--------------------------------------------------------------------------
        | Isi Old Input
        |--------------------------------------------------------------------------
        */

        if (
            Array.isArray(oldDetails) &&
            oldDetails.length > 0
        ) {
            oldDetails.forEach(function (detail) {
                tambahDetail(detail);
            });
        } else {
            tambahDetail();
        }

    });
</script>
@endpush