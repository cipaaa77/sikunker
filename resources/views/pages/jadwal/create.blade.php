@extends('layouts.app')

@section('title', 'Tambah Jadwal Bulanan')

@push('styles')
<style>
    :root {
        --jade-primary: #174a43;
        --jade-secondary: #21665c;
        --jade-soft: #f4f8f7;
        --jade-border: #e3ece9;
        --text-main: #263238;
        --text-muted: #84919a;
    }

    .jadwal-create-page {
        padding: 24px 0 40px;
    }

    .jadwal-create-page .page-header {
        margin-bottom: 26px;
    }

    .jadwal-create-page .page-title {
        color: var(--text-main);
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .jadwal-create-page .page-description {
        color: var(--text-muted);
        font-size: 13px;
        margin-bottom: 0;
    }

    .jadwal-create-page .page-icon {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e7f2ef;
        color: var(--jade-primary);
        border-radius: 12px;
        font-size: 22px;
    }

    .jadwal-create-page .card {
        border: 1px solid var(--jade-border);
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(23, 74, 67, 0.04);
        overflow: hidden;
    }

    .jadwal-create-page .card-header {
        background: #ffffff;
        border-bottom: 1px solid var(--jade-border);
        padding: 18px 22px;
    }

    .jadwal-create-page .card-header-title {
        color: var(--jade-primary);
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .jadwal-create-page .card-header-description {
        color: var(--text-muted);
        font-size: 12px;
        margin-bottom: 0;
    }

    .jadwal-create-page .card-body {
        padding: 22px;
    }

    .jadwal-create-page .form-label {
        color: #46545b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .jadwal-create-page .form-control,
    .jadwal-create-page .form-select {
        min-height: 42px;
        border: 1px solid #dce6e3;
        border-radius: 9px;
        color: #334047;
        font-size: 13px;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .jadwal-create-page textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .jadwal-create-page .form-control:focus,
    .jadwal-create-page .form-select:focus {
        border-color: var(--jade-secondary);
        box-shadow: 0 0 0 3px rgba(33, 102, 92, 0.09);
    }

    .jadwal-create-page .form-text {
        color: var(--text-muted);
        font-size: 11px;
        margin-top: 6px;
    }

    .jadwal-create-page .status-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 15px;
        background: #f7faf9;
        border: 1px solid #e2eeea;
        border-radius: 10px;
        height: 100%;
    }

    .jadwal-create-page .status-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #fff3cd;
        color: #946c00;
        flex-shrink: 0;
    }

    .jadwal-create-page .status-label {
        color: var(--text-muted);
        font-size: 11px;
        margin-bottom: 2px;
    }

    .jadwal-create-page .status-value {
        color: #795b00;
        font-size: 13px;
        font-weight: 700;
    }

    .jadwal-create-page .info-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #eef7f4;
        border: 1px solid #d8ebe5;
        color: #35675d;
        border-radius: 10px;
        padding: 13px 15px;
        font-size: 12px;
        line-height: 1.6;
    }

    .jadwal-create-page .info-alert i {
        font-size: 17px;
        margin-top: 1px;
    }

    .jadwal-create-page .detail-card {
        background: #ffffff;
        border: 1px solid #e2ebe8;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .jadwal-create-page .detail-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 17px;
        background: #f8fbfa;
        border-bottom: 1px solid #e6efec;
    }

    .jadwal-create-page .detail-number {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--jade-primary);
        color: #ffffff;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .jadwal-create-page .detail-title {
        color: var(--jade-primary);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .jadwal-create-page .detail-subtitle {
        color: var(--text-muted);
        font-size: 11px;
        margin-bottom: 0;
    }

    .jadwal-create-page .detail-card-body {
        padding: 18px;
    }

    .jadwal-create-page .btn {
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 15px;
    }

    .jadwal-create-page .btn-jade {
        background: var(--jade-primary);
        border-color: var(--jade-primary);
        color: #ffffff;
    }

    .jadwal-create-page .btn-jade:hover {
        background: #103b35;
        border-color: #103b35;
        color: #ffffff;
    }

    .jadwal-create-page .btn-outline-jade {
        background: #ffffff;
        border: 1px solid #bcd6cf;
        color: var(--jade-primary);
    }

    .jadwal-create-page .btn-outline-jade:hover {
        background: #edf6f3;
        border-color: var(--jade-secondary);
        color: var(--jade-primary);
    }

    .jadwal-create-page .btn-remove-detail {
        color: #b33a3a;
        background: #fff5f5;
        border: 1px solid #f1d7d7;
        padding: 6px 10px;
        font-size: 11px;
    }

    .jadwal-create-page .btn-remove-detail:hover {
        background: #ffe8e8;
        color: #982c2c;
    }

    .jadwal-create-page .empty-detail {
        text-align: center;
        padding: 32px 20px;
        background: #fafcfb;
        border: 1px dashed #cbded8;
        border-radius: 12px;
        color: var(--text-muted);
    }

    .jadwal-create-page .empty-detail i {
        display: block;
        color: #a6c3ba;
        font-size: 32px;
        margin-bottom: 10px;
    }

    .jadwal-create-page .empty-detail-title {
        color: #5c7770;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .jadwal-create-page .empty-detail-description {
        font-size: 12px;
        margin-bottom: 0;
    }

    .jadwal-create-page .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 22px;
        margin-top: 22px;
        border-top: 1px solid var(--jade-border);
    }

    .jadwal-create-page .footer-note {
        color: var(--text-muted);
        font-size: 11px;
        margin-bottom: 0;
    }

    .jadwal-create-page .footer-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .jadwal-create-page .invalid-feedback {
        font-size: 11px;
    }

    .jadwal-create-page .required-mark {
        color: #c0392b;
    }

    @media (max-width: 767.98px) {
        .jadwal-create-page {
            padding-top: 16px;
        }

        .jadwal-create-page .page-title {
            font-size: 20px;
        }

        .jadwal-create-page .card-body,
        .jadwal-create-page .card-header {
            padding: 17px;
        }

        .jadwal-create-page .detail-card-body {
            padding: 15px;
        }

        .jadwal-create-page .form-footer {
            align-items: stretch;
        }

        .jadwal-create-page .footer-actions {
            width: 100%;
        }

        .jadwal-create-page .footer-actions .btn {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid jadwal-create-page">

    {{-- Header Halaman --}}
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div class="d-flex align-items-center gap-3">
            <div class="page-icon">
                <i class="bi bi-calendar-plus"></i>
            </div>

            <div>
                <h4 class="page-title">
                    Tambah Jadwal Bulanan
                </h4>

                <p class="page-description">
                    Buat jadwal kegiatan Posyandu sebagai draft.
                </p>
            </div>
        </div>

        <a
            href="{{ route('jadwal.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>

                <div>
                    <strong class="d-block mb-1">
                        Terdapat kesalahan pada data:
                    </strong>

                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Pesan Error Session --}}
    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Pembatasan Akses Admin --}}
    @if (!auth()->check() || auth()->user()->role !== 'admin')

        <div class="card">
            <div class="card-body text-center py-5">
                <i
                    class="bi bi-shield-lock"
                    style="font-size: 42px; color: #b33a3a;"
                ></i>

                <h5 class="mt-3 mb-2">
                    Akses Ditolak
                </h5>

                <p class="text-muted small mb-3">
                    Anda tidak memiliki akses untuk membuat jadwal bulanan.
                </p>

                <a
                    href="{{ route('jadwal.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke Jadwal
                </a>
            </div>
        </div>

    @else

        {{-- Form Utama --}}
        <form
            id="jadwalForm"
            action="{{ route('jadwal.store') }}"
            method="POST"
        >
            @csrf

            {{-- Informasi Jadwal --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h5 class="card-header-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Informasi Jadwal
                    </h5>

                    <p class="card-header-description">
                        Tentukan periode dan catatan umum jadwal.
                    </p>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Periode --}}
                        <div class="col-md-6">

                            <label
                                for="periode"
                                class="form-label"
                            >
                                Periode Jadwal
                                <span class="required-mark">*</span>
                            </label>

                            <input
                                type="month"
                                name="periode"
                                id="periode"
                                class="form-control @error('periode') is-invalid @enderror"
                                value="{{ old('periode', now()->format('Y-m')) }}"
                                required
                            >

                            <div class="form-text">
                                Pilih bulan dan tahun jadwal kegiatan.
                            </div>

                            @error('periode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Status Awal
                            </label>

                            <div class="status-box">

                                <div class="status-icon">
                                    <i class="bi bi-pencil-square"></i>
                                </div>

                                <div>
                                    <div class="status-label">
                                        Status jadwal saat dibuat
                                    </div>

                                    <div class="status-value">
                                        Draft
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Catatan --}}
                        <div class="col-12">

                            <label
                                for="catatan"
                                class="form-label"
                            >
                                Catatan Jadwal
                                <span class="text-muted fw-normal">
                                    (Opsional)
                                </span>
                            </label>

                            <textarea
                                name="catatan"
                                id="catatan"
                                class="form-control @error('catatan') is-invalid @enderror"
                                rows="4"
                                placeholder="Tambahkan catatan umum untuk jadwal bulan ini..."
                            >{{ old('catatan') }}</textarea>

                            @error('catatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Informasi Status --}}
                        <div class="col-12">

                            <div class="info-alert">
                                <i class="bi bi-info-circle-fill"></i>

                                <div>
                                    Jadwal akan disimpan sebagai
                                    <strong>Draft</strong>.
                                    Setelah detail jadwal selesai disusun,
                                    jadwal dapat diajukan untuk proses persetujuan.
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            {{-- Detail Jadwal --}}
            <div class="card mb-4">

                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>
                        <h5 class="card-header-title">
                            <i class="bi bi-list-check me-2"></i>
                            Detail Kegiatan
                        </h5>

                        <p class="card-header-description">
                            Tambahkan Posyandu, kegiatan, tanggal, dan waktu.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="btnTambahDetail"
                        class="btn btn-outline-jade"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Tambah Detail
                    </button>

                </div>

                <div class="card-body">

                    <div
                        id="detailContainer"
                        class="mb-0"
                    ></div>

                    <div
                        id="emptyDetail"
                        class="empty-detail"
                    >
                        <i class="bi bi-calendar2-plus"></i>

                        <div class="empty-detail-title">
                            Belum ada detail jadwal
                        </div>

                        <p class="empty-detail-description">
                            Klik tombol "Tambah Detail" untuk menambahkan kegiatan Posyandu.
                        </p>
                    </div>

                </div>
            </div>

            {{-- Footer Form --}}
            <div class="form-footer">

                <p class="footer-note">
                    <i class="bi bi-info-circle me-1"></i>
                    Pastikan seluruh data sudah benar sebelum disimpan.
                </p>

                <div class="footer-actions">

                    <a
                        href="{{ route('jadwal.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        id="btnSubmit"
                        class="btn btn-jade"
                    >
                        <i class="bi bi-save me-1"></i>
                        Simpan sebagai Draft
                    </button>

                </div>

            </div>

        </form>

    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('jadwalForm');
    const detailContainer = document.getElementById('detailContainer');
    const emptyDetail = document.getElementById('emptyDetail');
    const btnTambahDetail = document.getElementById('btnTambahDetail');
    const btnSubmit = document.getElementById('btnSubmit');
    const periodeInput = document.getElementById('periode');

    if (!form || !detailContainer || !btnTambahDetail) {
        return;
    }

    const posyandus = @json($posyandus ?? []);
    const kegiatans = @json($kegiatans ?? []);
    const oldDetails = @json(old('details', []));

    let detailIndex = 0;

    /*
    |--------------------------------------------------------------------------
    | Helper Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /*
    |--------------------------------------------------------------------------
    | Format Label Posyandu + Wilayah
    |--------------------------------------------------------------------------
    */

    function getWilayahName(posyandu) {
        if (!posyandu || !posyandu.wilayah) {
            return 'Wilayah belum diatur';
        }

        return (
            posyandu.wilayah.nama_wilayah ||
            posyandu.wilayah.nama ||
            posyandu.wilayah.name ||
            'Wilayah belum diatur'
        );
    }

    function getPosyanduLabel(posyandu) {
        const namaPosyandu =
            posyandu.nama_posyandu ||
            posyandu.nama ||
            'Posyandu tanpa nama';

        const namaWilayah = getWilayahName(posyandu);

        return `${namaPosyandu} — ${namaWilayah}`;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Option Posyandu
    |--------------------------------------------------------------------------
    */

    function generatePosyanduOptions(selectedValue = '') {
        let options = `
            <option value="">
                Pilih Posyandu
            </option>
        `;

        posyandus.forEach(function (posyandu) {
            const selected =
                String(selectedValue) === String(posyandu.id)
                    ? 'selected'
                    : '';

            const label = getPosyanduLabel(posyandu);

            options += `
                <option
                    value="${escapeHtml(posyandu.id)}"
                    ${selected}
                >
                    ${escapeHtml(label)}
                </option>
            `;
        });

        return options;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Option Kegiatan
    |--------------------------------------------------------------------------
    */

    function generateKegiatanOptions(selectedValue = '') {
        let options = `
            <option value="">
                Pilih Kegiatan
            </option>
        `;

        kegiatans.forEach(function (kegiatan) {
            const selected =
                String(selectedValue) === String(kegiatan.id)
                    ? 'selected'
                    : '';

            const label =
                kegiatan.nama_kegiatan ||
                kegiatan.nama ||
                'Kegiatan tanpa nama';

            options += `
                <option
                    value="${escapeHtml(kegiatan.id)}"
                    ${selected}
                >
                    ${escapeHtml(label)}
                </option>
            `;
        });

        return options;
    }

    /*
    |--------------------------------------------------------------------------
    | Update Empty State
    |--------------------------------------------------------------------------
    */

    function updateEmptyState() {
        const totalDetail =
            detailContainer.querySelectorAll('.detail-card').length;

        emptyDetail.style.display =
            totalDetail === 0
                ? 'block'
                : 'none';
    }

    /*
    |--------------------------------------------------------------------------
    | Update Nomor Detail
    |--------------------------------------------------------------------------
    */

    function updateDetailNumbers() {
        const detailCards =
            detailContainer.querySelectorAll('.detail-card');

        detailCards.forEach(function (card, index) {
            const numberElement =
                card.querySelector('.detail-number');

            if (numberElement) {
                numberElement.textContent = index + 1;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Tambah Detail
    |--------------------------------------------------------------------------
    */

    function addDetail(data = {}) {
        const index = detailIndex++;

        const detailCard = document.createElement('div');

        detailCard.className = 'detail-card';

        detailCard.innerHTML = `
            <div class="detail-card-header">

                <div class="d-flex align-items-center gap-2">

                    <div class="detail-number">
                        1
                    </div>

                    <div>
                        <div class="detail-title">
                            Detail Kegiatan
                        </div>

                        <p class="detail-subtitle">
                            Informasi kegiatan Posyandu
                        </p>
                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-remove-detail btnHapusDetail"
                >
                    <i class="bi bi-trash3 me-1"></i>
                    Hapus
                </button>

            </div>

            <div class="detail-card-body">

                <div class="row g-3">

                    {{-- Posyandu --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Posyandu
                            <span class="required-mark">*</span>
                        </label>

                        <select
                            name="details[${index}][posyandu_id]"
                            class="form-select"
                            required
                        >
                            ${generatePosyanduOptions(data.posyandu_id || '')}
                        </select>

                        <div class="form-text">
                            Nama Posyandu ditampilkan bersama wilayahnya.
                        </div>

                    </div>

                    {{-- Kegiatan --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Jenis Kegiatan
                            <span class="required-mark">*</span>
                        </label>

                        <select
                            name="details[${index}][kegiatan_id]"
                            class="form-select"
                            required
                        >
                            ${generateKegiatanOptions(data.kegiatan_id || '')}
                        </select>

                    </div>

                    {{-- Tanggal Mulai --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Tanggal Mulai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_mulai]"
                            class="form-control tanggal-mulai"
                            value="${escapeHtml(data.tgl_mulai || '')}"
                            required
                        >

                    </div>

                    {{-- Tanggal Selesai --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Tanggal Selesai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_selesai]"
                            class="form-control tanggal-selesai"
                            value="${escapeHtml(data.tgl_selesai || '')}"
                            required
                        >

                    </div>

                    {{-- Jam Mulai --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Jam Mulai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_mulai]"
                            class="form-control jam-mulai"
                            value="${escapeHtml(data.jam_mulai || '')}"
                            required
                        >

                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Jam Selesai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_selesai]"
                            class="form-control jam-selesai"
                            value="${escapeHtml(data.jam_selesai || '')}"
                            required
                        >

                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">

                        <label class="form-label">
                            Keterangan
                            <span class="text-muted fw-normal">
                                (Opsional)
                            </span>
                        </label>

                        <textarea
                            name="details[${index}][keterangan]"
                            class="form-control"
                            rows="3"
                            placeholder="Tambahkan keterangan kegiatan..."
                        >${escapeHtml(data.keterangan || '')}</textarea>

                    </div>

                </div>

            </div>
        `;

        detailContainer.appendChild(detailCard);

        const btnHapus =
            detailCard.querySelector('.btnHapusDetail');

        btnHapus.addEventListener('click', function () {
            detailCard.remove();

            updateDetailNumbers();
            updateEmptyState();
        });

        const tanggalMulai =
            detailCard.querySelector('.tanggal-mulai');

        const tanggalSelesai =
            detailCard.querySelector('.tanggal-selesai');

        const jamMulai =
            detailCard.querySelector('.jam-mulai');

        const jamSelesai =
            detailCard.querySelector('.jam-selesai');

        /*
        |--------------------------------------------------------------------------
        | Validasi Tanggal
        |--------------------------------------------------------------------------
        */

        tanggalMulai.addEventListener('change', function () {
            tanggalSelesai.min = this.value;

            if (
                tanggalSelesai.value &&
                tanggalSelesai.value < this.value
            ) {
                tanggalSelesai.value = this.value;
            }
        });

        tanggalSelesai.addEventListener('change', function () {
            if (
                tanggalMulai.value &&
                this.value < tanggalMulai.value
            ) {
                alert('Tanggal selesai tidak boleh sebelum tanggal mulai.');
                this.value = tanggalMulai.value;
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Validasi Jam
        |--------------------------------------------------------------------------
        */

        jamMulai.addEventListener('change', function () {
            jamSelesai.min = this.value;
        });

        jamSelesai.addEventListener('change', function () {
            if (
                jamMulai.value &&
                this.value <= jamMulai.value
            ) {
                alert('Jam selesai harus lebih besar dari jam mulai.');
                this.value = '';
            }
        });

        updateDetailNumbers();
        updateEmptyState();
    }

    /*
    |--------------------------------------------------------------------------
    | Tombol Tambah Detail
    |--------------------------------------------------------------------------
    */

    btnTambahDetail.addEventListener('click', function () {
        addDetail();
    });

    /*
    |--------------------------------------------------------------------------
    | Restore Old Input
    |--------------------------------------------------------------------------
    */

    if (Array.isArray(oldDetails) && oldDetails.length > 0) {
        oldDetails.forEach(function (detail) {
            addDetail(detail);
        });
    } else {
        addDetail();
    }

    /*
    |--------------------------------------------------------------------------
    | Validasi Form Sebelum Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', function (event) {
        const detailCards =
            detailContainer.querySelectorAll('.detail-card');

        if (detailCards.length === 0) {
            event.preventDefault();

            alert('Minimal tambahkan satu detail kegiatan.');

            return;
        }

        let valid = true;

        detailCards.forEach(function (card) {
            const tanggalMulai =
                card.querySelector('.tanggal-mulai').value;

            const tanggalSelesai =
                card.querySelector('.tanggal-selesai').value;

            const jamMulai =
                card.querySelector('.jam-mulai').value;

            const jamSelesai =
                card.querySelector('.jam-selesai').value;

            if (tanggalSelesai < tanggalMulai) {
                valid = false;
            }

            if (jamSelesai <= jamMulai) {
                valid = false;
            }
        });

        if (!valid) {
            event.preventDefault();

            alert(
                'Periksa kembali tanggal dan jam pada detail kegiatan.'
            );

            return;
        }

        btnSubmit.disabled = true;

        btnSubmit.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-2"
                role="status"
                aria-hidden="true"
            ></span>
            Menyimpan...
        `;
    });

});
</script>
@endpush