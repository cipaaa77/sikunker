@extends('layouts.app')

@section('title', 'Wilayah')
@section('breadcrumb', 'Wilayah')
@section('page-title', 'Data Wilayah')


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
            min-width: 850px;
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
                    Data Wilayah
                </div>

                <p class="page-description">
                    Kelola data wilayah yang digunakan dalam sistem Posyandu.
                </p>

            </div>


            <div class="col-md-5">

                <div class="d-flex justify-content-md-end header-action">

                    <a
                        href="{{ route('wilayah.create') }}"
                        class="btn btn-jade btn-sm">

                        <i class="fas fa-plus me-1"></i>

                        Tambah Wilayah

                    </a>

                </div>

            </div>

        </div>

    </div>


    <div class="border-top"></div>


    {{-- FILTER --}}

    <div class="p-3">

        <div class="row align-items-center">

            <div class="col-md-8">

                <div class="position-relative search-box">

                    <i class="fas fa-search search-icon"></i>

                    <input
                        type="text"
                        id="searchWilayah"
                        class="form-control form-control-sm"
                        placeholder="Cari wilayah, RW, kelurahan...">

                </div>

            </div>


            <div class="col-md-4 text-md-end mt-2 mt-md-0">

                <small class="text-muted">

                    Menampilkan
                    <strong>{{ $wilayahs->total() }}</strong>
                    data

                </small>

            </div>

        </div>

    </div>


    {{-- TABLE --}}

    <div class="table-responsive">

        <table
            class="table table-hover align-items-center mb-0"
            id="wilayahTable">

            <thead>

                <tr>

                    <th class="ps-4">
                        No
                    </th>

                    <th>
                        Nama Wilayah
                    </th>

                    <th>
                        RW
                    </th>

                    <th>
                        Kelurahan
                    </th>

                    <th>
                        Kecamatan
                    </th>

                    <th>
                        Alamat
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

                @forelse($wilayahs as $index => $wilayah)

                    <tr>


                        {{-- NO --}}

                        <td class="ps-4">

                            {{ $wilayahs->firstItem() + $index }}

                        </td>


                        {{-- NAMA --}}

                        <td>

                            <div class="fw-semibold text-dark">

                                {{ $wilayah->nama_wilayah }}

                            </div>

                        </td>


                        {{-- RW --}}

                        <td>

                            {{ $wilayah->rw ?: '-' }}

                        </td>


                        {{-- KELURAHAN --}}

                        <td>

                            {{ $wilayah->kelurahan ?: '-' }}

                        </td>


                        {{-- KECAMATAN --}}

                        <td>

                            {{ $wilayah->kecamatan ?: '-' }}

                        </td>


                        {{-- ALAMAT --}}

                        <td>

                            <span
                                class="text-muted"
                                title="{{ $wilayah->alamat }}">

                                {{ $wilayah->alamat
                                    ? Str::limit($wilayah->alamat, 35)
                                    : '-' }}

                            </span>

                        </td>


                        {{-- STATUS --}}

                        <td>

                            @if($wilayah->aktif)

                                <span class="badge badge-active">
                                    Aktif
                                </span>

                            @else

                                <span class="badge badge-inactive">
                                    Tidak Aktif
                                </span>

                            @endif

                        </td>


                        {{-- ACTION --}}

                        <td class="text-center">


                            {{-- SHOW --}}

                            <a
                                href="{{ route('wilayah.show', $wilayah) }}"
                                class="btn btn-outline-secondary action-btn"
                                title="Lihat">

                                <i class="fas fa-eye"></i>

                            </a>


                            {{-- EDIT --}}

                            <a
                                href="{{ route('wilayah.edit', $wilayah) }}"
                                class="btn btn-outline-jade action-btn"
                                title="Edit">

                                <i class="fas fa-pen"></i>

                            </a>


                            {{-- DELETE --}}

                            <form
                                action="{{ route('wilayah.destroy', $wilayah) }}"
                                method="POST"
                                class="d-inline delete-form">

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

                                    <i class="fas fa-map-location-dot"></i>

                                </div>

                                <h6 class="fw-semibold">
                                    Belum Ada Data Wilayah
                                </h6>

                                <p class="text-muted small mb-3">
                                    Silakan tambahkan wilayah terlebih dahulu.
                                </p>

                                <a
                                    href="{{ route('wilayah.create') }}"
                                    class="btn btn-jade btn-sm">

                                    <i class="fas fa-plus me-1"></i>

                                    Tambah Wilayah

                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    @if($wilayahs->hasPages())

        <div class="px-3 py-3 border-top">

            {{ $wilayahs->links() }}

        </div>

    @endif


</div>


@endsection


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =================================================
           SEARCH
        ================================================= */

        const search =
            document.getElementById(
                'searchWilayah'
            );


        const table =
            document.getElementById(
                'wilayahTable'
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


        /* =================================================
           DELETE CONFIRMATION
        ================================================= */

        const forms =
            document.querySelectorAll(
                '.delete-form'
            );


        forms.forEach(function (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();


                    Swal.fire({

                        title:
                            'Hapus Wilayah?',

                        text:
                            'Data wilayah yang dihapus tidak dapat dikembalikan.',

                        icon:
                            'warning',

                        showCancelButton:
                            true,

                        confirmButtonColor:
                            '#174a43',

                        cancelButtonColor:
                            '#6c757d',

                        confirmButtonText:
                            'Ya, Hapus',

                        cancelButtonText:
                            'Batal',

                        reverseButtons:
                            true

                    }).then(function (result) {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                }
            );

        });


    }
);

</script>

@endpush