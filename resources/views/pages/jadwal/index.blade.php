@extends('layouts.app')

@section('title', 'Jadwal Bulanan')
@section('breadcrumb', 'Jadwal Bulanan')
@section('page-title', 'Data Jadwal Bulanan')

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

    .btn-outline-jade {
        border: 1px solid #21665c;
        color: #21665c;
        background: #fff;
    }

    .btn-outline-jade:hover {
        background: #e8f2f0;
        color: #174a43;
    }

    .search-box {
        max-width: 280px;
    }

    .search-box .form-control {
        padding-left: 38px;
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa5a3;
        z-index: 5;
    }

    .filter-select {
        max-width: 180px;
    }

    .jadwal-row {
        border-top: 1px solid #edf0f0;
        transition: background .2s ease;
    }

    .jadwal-row:hover {
        background: #fbfcfc;
    }

    .jadwal-main {
        padding: 15px 20px;
    }

    .jadwal-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e8f2f0;
        color: #174a43;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .jadwal-title {
        color: #344767;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .jadwal-meta {
        color: #8898aa;
        font-size: 12px;
    }

    .dropdown-toggle-jadwal {
        width: 32px;
        height: 32px;
        border: 1px solid #e1e7e5;
        background: #fff;
        color: #174a43;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .dropdown-toggle-jadwal:hover {
        background: #e8f2f0;
        border-color: #174a43;
    }

    .dropdown-toggle-jadwal[aria-expanded="true"] {
        background: #174a43;
        color: #fff;
        border-color: #174a43;
    }

    .jadwal-detail-wrapper {
        padding: 0 20px 18px 82px;
        background: #fbfcfc;
    }

    .jadwal-detail-card {
        border: 1px solid #e8ecef;
        border-radius: 10px;
        background: #fff;
        padding: 14px;
        height: 100%;
    }

    .jadwal-detail-title {
        color: #344767;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .jadwal-detail-text {
        color: #6c757d;
        font-size: 12px;
    }

    .detail-label {
        color: #9aa5a3;
        font-size: 11px;
        margin-bottom: 2px;
    }

    .detail-value {
        color: #344767;
        font-size: 12px;
        font-weight: 600;
    }

    .action-btn {
        width: 31px;
        height: 31px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        margin-left: 3px;
        font-size: 12px;
    }

    .badge-draft {
        background: #f1f3f5;
        color: #6c757d;
        font-weight: 600;
    }

    .badge-diajukan {
        background: #fff3cd;
        color: #856404;
        font-weight: 600;
    }

    .badge-disetujui {
        background: #e8f2f0;
        color: #174a43;
        font-weight: 600;
    }

    .badge-ditolak {
        background: #f8d7da;
        color: #842029;
        font-weight: 600;
    }

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e8f2f0;
        color: #174a43;
        font-size: 20px;
    }

    @media (max-width: 767px) {
        .page-card-header {
            padding: 15px;
        }

        .header-action {
            width: 100%;
            margin-top: 12px;
        }

        .header-action .btn {
            width: 100%;
        }

        .search-box {
            max-width: 100%;
            width: 100%;
        }

        .filter-select {
            max-width: 100%;
            width: 100%;
        }

        .jadwal-main {
            padding: 15px;
        }

        .jadwal-detail-wrapper {
            padding: 0 15px 15px 15px;
        }

        .jadwal-icon {
            width: 38px;
            height: 38px;
            font-size: 15px;
        }

        .jadwal-title {
            font-size: 13px;
        }
    }
</style>

@endpush


@section('content')

<div class="card page-card">

    {{-- HEADER --}}
    <div class="page-card-header">

        <div class="row align-items-center">

            <div class="col-md-7">

                <div class="page-title-small">
                    Data Jadwal Bulanan
                </div>

                <p class="page-description">
                    Kelola jadwal kegiatan Posyandu berdasarkan periode bulanan.
                </p>

            </div>

            <div class="col-md-5">

                <div class="d-flex justify-content-md-end header-action">

                    <a
                        href="{{ route('jadwal.create') }}"
                        class="btn btn-jade btn-sm">

                        <i class="fas fa-plus me-1"></i>
                        Buat Jadwal

                    </a>

                </div>

            </div>

        </div>

    </div>


    <div class="border-top"></div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="px-3 pt-3">

            <div class="alert alert-success alert-dismissible fade show mb-0">

                <i class="fas fa-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        </div>

    @endif


    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="px-3 pt-3">

            <div class="alert alert-danger alert-dismissible fade show mb-0">

                <i class="fas fa-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        </div>

    @endif


    {{-- FILTER --}}
    <div class="p-3">

        <form
            action="{{ route('jadwal.index') }}"
            method="GET">

            <div class="row align-items-center g-2">

                <div class="col-md-5">

                    <div class="position-relative search-box">

                        <i class="fas fa-search search-icon"></i>

                        <input
                            type="text"
                            id="searchJadwal"
                            class="form-control form-control-sm"
                            placeholder="Cari periode, pembuat, atau Posyandu...">

                    </div>

                </div>


                <div class="col-md-3">

                    <select
                        name="tahun"
                        class="form-select form-select-sm filter-select">

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach($tahunList as $tahun)

                            <option
                                value="{{ $tahun }}"
                                {{ request('tahun') == $tahun ? 'selected' : '' }}>

                                {{ $tahun }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <select
                        name="status"
                        class="form-select form-select-sm filter-select">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="draft"
                            {{ request('status') === 'draft' ? 'selected' : '' }}>

                            Draft

                        </option>

                        <option
                            value="diajukan"
                            {{ request('status') === 'diajukan' ? 'selected' : '' }}>

                            Diajukan

                        </option>

                        <option
                            value="disetujui"
                            {{ request('status') === 'disetujui' ? 'selected' : '' }}>

                            Disetujui

                        </option>

                        <option
                            value="ditolak"
                            {{ request('status') === 'ditolak' ? 'selected' : '' }}>

                            Ditolak

                        </option>

                    </select>

                </div>


                <div class="col-md-1 text-md-end">

                    <button
                        type="submit"
                        class="btn btn-jade btn-sm"
                        title="Terapkan filter">

                        <i class="fas fa-search"></i>

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- LIST HEADER --}}
    <div class="px-3 pb-2">

        <div class="d-flex justify-content-between align-items-center">

            <small class="text-muted">

                Menampilkan
                <strong>{{ $jadwals->total() }}</strong>
                jadwal

            </small>

            @if(request()->hasAny(['tahun', 'status']))

                <a
                    href="{{ route('jadwal.index') }}"
                    class="small text-decoration-none"
                    style="color: #174a43;">

                    <i class="fas fa-rotate-left me-1"></i>
                    Reset filter

                </a>

            @endif

        </div>

    </div>


    {{-- LIST JADWAL --}}
    <div id="jadwalList">

        @forelse($jadwals as $jadwal)

            <div
                class="jadwal-row"
                data-search="{{ strtolower(
                    ($jadwal->periode_label ?? '') . ' ' .
                    ($jadwal->pembuat->name ?? '') . ' ' .
                    $jadwal->status . ' ' .
                    $jadwal->details->pluck('posyandu.nama_posyandu')->implode(' ')
                ) }}">

                {{-- BARIS UTAMA --}}
                <div class="jadwal-main">

                    <div class="d-flex align-items-center justify-content-between gap-3">

                        <div class="d-flex align-items-center flex-grow-1 min-w-0">

                            <div class="jadwal-icon me-3">

                                <i class="fas fa-calendar-days"></i>

                            </div>


                            <div class="min-w-0">

                              <div class="jadwal-title">
                                {{ \Carbon\Carbon::createFromDate(
                                    $jadwal->tahun,
                                    $jadwal->bulan,
                                    1
                                )->translatedFormat('F Y') }}
                            </div>

                                <div class="jadwal-meta">

                                    <span class="me-2">

                                        <i class="fas fa-user me-1"></i>

                                        {{ $jadwal->pembuat->name ?? 'Sistem' }}

                                    </span>

                                    <span>

                                        <i class="fas fa-list me-1"></i>

                                        {{ $jadwal->details->count() }}
                                        detail kegiatan

                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="d-flex align-items-center gap-2">

                            {{-- STATUS --}}
                            @if($jadwal->status === 'draft')

                                <span class="badge badge-draft">
                                    Draft
                                </span>

                            @elseif($jadwal->status === 'diajukan')

                                <span class="badge badge-diajukan">
                                    Diajukan
                                </span>

                            @elseif($jadwal->status === 'disetujui')

                                <span class="badge badge-disetujui">
                                    Disetujui
                                </span>

                            @elseif($jadwal->status === 'ditolak')

                                <span class="badge badge-ditolak">
                                    Ditolak
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ ucfirst($jadwal->status) }}
                                </span>

                            @endif


                            {{-- DROPDOWN --}}
                            <button
                                type="button"
                                class="dropdown-toggle-jadwal"
                                data-bs-toggle="collapse"
                                data-bs-target="#detailJadwal{{ $jadwal->id }}"
                                aria-expanded="false"
                                aria-controls="detailJadwal{{ $jadwal->id }}"
                                title="Lihat detail">

                                <i class="fas fa-chevron-down"></i>

                            </button>

                        </div>

                    </div>

                </div>


                {{-- DETAIL DROPDOWN --}}
                <div
                    id="detailJadwal{{ $jadwal->id }}"
                    class="collapse">

                    <div class="jadwal-detail-wrapper">

                        {{-- CATATAN --}}
                        @if($jadwal->catatan)

                            <div class="mb-3">

                                <div class="detail-label">
                                    Catatan
                                </div>

                                <div class="jadwal-detail-text">

                                    <i class="fas fa-note-sticky me-1"></i>

                                    {{ $jadwal->catatan }}

                                </div>

                            </div>

                        @endif


                        {{-- DETAIL KEGIATAN --}}
                        <div class="row g-3">

                            @forelse($jadwal->details as $detail)

                                <div class="col-md-6 col-xl-4">

                                    <div class="jadwal-detail-card">

                                        <div class="d-flex align-items-start mb-3">

                                            <i
                                                class="fas fa-location-dot me-2 mt-1"
                                                style="color: #174a43;">
                                            </i>

                                            <div>

                                                <div class="jadwal-detail-title">

                                                    {{ $detail->posyandu->nama_posyandu ?? 'Posyandu tidak ditemukan' }}

                                                </div>

                                                <div class="jadwal-detail-text">

                                                    {{ $detail->posyandu->kode_posyandu ?? '-' }}

                                                </div>

<div class="jadwal-detail-text">
    <i class="fas fa-location-dot me-1"></i>
    {{ $detail->posyandu?->wilayah?->nama_wilayah ?? 'Wilayah belum dipilih' }}
</div>

@if($detail->posyandu?->wilayah?->rw)
    <div class="jadwal-detail-text">
        <i class="fas fa-map-pin me-1"></i>
        RW {{ $detail->posyandu->wilayah->rw }}
    </div>
@endif

                                            </div>

                                        </div>


                                        <div class="mb-2">

                                            <div class="detail-label">
                                                Kegiatan
                                            </div>

                                            <div class="detail-value">

                                                {{ $detail->kegiatan->nama_kegiatan ?? '-' }}

                                            </div>

                                        </div>


                                        <div class="mb-2">

                                            <div class="detail-label">
                                                Tanggal
                                            </div>

                                            <div class="detail-value">

                                                {{ optional($detail->tgl_mulai)->translatedFormat('d F Y') }}

                                                @if(
                                                    $detail->tgl_selesai &&
                                                    $detail->tgl_selesai != $detail->tgl_mulai
                                                )

                                                    -
                                                    {{ optional($detail->tgl_selesai)->translatedFormat('d F Y') }}

                                                @endif

                                            </div>

                                        </div>


                                        <div class="mb-3">

                                            <div class="detail-label">
                                                Waktu
                                            </div>

                                            <div class="detail-value">

                                                {{ $detail->jam_mulai }}
                                                -
                                                {{ $detail->jam_selesai }}

                                            </div>

                                        </div>


                                        @if($detail->keterangan)

                                            <div class="mb-3">

                                                <div class="detail-label">
                                                    Keterangan
                                                </div>

                                                <div class="jadwal-detail-text">

                                                    {{ $detail->keterangan }}

                                                </div>

                                            </div>

                                        @endif


                                        {{-- PDF SATU POSYANDU --}}
                                        <a
                                            href="{{ route('jadwal.pdf-one', [
                                                'jadwal' => $jadwal->id,
                                                'posyandu' => $detail->posyandu_id
                                            ]) }}"
                                            class="btn btn-sm btn-outline-danger w-100">

                                            <i class="fas fa-file-pdf me-1"></i>
                                            PDF Posyandu

                                        </a>

                                    </div>

                                </div>

                            @empty

                                <div class="col-12">

                                    <div class="text-muted small">

                                        Belum ada detail kegiatan.

                                    </div>

                                </div>

                            @endforelse

                        </div>


                        {{-- ACTION JADWAL --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">

                            <div class="text-muted small">

                                <i class="fas fa-clock me-1"></i>

                                Diperbarui:
                                {{ $jadwal->updated_at?->format('d-m-Y H:i') }}

                            </div>


                            <div class="d-flex flex-wrap gap-2">

                                {{-- LIHAT --}}
                                <a
                                    href="{{ route('jadwal.show', $jadwal) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    <i class="fas fa-eye me-1"></i>
                                    Lihat

                                </a>


                                {{-- EDIT --}}
                                @if($jadwal->status !== 'disetujui')

                                    <a
                                        href="{{ route('jadwal.edit', $jadwal) }}"
                                        class="btn btn-sm btn-outline-jade">

                                        <i class="fas fa-pen me-1"></i>
                                        Edit

                                    </a>

                                @endif


                                {{-- PDF SEMUA --}}
                                <a
                                    href="{{ route('jadwal.pdf', $jadwal) }}"
                                    class="btn btn-sm btn-outline-danger">

                                    <i class="fas fa-file-pdf me-1"></i>
                                    PDF Semua

                                </a>


                                {{-- DELETE --}}
                                <form
                                    action="{{ route('jadwal.destroy', $jadwal) }}"
                                    method="POST"
                                    class="d-inline delete-form">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger">

                                        <i class="fas fa-trash me-1"></i>
                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="empty-state">

                <div class="empty-state-icon">

                    <i class="fas fa-calendar-xmark"></i>

                </div>

                <h6 class="fw-semibold">
                    Belum Ada Jadwal Bulanan
                </h6>

                <p class="text-muted small mb-3">
                    Silakan buat jadwal bulanan terlebih dahulu.
                </p>

                <a
                    href="{{ route('jadwal.create') }}"
                    class="btn btn-jade btn-sm">

                    <i class="fas fa-plus me-1"></i>
                    Buat Jadwal

                </a>

            </div>

        @endforelse

    </div>


    {{-- PAGINATION --}}
    @if($jadwals->hasPages())

        <div class="px-3 py-3 border-top">

            {{ $jadwals->links() }}

        </div>

    @endif

</div>

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    const searchInput = document.getElementById('searchJadwal');
    const jadwalRows = document.querySelectorAll('.jadwal-row');

    if (searchInput) {

        searchInput.addEventListener('keyup', function () {

            const keyword = this.value.toLowerCase().trim();

            jadwalRows.forEach(function (row) {

                const searchableText =
                    row.getAttribute('data-search') || '';

                row.style.display =
                    searchableText.includes(keyword)
                        ? ''
                        : 'none';

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | UBAH IKON DROPDOWN
    |--------------------------------------------------------------------------
    */

    const dropdownButtons = document.querySelectorAll(
        '.dropdown-toggle-jadwal'
    );

    dropdownButtons.forEach(function (button) {

        const targetSelector =
            button.getAttribute('data-bs-target');

        const target =
            document.querySelector(targetSelector);

        if (!target) {
            return;
        }

        target.addEventListener('shown.bs.collapse', function () {

            button.setAttribute('aria-expanded', 'true');

            const icon =
                button.querySelector('i');

            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }

        });

        target.addEventListener('hidden.bs.collapse', function () {

            button.setAttribute('aria-expanded', 'false');

            const icon =
                button.querySelector('i');

            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | DELETE CONFIRMATION
    |--------------------------------------------------------------------------
    */

    const deleteForms =
        document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            Swal.fire({

                title: 'Hapus Jadwal?',

                text: 'Data jadwal beserta detail kegiatannya akan dihapus dan tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#174a43',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Ya, Hapus',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    }

});
</script>

@endpush