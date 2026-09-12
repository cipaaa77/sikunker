@extends('layouts.app')

@section('title', 'Jadwal Bulanan')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Jadwal Bulanan</h4>
            <p class="text-muted mb-0">
                Kelola jadwal kegiatan Posyandu berdasarkan periode bulanan.
            </p>
        </div>

        <a href="{{ route('jadwal.create') }}" class="btn btn-jade">
            <i class="fa-solid fa-plus me-1"></i>
            Buat Jadwal
        </a>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="row align-items-center mb-3">

                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-search text-muted"></i>
                        </span>

                        <input
                            type="text"
                            id="searchJadwal"
                            class="form-control"
                            placeholder="Cari tahun, bulan, status..."
                        >
                    </div>
                </div>

                <div class="col-md-7 text-md-end mt-2 mt-md-0">
                    <span class="text-muted small">
                        Total {{ $jadwals->total() }} jadwal
                    </span>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0" id="jadwalTable">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Periode</th>
                            <th>Catatan</th>
                            <th>Dibuat Oleh</th>
                            <th width="12%">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($jadwals as $index => $jadwal)

                            <tr>

                                <td>
                                    {{ $jadwals->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::create()
                                            ->month($jadwal->bulan)
                                            ->translatedFormat('F') }}
                                        {{ $jadwal->tahun }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $jadwal->details_count ?? $jadwal->details()->count() }}
                                        kegiatan
                                    </small>
                                </td>

                                <td>
                                    @if($jadwal->catatan)
                                        {{ \Illuminate\Support\Str::limit($jadwal->catatan, 50) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $jadwal->dibuatOleh->name ?? '-' }}
                                </td>

                                <td>
                                    @switch($jadwal->status)

                                        @case('draft')
                                            <span class="badge text-bg-secondary">
                                                Draft
                                            </span>
                                            @break

                                        @case('diajukan')
                                            <span class="badge text-bg-warning">
                                                Diajukan
                                            </span>
                                            @break

                                        @case('disetujui')
                                            <span class="badge text-bg-success">
                                                Disetujui
                                            </span>
                                            @break

                                        @case('ditolak')
                                            <span class="badge text-bg-danger">
                                                Ditolak
                                            </span>
                                            @break

                                        @case('final')
                                            <span class="badge bg-jade">
                                                Final
                                            </span>
                                            @break

                                        @default
                                            <span class="badge text-bg-secondary">
                                                {{ ucfirst($jadwal->status) }}
                                            </span>

                                    @endswitch
                                </td>

                                <td class="text-center">

                                    <div class="d-flex justify-content-center flex-wrap gap-1">

                                        {{-- Lihat --}}
                                        <a
                                            href="{{ route('jadwal.show', $jadwal) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Lihat"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        @if($jadwal->status !== 'final')

                                            <a
                                                href="{{ route('jadwal.edit', $jadwal) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Edit"
                                            >
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                        @endif

                                        {{-- Generate --}}
                                        @if(in_array($jadwal->status, ['draft', 'ditolak']))

                                            <form
                                                action="{{ route('jadwal.generate', $jadwal) }}"
                                                method="POST"
                                                class="confirm-submit"
                                                data-title="Generate jadwal?"
                                                data-text="Detail jadwal akan dibuat berdasarkan hari operasional Posyandu."
                                                data-confirm="Ya, Generate"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Generate"
                                                >
                                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                                </button>
                                            </form>

                                        @endif

                                        {{-- PDF --}}
                                        <a
                                            href="{{ route('jadwal.pdf', $jadwal) }}"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Download PDF"
                                            target="_blank"
                                        >
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>

                                        {{-- Excel --}}
                                        <a
                                            href="{{ route('jadwal.excel', $jadwal) }}"
                                            class="btn btn-sm btn-outline-success"
                                            title="Download Excel"
                                        >
                                            <i class="fa-solid fa-file-excel"></i>
                                        </a>

                                        {{-- Hapus --}}
                                        @if(in_array($jadwal->status, ['draft', 'ditolak']))

                                            <form
                                                action="{{ route('jadwal.destroy', $jadwal) }}"
                                                method="POST"
                                                class="delete-form"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus"
                                                >
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-5">

                                    <div class="text-muted">
                                        <i class="fa-regular fa-calendar-xmark fa-2x mb-3"></i>

                                        <div class="fw-semibold">
                                            Belum ada jadwal bulanan.
                                        </div>

                                        <small>
                                            Klik tombol "Buat Jadwal" untuk membuat jadwal baru.
                                        </small>
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($jadwals->hasPages())

                <div class="d-flex justify-content-between align-items-center mt-4">

                    <div class="text-muted small">
                        Menampilkan
                        {{ $jadwals->firstItem() }}
                        -
                        {{ $jadwals->lastItem() }}
                        dari
                        {{ $jadwals->total() }}
                    </div>

                    <div>
                        {{ $jadwals->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .btn-jade {
        background: var(--jade);
        border-color: var(--jade);
        color: #fff;
    }

    .btn-jade:hover {
        background: var(--jade-dark);
        border-color: var(--jade-dark);
        color: #fff;
    }

    .bg-jade {
        background: var(--jade) !important;
        color: #fff;
    }

    #jadwalTable th {
        font-size: 13px;
        color: #555;
        font-weight: 600;
        white-space: nowrap;
    }

    #jadwalTable td {
        font-size: 14px;
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: var(--jade);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--jade);
        border-color: var(--jade);
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('searchJadwal')?.addEventListener('keyup', function () {

        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#jadwalTable tbody tr');

        rows.forEach(row => {
            row.style.display =
                row.innerText.toLowerCase().includes(keyword)
                    ? ''
                    : 'none';
        });

    });
</script>
@endpush