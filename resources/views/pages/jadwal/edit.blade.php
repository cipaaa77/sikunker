@extends('layouts.app')

@section('title', 'Edit Jadwal Bulanan')

@push('styles')
<style>
    .jadwal-edit-page {
        padding: 24px 0 40px;
    }

    .jadwal-edit-page .page-title {
        color: #263238;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .jadwal-edit-page .page-description {
        color: #84919a;
        font-size: 13px;
        margin-bottom: 24px;
    }

    .jadwal-edit-page .card,
    .jadwal-edit-page .form-footer {
        background: #fff;
        border: 1px solid #e3ece9;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(23, 74, 67, .04);
        overflow: hidden;
    }

    .jadwal-edit-page .card-header {
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid #e3ece9;
    }

    .jadwal-edit-page .card-header-title {
        color: #174a43;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .jadwal-edit-page .card-header-description {
        color: #84919a;
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
        min-height: 115px;
        resize: vertical;
    }

    .jadwal-edit-page .form-control:focus,
    .jadwal-edit-page .form-select:focus {
        border-color: #21665c;
        box-shadow: 0 0 0 3px rgba(33, 102, 92, .09);
    }

    .jadwal-edit-page .form-control:disabled,
    .jadwal-edit-page .form-select:disabled {
        background: #edf1f2;
        color: #65747c;
        opacity: 1;
        cursor: not-allowed;
    }

    .jadwal-edit-page .form-text {
        color: #84919a;
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

    .jadwal-edit-page .status-ditolak {
        background: #fde2e2;
        color: #a52a2a;
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

    .jadwal-edit-page .warning-alert {
        padding: 13px 15px;
        background: #fff5e6;
        border: 1px solid #f2dfbd;
        color: #8b641d;
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

    .jadwal-edit-page .detail-card:last-child {
        margin-bottom: 0;
    }

    .jadwal-edit-page .detail-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        background: #174a43;
        color: #fff;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .jadwal-edit-page .detail-title {
        color: #174a43;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .jadwal-edit-page .detail-subtitle {
        color: #84919a;
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
        background: #174a43;
        border-color: #174a43;
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
        color: #174a43;
    }

    .jadwal-edit-page .btn-outline-jade:hover {
        background: #edf6f3;
        border-color: #21665c;
        color: #174a43;
    }

    .jadwal-edit-page .btn-approve {
        background: #23734a;
        border-color: #23734a;
        color: #fff;
    }

    .jadwal-edit-page .btn-reject {
        background: #c0392b;
        border-color: #c0392b;
        color: #fff;
    }

    .jadwal-edit-page .btn-remove-detail {
        color: #b33a3a;
        background: #fff5f5;
        border: 1px solid #f1d7d7;
        padding: 6px 10px;
        font-size: 11px;
    }

    .jadwal-edit-page .form-footer {
        padding: 20px 22px;
        margin-top: 20px;
    }

    .jadwal-edit-page .footer-note {
        color: #84919a;
        font-size: 11px;
        margin-bottom: 0;
    }

    .jadwal-edit-page .footer-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    @media (max-width: 767.98px) {
        .jadwal-edit-page .card-header,
        .jadwal-edit-page .card-body,
        .jadwal-edit-page .form-footer {
            padding: 17px;
        }

        .jadwal-edit-page .footer-actions {
            justify-content: stretch;
        }

        .jadwal-edit-page .footer-actions .btn {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

@php
    $status = strtolower((string) ($jadwal->status ?? 'draft'));

    $user = auth()->user();

    $role = strtolower((string) (
        $user->role
        ?? $user->nama_role
        ?? ''
    ));

    $isAdmin = in_array($role, [
        'admin',
        'administrator',
        'superadmin',
    ], true);

    $isCoordinator = in_array($role, [
        'koordinator',
        'coordinator',
    ], true);

    $canEditDetail = $isAdmin && in_array($status, [
        'draft',
        'ditolak',
    ], true);

    $canEditCatatan = ($isAdmin || $isCoordinator)
        && $status !== 'disetujui';

    $canSubmit = $isAdmin && in_array($status, [
        'draft',
        'ditolak',
    ], true);

    $canApprove = $isCoordinator && $status === 'diajukan';

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

    /*
    |--------------------------------------------------------------------------
    | Catatan yang ditampilkan
    |--------------------------------------------------------------------------
    |
    | Prioritas:
    | 1. Input old jika validasi gagal
    | 2. Catatan jadwal utama
    | 3. Catatan approval
    |--------------------------------------------------------------------------
    */

    $catatanTersimpan = $jadwal->catatan
        ?? optional($jadwal->approval)->catatan
        ?? '';
@endphp

<div class="container-fluid jadwal-edit-page">

    <div class="mb-4" style="color: #ffffff;">
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



    {{-- =========================================================
         FORM UTAMA
    ========================================================== --}}
    <form
        id="jadwalEditForm"
        action="{{ route('jadwal.update', $jadwal->id) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        {{-- save = simpan biasa, submit = beri ke koordinator --}}
        <input
            type="hidden"
            name="action"
            id="jadwalAction"
            value="save"
        >

        {{-- =====================================================
             INFORMASI JADWAL
        ====================================================== --}}
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
                        <label
                            for="periode"
                            class="form-label"
                        >
                            Periode Jadwal
                        </label>

                        <input
                            type="month"
                            id="periode"
                            class="form-control"
                            value="{{ $periodeJadwal }}"
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
                            id="catatan"
                            name="catatan"
                            rows="4"
                            class="form-control"
                            placeholder="Tambahkan catatan umum atau alasan penolakan..."
                            @disabled(!$canEditCatatan)
                        >{{ old('catatan', $catatanTersimpan) }}</textarea>

                        @if ($canEditCatatan)
                            <div class="form-text">
                                Catatan akan ikut tersimpan ketika jadwal
                                disimpan atau diajukan kepada koordinator.
                            </div>
                        @else
                            <div class="form-text">
                                Jadwal sudah disetujui sehingga catatan dikunci.
                            </div>
                        @endif
                    </div>

                    <div class="col-12">

                        @if ($status === 'draft')

                            <div class="info-alert">
                                Jadwal masih berstatus draft.
                                Periksa seluruh detail sebelum mengajukan
                                jadwal kepada koordinator.
                            </div>

                        @elseif ($status === 'diajukan')

                            <div class="info-alert">
                                Jadwal sedang menunggu pemeriksaan koordinator.
                                Detail kegiatan dikunci.
                            </div>

                        @elseif ($status === 'ditolak')

                            <div class="warning-alert">
                                Jadwal ditolak oleh koordinator.
                                Perbaiki detail dan catatan, kemudian
                                ajukan kembali kepada koordinator.
                            </div>

                        @elseif ($status === 'disetujui')

                            <div class="info-alert">
                                Jadwal sudah disetujui oleh koordinator.
                                Seluruh data tidak dapat diubah.
                            </div>

                        @endif

                    </div>

                </div>

            </div>
        </div>

        {{-- =========================================================
             DETAIL KEGIATAN
        ========================================================== --}}
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

                @if ($canEditDetail)
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

                <div id="detailContainer">

                    @forelse ($jadwal->details as $index => $detail)

                        <div class="detail-card">

                            <div class="detail-card-header">

                                <div class="d-flex align-items-center gap-2">

                                    <div class="detail-number">
                                        {{ $index + 1 }}
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

                                @if ($canEditDetail)
                                    <button
                                        type="button"
                                        class="btn btn-remove-detail btnHapusDetail"
                                    >
                                        Hapus
                                    </button>
                                @endif

                            </div>

                            <div class="detail-card-body">

                                <input
                                    type="hidden"
                                    name="details[{{ $index }}][id]"
                                    value="{{ $detail->id }}"
                                >

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Posyandu
                                        </label>

                                        <select
                                            name="details[{{ $index }}][posyandu_id]"
                                            class="form-select"
                                            required
                                            @disabled(!$canEditDetail)
                                        >
                                            <option value="">
                                                Pilih Posyandu
                                            </option>

                                            @foreach ($posyandus as $posyandu)
                                                <option
                                                    value="{{ $posyandu->id }}"
                                                    @selected(
                                                        (string) $detail->posyandu_id ===
                                                        (string) $posyandu->id
                                                    )
                                                >
                                                    {{ $posyandu->nama_posyandu }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Kegiatan
                                        </label>

                                        <select
                                            name="details[{{ $index }}][kegiatan_id]"
                                            class="form-select"
                                            required
                                            @disabled(!$canEditDetail)
                                        >
                                            <option value="">
                                                Pilih Kegiatan
                                            </option>

                                            @foreach ($kegiatans as $kegiatan)
                                                <option
                                                    value="{{ $kegiatan->id }}"
                                                    @selected(
                                                        (string) $detail->kegiatan_id ===
                                                        (string) $kegiatan->id
                                                    )
                                                >
                                                    {{ $kegiatan->nama_kegiatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Tanggal Mulai
                                        </label>

                                        <input
                                            type="date"
                                            name="details[{{ $index }}][tgl_mulai]"
                                            class="form-control"
                                            value="{{ old(
                                                'details.' . $index . '.tgl_mulai',
                                                substr((string) $detail->tgl_mulai, 0, 10)
                                            ) }}"
                                            required
                                            @disabled(!$canEditDetail)
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Tanggal Selesai
                                        </label>

                                        <input
                                            type="date"
                                            name="details[{{ $index }}][tgl_selesai]"
                                            class="form-control"
                                            value="{{ old(
                                                'details.' . $index . '.tgl_selesai',
                                                substr((string) $detail->tgl_selesai, 0, 10)
                                            ) }}"
                                            required
                                            @disabled(!$canEditDetail)
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Jam Mulai
                                        </label>

                                        <input
                                            type="time"
                                            name="details[{{ $index }}][jam_mulai]"
                                            class="form-control"
                                            value="{{ old(
                                                'details.' . $index . '.jam_mulai',
                                                substr((string) $detail->jam_mulai, 0, 5)
                                            ) }}"
                                            required
                                            @disabled(!$canEditDetail)
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Jam Selesai
                                        </label>

                                        <input
                                            type="time"
                                            name="details[{{ $index }}][jam_selesai]"
                                            class="form-control"
                                            value="{{ old(
                                                'details.' . $index . '.jam_selesai',
                                                substr((string) $detail->jam_selesai, 0, 5)
                                            ) }}"
                                            required
                                            @disabled(!$canEditDetail)
                                        >
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            Keterangan
                                        </label>

                                        <textarea
                                            name="details[{{ $index }}][keterangan]"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Tambahkan keterangan kegiatan..."
                                            @disabled(!$canEditDetail)
                                        >{{ old(
                                            'details.' . $index . '.keterangan',
                                            $detail->keterangan
                                        ) }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @empty

                        <div
                            id="emptyDetail"
                            class="text-center py-5"
                        >
                            <div class="text-muted">
                                Belum ada detail kegiatan.
                            </div>
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        {{-- =========================================================
             FOOTER ADMIN
        ========================================================== --}}
        <div class="form-footer">

            <div class="row align-items-center g-3">

                <div class="col-md-6">
                    <p class="footer-note">

                        @if ($status === 'draft')
                            Jadwal masih dapat diperbaiki sebelum diajukan.
                        @elseif ($status === 'ditolak')
                            Perbaiki jadwal kemudian ajukan kembali kepada koordinator.
                        @elseif ($status === 'diajukan')
                            Jadwal sedang diperiksa oleh koordinator.
                        @elseif ($status === 'disetujui')
                            Jadwal sudah disetujui dan dikunci.
                        @endif

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

                        @if ($canEditDetail)

                            <button
                                type="submit"
                                id="btnSimpan"
                                class="btn btn-outline-jade"
                            >
                                Simpan Perubahan
                            </button>

                            @if ($canSubmit)

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

    {{-- =========================================================
         FORM KOORDINATOR
    ========================================================== --}}
    @if ($canApprove)

        {{-- FORM APPROVE --}}
        <form
            id="formApproveJadwal"
            action="{{ route('jadwal.approve', $jadwal->id) }}"
            method="POST"
            class="d-none"
        >
            @csrf
        </form>

        {{-- FORM REJECT --}}
        <form
            id="formRejectJadwal"
            action="{{ route('jadwal.reject', $jadwal->id) }}"
            method="POST"
            class="d-none"
        >
            @csrf

            <input
                type="hidden"
                name="catatan_koordinator"
                id="catatanPenolakan"
            >
        </form>

        <div class="form-footer">

            <div class="row align-items-center g-3">

                <div class="col-md-6">
                    <p class="footer-note">
                        Koordinator dapat menyetujui atau menolak jadwal.
                        Catatan wajib diisi jika jadwal ditolak.
                    </p>
                </div>

                <div class="col-md-6">

                    <div class="footer-actions">

                        <button
                            type="button"
                            id="btnTolak"
                            class="btn btn-reject"
                        >
                            Tolak Jadwal
                        </button>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('jadwalEditForm');

    const detailContainer =
        document.getElementById('detailContainer');

    const btnTambahDetail =
        document.getElementById('btnTambahDetail');

    const btnSimpan =
        document.getElementById('btnSimpan');

    const btnKoordinator =
        document.getElementById('btnKoordinator');

    const jadwalAction =
        document.getElementById('jadwalAction');

    const btnTolak =
        document.getElementById('btnTolak');

    const btnSetujui =
        document.getElementById('btnSetujui');

    const formApproveJadwal =
        document.getElementById('formApproveJadwal');

    const formRejectJadwal =
        document.getElementById('formRejectJadwal');

    const catatan =
        document.getElementById('catatan');

    const catatanPenolakan =
        document.getElementById('catatanPenolakan');

    const canEditDetail =
        @json($canEditDetail);

    const canSubmit =
        @json($canSubmit);

    const posyandus =
        @json($posyandus ?? []);

    const kegiatans =
        @json($kegiatans ?? []);

    let detailIndex = detailContainer
        ? detailContainer.querySelectorAll('.detail-card').length
        : 0;

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function buildOptions(items, valueKey, labelKey) {
        let html = '<option value="">Pilih</option>';

        items.forEach(function (item) {
            html += `
                <option value="${escapeHtml(item[valueKey])}">
                    ${escapeHtml(item[labelKey])}
                </option>
            `;
        });

        return html;
    }

    function updateNumbers() {
        if (!detailContainer) {
            return;
        }

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

    function addDetail() {
        if (!canEditDetail || !detailContainer) {
            return;
        }

        const index = detailIndex++;

        const card =
            document.createElement('div');

        card.className = 'detail-card';

        card.innerHTML = `
            <div class="detail-card-header">

                <div class="d-flex align-items-center gap-2">

                    <div class="detail-number">
                        ${index + 1}
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
                    Hapus
                </button>

            </div>

            <div class="detail-card-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Posyandu
                        </label>

                        <select
                            name="details[${index}][posyandu_id]"
                            class="form-select"
                            required
                        >
                            ${buildOptions(
                                posyandus,
                                'id',
                                'nama_posyandu'
                            )}
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Kegiatan
                        </label>

                        <select
                            name="details[${index}][kegiatan_id]"
                            class="form-select"
                            required
                        >
                            ${buildOptions(
                                kegiatans,
                                'id',
                                'nama_kegiatan'
                            )}
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_mulai]"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="details[${index}][tgl_selesai]"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jam Mulai
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_mulai]"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jam Selesai
                        </label>

                        <input
                            type="time"
                            name="details[${index}][jam_selesai]"
                            class="form-control"
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
                            rows="3"
                            placeholder="Tambahkan keterangan kegiatan..."
                        ></textarea>
                    </div>

                </div>
            </div>
        `;

        detailContainer.appendChild(card);

        const emptyDetail =
            document.getElementById('emptyDetail');

        if (emptyDetail) {
            emptyDetail.remove();
        }

        updateNumbers();
    }

    /*
    |--------------------------------------------------------------------------
    | Tambah detail
    |--------------------------------------------------------------------------
    */

    if (btnTambahDetail) {
        btnTambahDetail.addEventListener('click', function () {
            addDetail();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Hapus detail
    |--------------------------------------------------------------------------
    */

    if (detailContainer) {
        detailContainer.addEventListener('click', function (event) {

            const button =
                event.target.closest('.btnHapusDetail');

            if (!button) {
                return;
            }

            const card =
                button.closest('.detail-card');

            if (!card) {
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Hapus detail?',
                text: 'Detail kegiatan ini akan dihapus dari form.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6c757d'
            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }

                card.remove();

                updateNumbers();
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Submit form biasa
    |--------------------------------------------------------------------------
    */

    if (form) {
        form.addEventListener('submit', function (event) {

            if (!canEditDetail) {
                event.preventDefault();
                return;
            }

            /*
            | Jika tidak ditentukan, default adalah save.
            */

            if (jadwalAction && !jadwalAction.value) {
                jadwalAction.value = 'save';
            }

            if (btnSimpan) {
                btnSimpan.disabled = true;
                btnSimpan.innerText = 'Menyimpan...';
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Beri ke Koordinator
    |--------------------------------------------------------------------------
    |
    | PENTING:
    | Jangan mengubah action ke jadwal.submit.
    | Form tetap dikirim ke jadwal.update agar:
    | - catatan tersimpan
    | - detail tersimpan
    | - status menjadi diajukan
    |--------------------------------------------------------------------------
    */

    if (btnKoordinator && form && jadwalAction && canSubmit) {

        btnKoordinator.addEventListener('click', function () {

            Swal.fire({
                icon: 'question',
                title: 'Beri ke Koordinator?',
                text: 'Perubahan akan disimpan dan jadwal dikirim kepada koordinator.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Berikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#174a43',
                cancelButtonColor: '#6c757d'
            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Ubah aksi menjadi submit
                |--------------------------------------------------------------------------
                */

                jadwalAction.value = 'submit';

                /*
                |--------------------------------------------------------------------------
                | Form tetap menggunakan:
                | PUT jadwal.update
                |--------------------------------------------------------------------------
                */

                btnKoordinator.disabled = true;
                btnKoordinator.innerText = 'Mengirim...';

                form.submit();
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Tolak Jadwal
    |--------------------------------------------------------------------------
    |
    | Catatan diambil dari textarea utama.
    |--------------------------------------------------------------------------
    */

    if (
        btnTolak &&
        formRejectJadwal &&
        catatan &&
        catatanPenolakan
    ) {

        btnTolak.addEventListener('click', function () {

            const isiCatatan =
                catatan.value.trim();

            if (!isiCatatan) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Catatan wajib diisi',
                    text: 'Isi Catatan Jadwal terlebih dahulu sebelum menolak jadwal.'
                });

                catatan.focus();

                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Tolak Jadwal?',
                text: 'Jadwal akan dikembalikan kepada admin.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#6c757d'
            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }

                catatanPenolakan.value =
                    isiCatatan;

                btnTolak.disabled = true;
                btnTolak.innerText = 'Menolak...';

                formRejectJadwal.submit();
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Setujui Jadwal
    |--------------------------------------------------------------------------
    */

    if (btnSetujui && formApproveJadwal) {

        btnSetujui.addEventListener('click', function () {

            Swal.fire({
                icon: 'question',
                title: 'Setujui Jadwal?',
                text: 'Jadwal yang disetujui tidak dapat diubah lagi.',
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