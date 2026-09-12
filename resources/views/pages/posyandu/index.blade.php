@extends('layouts.app')

@section('title', 'Posyandu')

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

    .table thead th {
        white-space: nowrap;
    }

    .table tbody td {
        white-space: nowrap;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .badge-active {
        background: #e8f2f0;
        color: #174a43;
        font-weight: 600;
    }

    .badge-inactive {
        background: #f1f3f5;
        color: #6c757d;
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

        .table {
            min-width: 950px;
        }

    }

</style>

@endpush

@section('content')

<div class="card page-card">

    <div class="page-card-header">

        <div class="row align-items-center">

            <div class="col-md-7">

                <div class="page-title-small">
                    Data Posyandu
                </div>

                <p class="page-description">
                    Kelola data Posyandu yang digunakan dalam sistem penjadwalan.
                </p>

            </div>

            <div class="col-md-5">

                <div class="d-flex justify-content-md-end header-action">

                    <a
                        href="{{ route('posyandu.create') }}"
                        class="btn btn-jade btn-sm">

                        <i class="fas fa-plus me-1"></i>

                        Tambah Posyandu

                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="border-top"></div>

    <div class="p-3">

        <div class="row align-items-center">

            <div class="col-md-8">

                <div class="position-relative search-box">

                    <i class="fas fa-search search-icon"></i>

                    <input
                        type="text"
                        id="searchPosyandu"
                        class="form-control form-control-sm"
                        placeholder="Cari kode, nama, wilayah..."
                    >

                </div>

            </div>

            <div class="col-md-4 text-md-end mt-2 mt-md-0">

                <small class="text-muted">

                    Menampilkan
                    <strong>{{ $posyandus->total() }}</strong>
                    data

                </small>

            </div>

        </div>

    </div>

    <div class="table-responsive">

        <table
            class="table table-hover align-items-center mb-0"
            id="posyanduTable">

            <thead>

                <tr>

                    <th class="ps-4">
                        No
                    </th>

                    <th>
                        Kode Posyandu
                    </th>

                    <th>
                        Nama Posyandu
                    </th>

                    <th>
                        Wilayah
                    </th>

                    <th>
                        Ketua
                    </th>

                    <th>
                        Kontak
                    </th>

                    <th>
                        Status
                    </th>

                    <th class="text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($posyandus as $index => $posyandu)

                    <tr>

                        <td class="ps-4">

                            {{ $posyandus->firstItem() + $index }}

                        </td>

                        <td>

                            <div class="fw-semibold text-dark">

                                {{ $posyandu->kode_posyandu }}

                            </div>

                        </td>

                        <td>

                            <div class="fw-semibold text-dark">

                                {{ $posyandu->nama_posyandu }}

                            </div>

                        </td>

                        <td>

                            <div class="fw-semibold text-dark">

                                {{ $posyandu->wilayah->nama_wilayah }}

                            </div>

                            @if($posyandu->wilayah->rw)

                                <small class="text-muted">

                                    {{ $posyandu->wilayah->rw }}

                                </small>

                            @endif

                        </td>

                        <td>

                            {{ $posyandu->ketua ?: '-' }}

                        </td>

                        <td>

                            {{ $posyandu->kontak ?: '-' }}

                        </td>

                        <td>

                            @if($posyandu->aktif)

                                <span class="badge badge-active">
                                    Aktif
                                </span>

                            @else

                                <span class="badge badge-inactive">
                                    Tidak Aktif
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <a
                                href="{{ route('posyandu.show', $posyandu) }}"
                                class="btn btn-outline-secondary action-btn"
                                title="Lihat">

                                <i class="fas fa-eye"></i>

                            </a>

                            <a
                                href="{{ route('posyandu.edit', $posyandu) }}"
                                class="btn btn-outline-jade action-btn"
                                title="Edit">

                                <i class="fas fa-pen"></i>

                            </a>

                            <form
                                action="{{ route('posyandu.destroy', $posyandu) }}"
                                method="POST"
                                class="d-inline delete-form"
                                data-name="{{ $posyandu->nama_posyandu }}">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger action-btn"
                                    title="Hapus">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">

                            <div class="empty-state">

                                <div class="empty-state-icon">

                                    <i class="fas fa-house-medical"></i>

                                </div>

                                <h6 class="fw-semibold">
                                    Belum Ada Data Posyandu
                                </h6>

                                <p class="text-muted small mb-3">
                                    Silakan tambahkan Posyandu terlebih dahulu.
                                </p>

                                <a
                                    href="{{ route('posyandu.create') }}"
                                    class="btn btn-jade btn-sm">

                                    <i class="fas fa-plus me-1"></i>

                                    Tambah Posyandu

                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($posyandus->hasPages())

        <div class="px-3 py-3 border-top">

            {{ $posyandus->links() }}

        </div>

    @endif

</div>

@endsection

@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const search =
            document.getElementById(
                'searchPosyandu'
            );

        const table =
            document.getElementById(
                'posyanduTable'
            );

        if (search && table) {

            search.addEventListener(
                'keyup',
                function () {

                    const keyword =
                        this.value.toLowerCase();

                    const rows =
                        table.querySelectorAll(
                            'tbody tr'
                        );

                    rows.forEach(function (row) {

                        const text =
                            row.textContent
                                .toLowerCase();

                        row.style.display =
                            text.includes(keyword)
                                ? ''
                                : 'none';

                    });

                }
            );

        }

    }
);

</script>

@endpush