@extends('layouts.app')

@section('title', 'Detail Posyandu')

@section('content')

<div class="page-header">

</div>

<div class="row">

    <div class="col-lg-8 col-xl-7">

        <div class="card mb-4">

            <div class="card-header px-4 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div
                            class="small text-muted mb-1"
                        >
                            {{ $posyandu->kode_posyandu }}
                        </div>

                        <h5
                            class="mb-1 fw-semibold"
                            style="color: var(--jade-dark);"
                        >
                            {{ $posyandu->nama_posyandu }}
                        </h5>

                        <p class="mb-0 small text-muted">
                            {{ $posyandu->wilayah->nama_wilayah }}
                        </p>

                    </div>

                    @if($posyandu->aktif)

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
                            Kode Posyandu
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->kode_posyandu }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Nama Posyandu
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->nama_posyandu }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Wilayah
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->wilayah->nama_wilayah }}
                        </div>

                        @if($posyandu->wilayah->rw)
                            <small class="text-muted">
                                {{ $posyandu->wilayah->rw }}
                            </small>
                        @endif

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Ketua Posyandu
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->ketua ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Kontak
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->kontak ?: '-' }}
                        </div>

                    </div>

                    <div class="col-12">

                        <div class="small text-muted mb-1">
                            Alamat
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->alamat ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Dibuat
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->created_at?->format('d M Y H:i') ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="small text-muted mb-1">
                            Terakhir Diperbarui
                        </div>

                        <div class="fw-semibold">
                            {{ $posyandu->updated_at?->format('d M Y H:i') ?: '-' }}
                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white border-top px-4 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <a
                        href="{{ route('posyandu.index') }}"
                        class="btn btn-light border"
                    >
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('posyandu.edit', $posyandu) }}"
                            class="btn btn-jade"
                        >
                            <i class="fas fa-pen me-1"></i>
                            Edit
                        </a>

                        @if(
                            !$posyandu->hariOperasionals->count() &&
                            !$posyandu->jadwalDetails->count()
                        )

                            <form
                                action="{{ route('posyandu.destroy', $posyandu) }}"
                                method="POST"
                                class="delete-form"
                                data-name="{{ $posyandu->nama_posyandu }}"
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
                            Hari Operasional
                        </h5>

                        <p class="mb-0 small text-muted">
                            Pengaturan hari dan jam operasional Posyandu.
                        </p>

                    </div>

                    <span class="badge bg-light text-dark border">
                        {{ $posyandu->hariOperasionals->count() }} Hari
                    </span>

                </div>

            </div>

            <div class="card-body p-0">

                @if($posyandu->hariOperasionals->count())

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Hari</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                @php
                                    $namaHari = [
                                        1 => 'Senin',
                                        2 => 'Selasa',
                                        3 => 'Rabu',
                                        4 => 'Kamis',
                                        5 => 'Jumat',
                                        6 => 'Sabtu',
                                        7 => 'Minggu',
                                    ];
                                @endphp

                                @foreach($posyandu->hariOperasionals->sortBy('hari') as $hari)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="fw-semibold">
                                            {{ $namaHari[$hari->hari] ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $hari->jam_mulai ? substr($hari->jam_mulai, 0, 5) : '-' }}
                                        </td>

                                        <td>
                                            {{ $hari->jam_selesai ? substr($hari->jam_selesai, 0, 5) : '-' }}
                                        </td>

                                        <td>

                                            @if($hari->aktif)

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
                            <i class="fas fa-calendar-xmark"></i>
                        </div>

                        <div class="fw-semibold text-muted">
                            Belum ada hari operasional
                        </div>

                        <div class="small text-muted mt-1">
                            Pengaturan hari operasional belum dibuat.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection