@extends('layouts.app')

@section('title', 'Detail Wilayah')

@section('content')

<div class="page-header">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('wilayah.index') }}">
                    Wilayah
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail
            </li>

        </ol>
    </nav>

    <h1 class="page-title">
        Detail Wilayah
    </h1>

    <p class="page-description">
        Informasi lengkap wilayah dan Posyandu yang berada di dalamnya.
    </p>

</div>

<div class="row">

    <div class="col-lg-8 col-xl-7">

        <div class="card mb-4">

            <div class="card-header px-4 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5
                            class="mb-1 fw-semibold"
                            style="color: var(--jade-dark);"
                        >
                            {{ $wilayah->nama_wilayah }}
                        </h5>

                        <p class="mb-0 small text-muted">
                            Informasi wilayah
                        </p>
                    </div>

                    @if($wilayah->aktif)
                        <span class="badge badge-active rounded-pill px-3 py-2">
                            Aktif
                        </span>
                    @else
                        <span class="badge badge-inactive rounded-pill px-3 py-2">
                            Tidak Aktif
                        </span>
                    @endif

                </div>

            </div>

            <div class="card-body p-4">

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Nama Wilayah
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->nama_wilayah }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            RW
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->rw ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Kelurahan
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->kelurahan ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Kecamatan
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->kecamatan ?: '-' }}
                        </div>

                    </div>

                    <div class="col-12">

                        <div class="small text-muted mb-1">
                            Alamat
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->alamat ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Dibuat
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->created_at?->format('d M Y H:i') ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Terakhir Diperbarui
                        </div>

                        <div class="fw-semibold">
                            {{ $wilayah->updated_at?->format('d M Y H:i') ?: '-' }}
                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white border-top px-4 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <a
                        href="{{ route('wilayah.index') }}"
                        class="btn btn-light border"
                    >
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('wilayah.edit', $wilayah) }}"
                            class="btn btn-jade"
                        >
                            <i class="fas fa-pen me-1"></i>
                            Edit
                        </a>

                        @if(!$wilayah->posyandus()->exists())

                            <form
                                action="{{ route('wilayah.destroy', $wilayah) }}"
                                method="POST"
                                class="delete-form"
                                data-name="{{ $wilayah->nama_wilayah }}"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger"
                                >
                                    <i class="fas fa-trash me-1"></i>
                                    Hapus
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-header px-4 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5
                            class="mb-1 fw-semibold"
                            style="color: var(--jade-dark);"
                        >
                            Posyandu
                        </h5>

                        <p class="mb-0 small text-muted">
                            Daftar Posyandu dalam wilayah ini.
                        </p>
                    </div>

                    <span class="badge bg-light text-dark border">
                        {{ $wilayah->posyandus->count() }} Posyandu
                    </span>

                </div>

            </div>

            <div class="card-body p-0">

                @if($wilayah->posyandus->count())

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Posyandu</th>
                                    <th>Ketua</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($wilayah->posyandus as $posyandu)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <span class="fw-semibold">
                                                {{ $posyandu->kode_posyandu }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $posyandu->nama_posyandu }}
                                        </td>

                                        <td>
                                            {{ $posyandu->ketua ?: '-' }}
                                        </td>

                                        <td>

                                            @if($posyandu->aktif)
                                                <span class="badge badge-active rounded-pill px-3 py-2">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-inactive rounded-pill px-3 py-2">
                                                    Tidak Aktif
                                                </span>
                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <div
                            class="mb-3"
                            style="color: #aab7bb; font-size: 32px;"
                        >
                            <i class="fas fa-house-medical"></i>
                        </div>

                        <div class="fw-semibold text-muted">
                            Belum ada Posyandu
                        </div>

                        <div class="small text-muted mt-1">
                            Belum terdapat Posyandu yang terdaftar pada wilayah ini.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection