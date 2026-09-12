@extends('layouts.app')

@section('title', 'Edit Jadwal Bulanan')

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

    .jadwal-edit-page {
        padding: 24px 0 40px;
    }

    .jadwal-edit-page .page-header {
        margin-bottom: 24px;
    }

    .jadwal-edit-page .page-title {
        color: var(--text-main);
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .jadwal-edit-page .page-description {
        color: var(--text-muted);
        font-size: 13px;
        margin-bottom: 0;
    }

    .jadwal-edit-page .card,
    .jadwal-edit-page .form-footer {
        border: 1px solid var(--jade-border);
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(23, 74, 67, .04);
        overflow: hidden;
        background: #fff;
    }

    .jadwal-edit-page .card-header {
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid var(--jade-border);
    }

    .jadwal-edit-page .card-header-title {
        color: var(--jade-primary);
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .jadwal-edit-page .card-header-description {
        color: var(--text-muted);
        font-size: 12px;
        margin-bottom: 0;
    }

    .jadwal-edit-page .card-body {
        padding: 22px;
    }

    .jadwal-edit-page .form-label {
        color: #46545b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .jadwal-edit-page .required-mark {
        color: #c0392b;
    }

    .jadwal-edit-page .form-control,
    .jadwal-edit-page .form-select {
        min-height: 42px;
        border: 1px solid #dce6e3;
        border-radius: 9px;
        color: #334047;
        font-size: 13px;
        box-shadow: none;
    }

    .jadwal-edit-page textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .jadwal-edit-page .form-control:focus,
    .jadwal-edit-page .form-select:focus {
        border-color: var(--jade-secondary);
        box-shadow: 0 0 0 3px rgba(33, 102, 92, .09);
    }

    .jadwal-edit-page .form-control:disabled,
    .jadwal-edit-page .form-select:disabled {
        background: #edf1f2;
        color: #65747c;
        opacity: 1;
    }

    .jadwal-edit-page .form-text {
        color: var(--text-muted);
        font-size: 11px;
        margin-top: 6px;
    }

    .jadwal-edit-page .status-box {
        display: flex;
        align-items: center;
        min-height: 42px;
        padding: 10px 13px;
        background: #f7faf9;
        border: 1px solid #e2eeea;
        border-radius: 9px;
    }

    .jadwal-edit-page .status-badge {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .jadwal-edit-page .status-draft {
        background: #edf0f2;
        color: #65747c;
    }

    .jadwal-edit-page .status-diajukan {
        background: #fff0d0;
        color: #946500;
    }

    .jadwal-edit-page .status-disetujui {
        background: #dff2e7;
        color: #23734a;
    }

    .jadwal-edit-page .info-alert {
        padding: 13px 15px;
        background: #eef7f4;
        border: 1px solid #d8ebe5;
        color: #35675d;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.6;
    }

    .jadwal-edit-page .detail-card {
        background: #fff;
        border: 1px solid #e2ebe8;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .jadwal-edit-page .detail-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 17px;
        background: #f8fbfa;
        border-bottom: 1px solid #e6efec;
    }

    .jadwal-edit-page .detail-number {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--jade-primary);
        color: #fff;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .jadwal-edit-page .detail-title {
        color: var(--jade-primary);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .jadwal-edit-page .detail-subtitle {
        color: var(--text-muted);
        font-size: 11px;
        margin-bottom: 0;
    }

    .jadwal-edit-page .detail-card-body {
        padding: 18px;
    }

    .jadwal-edit-page .btn {
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 15px;
    }

    .jadwal-edit-page .btn-jade {
        background: var(--jade-primary);
        border-color: var(--jade-primary);
        color: #fff;
    }

    .jadwal-edit-page .btn-jade:hover {
        background: #103b35;
        border-color: #103b35;
        color: #fff;
    }

    .jadwal-edit-page .btn-outline-jade {
        background: #fff;
        border: 1px solid #bcd6cf;
        color: var(--jade-primary);
    }

    .jadwal-edit-page .btn-outline-jade:hover {
        background: #edf6f3;
        border-color: var(--jade-secondary);
        color: var(--jade-primary);
    }

    .jadwal-edit-page .btn-remove-detail {
        color: #b33a3a;
        background: #fff5f5;
        border: 1px solid #f1d7d7;
        padding: 6px 10px;
        font-size: 11px;
    }

    .jadwal-edit-page .btn-remove-detail:hover {
        background: #ffe8e8;
        color: #982c2c;
    }

    .jadwal-edit-page .empty-detail {
        padding: 35px 20px;
        text-align: center;
        border: 1px dashed #cbded8;
        border-radius: 10px;
        background: #fafcfb;
        color: var(--text-muted);
    }

    .jadwal-edit-page .empty-detail-title {
        color: #5c7770;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .jadwal-edit-page .empty-detail-description {
        font-size: 12px;
        margin-bottom: 0;
    }

    .jadwal-edit-page .form-footer {
        padding: 20px 22px;
        margin-top: 20px;
    }

    .jadwal-edit-page .footer-note {
        color: var(--text-muted);
        font-size: 11px;
        margin-bottom: 0;
    }

    .jadwal-edit-page .footer-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .jadwal-edit-page .btn-approve {
        background: #23734a;
        border-color: #23734a;
        color: #fff;
    }

    .jadwal-edit-page .btn-approve:hover {
        background: #1b5b3a;
        border-color: #1b5b3a;
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .jadwal-edit-page {
            padding-top: 16px;
        }

        .jadwal-edit-page .page-title {
            font-size: 21px;
        }

        .jadwal-edit-page .card-header,
        .jadwal-edit-page .card-body,
        .jadwal-edit-page .form-footer {
            padding: 17px;
        }

        .jadwal-edit-page .footer-actions {
            justify-content: stretch;
            width: 100%;
        }

        .jadwal-edit-page .footer-actions .btn {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

@php
    $status = strtolower($jadwal->status ?? 'draft');

    $role = strtolower(
        auth()->user()->role
        ?? auth()->user()->nama_role
        ?? ''
    );

    $isCoordinator = in_array($role, [
        'koordinator',
        'coordinator',
        'admin',
        'superadmin',
    ]);

    // Bisa diedit & disimpan selama status BUKAN disetujui.
    // (Halaman ini pun sudah tidak akan dicapai saat disetujui,
    // karena method edit() di controller meredirect lebih dulu.)
    $canEdit = in_array($status, ['draft', 'diajukan']);

    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $periodeJadwal = sprintf(
        '%04d-%02d',
        (int) $jadwal->tahun,
        (int) $jadwal->bulan
    );

    $periodeText =
        ($namaBulan[(int) $jadwal->bulan] ?? '-') .
        ' ' .
        $jadwal->tahun;
@endphp

<div class="container-fluid jadwal-edit-page">

    <div class="page-header">
        <h4 class="page-title">
            Edit Jadwal Bulanan
        </h4>

        <p class="page-description">
            Perbarui informasi jadwal dan detail kegiatan Posyandu.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <strong>Terdapat kesalahan pada data:</strong>

            <ul class="mb-0 mt-2 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM EDIT JADWAL --}}
    <form
        id="jadwalEditForm"
        action="{{ route('jadwal.update', $jadwal->id) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        {{-- INFORMASI UTAMA --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="card-header-title">
                    Informasi Jadwal
                </h5>

                <p class="card-header-description">
                    Informasi utama jadwal bulanan Posyandu.
                </p>
            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="periode" class="form-label">
                            Periode Jadwal
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="month"
                            id="periode"
                            name="periode"
                            class="form-control"
                            value="{{ old('periode', $periodeJadwal) }}"
                            disabled
                        >

                        <div class="form-text">
                            Periode jadwal: {{ $periodeText }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Status Jadwal
                        </label>

                        <div class="status-box">
                            <span class="status-badge status-{{ $status }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="catatan" class="form-label">
                            Catatan Jadwal
                            <span class="text-muted fw-normal">
                                (Opsional)
                            </span>
                        </label>

                        {{-- Catatan ini selalu bisa diisi/diubah, tidak readonly. --}}
                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="4"
                            class="form-control"
                            placeholder="Tambahkan catatan umum untuk jadwal bulan ini..."
                        >{{ old('catatan', $jadwal->catatan) }}</textarea>
                    </div>

                    <div class="col-12">

                        @if ($status === 'draft')
                            <div class="info-alert">
                                Jadwal masih berstatus draft.
                                Silakan periksa seluruh detail kegiatan.
                                Jika sudah benar, klik
                                <strong>Beri ke Koordinator</strong>.
                            </div>
                        @elseif ($status === 'diajukan')
                            <div class="info-alert">
                                Jadwal sudah diajukan dan sedang menunggu
                                persetujuan koordinator. Anda masih dapat
                                memperbarui informasi jadwal sebelum disetujui.
                            </div>
                        @elseif ($status === 'disetujui')
                            <div class="info-alert">
                                Jadwal sudah disetujui oleh koordinator
                                dan tidak dapat diubah lagi.
                            </div>
                        @endif

                    </div>

                </div>

            </div>
        </div>

        {{-- DETAIL KEGIATAN --}}
        <div class="card mb-4">

            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>
                    <h5 class="card-header-title">
                        Detail Kegiatan
                    </h5>

                    <p class="card-header-description">
                        Daftar Posyandu, kegiatan, tanggal, jam, dan keterangan.
                    </p>
                </div>

                @if ($canEdit)
                    <button
                        type="button"
                        id="btnTambahDetail"
                        class="btn btn-outline-jade"
                    >
                        Tambah Detail
                    </button>
                @endif

            </div>

            <div class="card-body">

                <div id="detailContainer"></div>

                <div
                    id="emptyDetail"
                    class="empty-detail"
                    style="display: none;"
                >
                    <div class="empty-detail-title">
                        Belum ada detail jadwal
                    </div>

                    <p class="empty-detail-description">
                        Klik tombol "Tambah Detail" untuk menambahkan kegiatan.
                    </p>
                </div>

            </div>
        </div>

        {{-- FOOTER FORM EDIT --}}
        <div class="form-footer">

            <div class="row align-items-center g-3">

                <div class="col-md-6">
                    <p class="footer-note">
                        Pastikan seluruh data sudah benar sebelum disimpan
                        atau diberikan kepada koordinator.
                    </p>
                </div>

                <div class="col-md-6">
                    <div class="footer-actions">

                        <a
                            href="{{ route('jadwal.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Kembali
                        </a>

                        @if ($canEdit)

                            <button
                                type="submit"
                                id="btnSimpan"
                                class="btn btn-outline-jade"
                            >
                                Simpan Perubahan
                            </button>

                            @if ($status === 'draft')
                                <button
                                    type="button"
                                    id="btnKoordinator"
                                    class="btn btn-jade"
                                >
                                    Beri ke Koordinator
                                </button>
                            @endif

                        @elseif ($status === 'disetujui')

                            <button
                                type="button"
                                class="btn btn-success"
                                disabled
                            >
                                Jadwal Disetujui
                            </button>

                        @endif

                    </div>
                </div>

            </div>

        </div>

    </form>

    {{-- FORM APPROVE TERPISAH --}}
    {{-- Penting: tidak menggunakan PUT agar route approve menerima POST --}}
    @if ($isCoordinator && $status === 'diajukan')

        <form
            id="formApproveJadwal"
            action="{{ route('jadwal.approve', $jadwal->id) }}"
            method="POST"
            class="d-none"
        >
            @csrf
        </form>

        <div class="form-footer mt-3">

            <div class="row align-items-center g-3">

                <div class="col-md-6">
                    <p class="footer-note">
                        Setelah disetujui, status jadwal akan berubah menjadi disetujui.
                    </p>
                </div>

                <div class="col-md-6">
                    <div class="footer-actions">

                        <button
                            type="button"
                            id="btnSetujui"
                            class="btn btn-approve"
                        >
                            Setujui Jadwal
                        </button>

                    </div>
                </div>

            </div>

        </div>

    @endif

</div>

@endsection

@push('scripts')

{{-- Pastikan SweetAlert2 tersedia --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('jadwalEditForm');
    const detailContainer = document.getElementById('detailContainer');
    const emptyDetail = document.getElementById('emptyDetail');
    const btnTambahDetail = document.getElementById('btnTambahDetail');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnKoordinator = document.getElementById('btnKoordinator');
    const btnSetujui = document.getElementById('btnSetujui');
    const formApproveJadwal = document.getElementById('formApproveJadwal');

    if (!form || !detailContainer) {
        return;
    }

    const posyandus = @json($posyandus ?? []);
    const kegiatans = @json($kegiatans ?? []);
    const existingDetails = @json($jadwal->details ?? []);
    const oldDetails = @json(old('details', []));
    const canEdit = @json($canEdit);

    let detailIndex = 0;

    function showAlert(options) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire(options);
        }

        if (options.showCancelButton) {
            const confirmed = window.confirm(
                options.title + '\n\n' + (options.text || '')
            );

            return Promise.resolve({
                isConfirmed: confirmed
            });
        }

        window.alert(
            options.title + '\n\n' + (options.text || '')
        );

        return Promise.resolve({
            isConfirmed: true
        });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeDate(value) {
        if (!value) {
            return '';
        }

        return String(value).substring(0, 10);
    }

    function normalizeTime(value) {
        if (!value) {
            return '';
        }

        return String(value).substring(0, 5);
    }

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
        const nama =
            posyandu.nama_posyandu ||
            posyandu.nama ||
            'Posyandu tanpa nama';

        return nama + ' — ' + getWilayahName(posyandu);
    }

    function getKegiatanLabel(kegiatan) {
        return (
            kegiatan.nama_kegiatan ||
            kegiatan.nama ||
            'Kegiatan tanpa nama'
        );
    }

    function buildPosyanduOptions(selectedValue = '') {
        let html = '<option value="">Pilih Posyandu</option>';

        posyandus.forEach(function (posyandu) {

            const selected =
                String(selectedValue) === String(posyandu.id)
                    ? 'selected'
                    : '';

            html += `
                <option value="${escapeHtml(posyandu.id)}" ${selected}>
                    ${escapeHtml(getPosyanduLabel(posyandu))}
                </option>
            `;
        });

        return html;
    }

    function buildKegiatanOptions(selectedValue = '') {
        let html = '<option value="">Pilih Kegiatan</option>';

        kegiatans.forEach(function (kegiatan) {

            const selected =
                String(selectedValue) === String(kegiatan.id)
                    ? 'selected'
                    : '';

            html += `
                <option value="${escapeHtml(kegiatan.id)}" ${selected}>
                    ${escapeHtml(getKegiatanLabel(kegiatan))}
                </option>
            `;
        });

        return html;
    }

    function updateEmptyState() {
        const total =
            detailContainer.querySelectorAll('.detail-card').length;

        emptyDetail.style.display =
            total === 0 ? 'block' : 'none';
    }

    function updateDetailNumbers() {
        detailContainer
            .querySelectorAll('.detail-card')
            .forEach(function (card, index) {

                const number =
                    card.querySelector('.detail-number');

                if (number) {
                    number.textContent = index + 1;
                }

            });
    }

    function addDetail(data = {}) {

        const index = detailIndex++;

        const detailId = data.id || '';
        const posyanduId = data.posyandu_id || '';
        const kegiatanId = data.kegiatan_id || '';
        const detailStatus = data.status || 'terjadwal';
        const tanggalMulai = normalizeDate(
            data.tgl_mulai || data.tanggal_mulai
        );
        const tanggalSelesai = normalizeDate(
            data.tgl_selesai || data.tanggal_selesai
        );
        const jamMulai = normalizeTime(data.jam_mulai);
        const jamSelesai = normalizeTime(data.jam_selesai);
        const keterangan = data.keterangan || '';

        const card = document.createElement('div');

        card.className = 'detail-card';

        card.innerHTML = `
            <input
                type="hidden"
                name="details[${index}][id]"
                value="${escapeHtml(detailId)}"
            >

            <input
                type="hidden"
                name="details[${index}][status]"
                value="${escapeHtml(detailStatus)}"
            >

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

                ${
                    canEdit
                        ? `
                            <button
                                type="button"
                                class="btn btn-remove-detail btnHapusDetail"
                            >
                                Hapus
                            </button>
                        `
                        : ''
                }

            </div>

            <div class="detail-card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Posyandu
                            <span class="required-mark">*</span>
                        </label>

                        <select
                            name="details[${index}][posyandu_id]"
                            class="form-select"
                            required
                            ${!canEdit ? 'disabled' : ''}
                        >
                            ${buildPosyanduOptions(posyanduId)}
                        </select>

                        <div class="form-text">
                            Nama Posyandu ditampilkan bersama wilayah.
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Jenis Kegiatan
                            <span class="required-mark">*</span>
                        </label>

                        <select
                            name="details[${index}][kegiatan_id]"
                            class="form-select"
                            required
                            ${!canEdit ? 'disabled' : ''}
                        >
                            ${buildKegiatanOptions(kegiatanId)}
                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Tanggal Mulai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_mulai]"
                            class="form-control tanggal-mulai"
                            value="${escapeHtml(tanggalMulai)}"
                            required
                            ${!canEdit ? 'disabled' : ''}
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Tanggal Selesai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_selesai]"
                            class="form-control tanggal-selesai"
                            value="${escapeHtml(tanggalSelesai)}"
                            required
                            ${!canEdit ? 'disabled' : ''}
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Jam Mulai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_mulai]"
                            class="form-control jam-mulai"
                            value="${escapeHtml(jamMulai)}"
                            required
                            ${!canEdit ? 'disabled' : ''}
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Jam Selesai
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_selesai]"
                            class="form-control jam-selesai"
                            value="${escapeHtml(jamSelesai)}"
                            required
                            ${!canEdit ? 'disabled' : ''}
                        >

                    </div>

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
                            ${!canEdit ? 'disabled' : ''}
                        >${escapeHtml(keterangan)}</textarea>

                    </div>

                </div>

            </div>
        `;

        detailContainer.appendChild(card);

        const btnHapusDetail =
            card.querySelector('.btnHapusDetail');

        const tanggalMulaiInput =
            card.querySelector('.tanggal-mulai');

        const tanggalSelesaiInput =
            card.querySelector('.tanggal-selesai');

        const jamMulaiInput =
            card.querySelector('.jam-mulai');

        const jamSelesaiInput =
            card.querySelector('.jam-selesai');

        if (btnHapusDetail) {

            btnHapusDetail.addEventListener('click', function () {

                card.remove();

                updateDetailNumbers();
                updateEmptyState();

            });

        }

        if (tanggalMulaiInput && tanggalSelesaiInput) {

            tanggalMulaiInput.addEventListener('change', function () {

                tanggalSelesaiInput.min = this.value;

                if (
                    tanggalSelesaiInput.value &&
                    tanggalSelesaiInput.value < this.value
                ) {
                    tanggalSelesaiInput.value = this.value;
                }

            });

            if (tanggalMulaiInput.value) {
                tanggalSelesaiInput.min =
                    tanggalMulaiInput.value;
            }

        }

        if (jamMulaiInput && jamSelesaiInput) {

            jamMulaiInput.addEventListener('change', function () {
                jamSelesaiInput.min = this.value;
            });

            if (jamMulaiInput.value) {
                jamSelesaiInput.min =
                    jamMulaiInput.value;
            }

        }

        updateDetailNumbers();
        updateEmptyState();
    }

    function validateDetails() {

        const cards =
            detailContainer.querySelectorAll('.detail-card');

        if (cards.length === 0) {

            showAlert({
                icon: 'warning',
                title: 'Detail Belum Ada',
                text: 'Minimal tambahkan satu detail kegiatan.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#174a43'
            });

            return false;
        }

        for (const card of cards) {

            const posyandu =
                card.querySelector('[name*="[posyandu_id]"]').value;

            const kegiatan =
                card.querySelector('[name*="[kegiatan_id]"]').value;

            const tanggalMulai =
                card.querySelector('.tanggal-mulai').value;

            const tanggalSelesai =
                card.querySelector('.tanggal-selesai').value;

            const jamMulai =
                card.querySelector('.jam-mulai').value;

            const jamSelesai =
                card.querySelector('.jam-selesai').value;

            if (!posyandu || !kegiatan) {

                showAlert({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Posyandu dan jenis kegiatan wajib diisi.',
                    confirmButtonText: 'Periksa Data',
                    confirmButtonColor: '#174a43'
                });

                return false;
            }

            if (
                tanggalMulai &&
                tanggalSelesai &&
                tanggalSelesai < tanggalMulai
            ) {

                showAlert({
                    icon: 'warning',
                    title: 'Tanggal Tidak Valid',
                    text: 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
                    confirmButtonText: 'Periksa Data',
                    confirmButtonColor: '#174a43'
                });

                return false;
            }

            if (
                jamMulai &&
                jamSelesai &&
                jamSelesai <= jamMulai
            ) {

                showAlert({
                    icon: 'warning',
                    title: 'Jam Tidak Valid',
                    text: 'Jam selesai harus lebih besar dari jam mulai.',
                    confirmButtonText: 'Periksa Data',
                    confirmButtonColor: '#174a43'
                });

                return false;
            }
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Muat Detail Lama
    |--------------------------------------------------------------------------
    */

    if (Array.isArray(oldDetails) && oldDetails.length > 0) {

        oldDetails.forEach(function (detail) {
            addDetail(detail);
        });

    } else if (
        Array.isArray(existingDetails) &&
        existingDetails.length > 0
    ) {

        existingDetails.forEach(function (detail) {
            addDetail(detail);
        });

    } else if (canEdit) {

        addDetail();

    } else {

        emptyDetail.style.display = 'block';

    }

    /*
    |--------------------------------------------------------------------------
    | Tambah Detail
    |--------------------------------------------------------------------------
    */

    if (btnTambahDetail) {

        btnTambahDetail.addEventListener('click', function () {
            addDetail();
        });

    }

    /*
    |--------------------------------------------------------------------------
    | Simpan Perubahan
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', function (event) {

        if (!validateDetails()) {
            event.preventDefault();
            return;
        }

        if (btnSimpan) {
            btnSimpan.disabled = true;
            btnSimpan.innerText = 'Menyimpan...';
        }

    });

    /*
    |--------------------------------------------------------------------------
    | Beri ke Koordinator
    |--------------------------------------------------------------------------
    */

    if (btnKoordinator) {

        btnKoordinator.addEventListener('click', function () {

            if (!validateDetails()) {
                return;
            }

            showAlert({
                icon: 'question',
                title: 'Beri ke Koordinator?',
                text: 'Jadwal akan dikirim kepada koordinator untuk diperiksa.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Berikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#174a43',
                cancelButtonColor: '#6c757d'
            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }

                const methodInput =
                    form.querySelector('input[name="_method"]');

                if (methodInput) {
                    methodInput.remove();
                }

                form.action =
                    "{{ route('jadwal.submit', $jadwal->id) }}";

                form.method = 'POST';

                btnKoordinator.disabled = true;
                btnKoordinator.innerText = 'Mengirim...';

                form.submit();

            });

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Setujui Jadwal Oleh Koordinator
    |--------------------------------------------------------------------------
    | Form approve terpisah dan method POST.
    | Tidak memakai formmethod PUT dari form edit.
    |--------------------------------------------------------------------------
    */

    if (btnSetujui && formApproveJadwal) {

        btnSetujui.addEventListener('click', function () {

            showAlert({
                icon: 'question',
                title: 'Setujui Jadwal?',
                text: 'Jadwal yang disetujui akan berubah menjadi status disetujui.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#23734a',
                cancelButtonColor: '#6c757d'
            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }

                btnSetujui.disabled = true;
                btnSetujui.innerText = 'Menyetujui...';

                formApproveJadwal.submit();

            });

        });

    }

});
</script>
@endpush