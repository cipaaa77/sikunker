@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>

            <a
                href="{{ route('jadwal.index') }}"
                class="text-decoration-none text-muted"
            >
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali
            </a>

            <h4 class="fw-bold mt-3 mb-1 text-white">
                Detail Jadwal Bulanan
            </h4>

            <p class="text-muted mb-0">
                {{ \Carbon\Carbon::create()
                    ->month($jadwal->bulan)
                    ->translatedFormat('F') }}
                {{ $jadwal->tahun }}
            </p>

        </div>

        <div class="d-flex gap-2">

            @if($jadwal->status !== 'final')
            <!-- ini aku masih belum tahu nnti tak revisi -->
                <a
                    href="{{ route('jadwal.edit', $jadwal) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="fa-solid fa-pen me-1"></i>
                    Edit
                </a>

            @endif

          

        </div>

    </div>


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted">
                        Periode
                    </small>

                    <div class="fw-semibold mt-1">
                        {{ \Carbon\Carbon::create()
                            ->month($jadwal->bulan)
                            ->translatedFormat('F') }}
                        {{ $jadwal->tahun }}
                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div class="mt-1">

                        @if($jadwal->status === 'draft')
                            <span class="badge text-bg-secondary">
                                Draft
                            </span>
                        @elseif($jadwal->status === 'diajukan')
                            <span class="badge text-bg-warning">
                                Diajukan
                            </span>
                        @elseif($jadwal->status === 'disetujui')
                            <span class="badge text-bg-success">
                                Disetujui
                            </span>
                        @elseif($jadwal->status === 'ditolak')
                            <span class="badge text-bg-danger">
                                Ditolak
                            </span>
                        @else
                            <span class="badge bg-jade">
                                Final
                            </span>
                        @endif

                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Dibuat Oleh
                    </small>

                    <div class="fw-semibold mt-1">
                        {{ $jadwal->dibuatOleh->name ?? '-' }}
                    </div>

                </div>

                <div class="col-md-3">

                    <small class="text-muted">
                        Jumlah Kegiatan
                    </small>

                    <div class="fw-semibold mt-1">
                        {{ $jadwal->details->count() }}
                    </div>

                </div>

            </div>

            @if($jadwal->catatan)

                <hr>

                <small class="text-muted">
                    Catatan
                </small>

                <div class="mt-1">
                    {{ $jadwal->catatan }}
                </div>

            @endif

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h6 class="fw-bold mb-1">
                Daftar Kegiatan
            </h6>

            <p class="text-muted small mb-0">
                Detail kegiatan pada periode jadwal.
            </p>

        </div>

        <div class="card-body px-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Posyandu</th>
                            <th>Wilayah</th>
                            <th>Kegiatan</th>
                            <th>Waktu</th>
                            <th>Tipe</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($jadwal->details as $index => $detail)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($detail->tgl_mulai)
                                        ->translatedFormat('d F Y') }}
                                </td>

                                <td>
                                    {{ $detail->posyandu->nama_posyandu ?? '-' }}
                                </td>

                                <td>
                                    {{ $detail->posyandu->wilayah->nama_wilayah ?? '-' }}
                                </td>

                                <td>
                                    {{ $detail->kegiatan->nama_kegiatan ?? '-' }}
                                </td>

                                <td>
                                    @if($detail->jam_mulai && $detail->jam_selesai)
                                        {{ substr($detail->jam_mulai, 0, 5) }}
                                        -
                                        {{ substr($detail->jam_selesai, 0, 5) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $detail->tipe_kegiatan }}
                                    </span>
                                </td>

                                <td>

                                    @if($detail->status === 'terjadwal')

                                        <span class="badge text-bg-primary">
                                            Terjadwal
                                        </span>

                                    @elseif($detail->status === 'selesai')

                                        <span class="badge text-bg-success">
                                            Selesai
                                        </span>

                                    @else

                                        <span class="badge text-bg-danger">
                                            Dibatalkan
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    Belum ada detail kegiatan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .bg-jade {
        background: var(--jade) !important;
        color: #fff;
    }
</style>
@endpush