@extends('layouts.app')

@section('title', 'Laporan Jadwal')

@push('styles')
<style>
    .laporan-page {
        width: 100%;
    }

    .laporan-filter-card {
        overflow: hidden;
    }

    .laporan-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 24px 28px;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .laporan-header h1 {
        margin: 0;
        color: #334155;
        font-size: 19px;
        font-weight: 700;
    }

    .laporan-header p {
        margin: 6px 0 0;
        color: #94a3b8;
        font-size: 13px;
    }

    .laporan-filter {
        padding: 24px 28px;
        background: #ffffff;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px 24px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        min-height: 43px;
        padding: 9px 12px;
        border: 1px solid #dbe2e8;
        border-radius: 8px;
        background: #ffffff;
        color: #334155;
        font-size: 14px;
        outline: none;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #64748b;
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.12);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
    }

    .filter-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .filter-button-primary {
        border: 1px solid #334155;
        background: #334155;
        color: #ffffff;
    }

    .filter-button-primary:hover {
        background: #1e293b;
        color: #ffffff;
    }

    .filter-button-secondary {
        border: 1px solid #dbe2e8;
        background: #ffffff;
        color: #475569;
    }

    .filter-button-secondary:hover {
        background: #f8fafc;
        color: #334155;
    }

    .laporan-result {
        margin-top: 24px;
    }

    @media (max-width: 1000px) {
        .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .laporan-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }

        .laporan-filter {
            padding: 20px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .filter-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .filter-button {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="laporan-page">

    {{-- FILTER LAPORAN --}}
    <div class="card laporan-filter-card">

        <div class="laporan-header">
            <div>
                <h1>Laporan Jadwal</h1>

                <p>
                    Filter laporan jadwal berdasarkan tanggal dan kategori.
                </p>
            </div>
        </div>

        <div class="laporan-filter">

            <form
                action="{{ route('laporan-jadwal.index') }}"
                method="GET"
            >

                <div class="filter-grid">

                    {{-- DARI TANGGAL --}}
                    <div class="filter-group">
                        <label for="mulai">
                            Dari Tanggal
                        </label>

                        <input
                            type="date"
                            name="mulai"
                            id="mulai"
                            value="{{ $mulai ?? '' }}"
                        >
                    </div>

                    {{-- SAMPAI TANGGAL --}}
                    <div class="filter-group">
                        <label for="sampai">
                            Sampai Tanggal
                        </label>

                        <input
                            type="date"
                            name="sampai"
                            id="sampai"
                            value="{{ $sampai ?? '' }}"
                        >
                    </div>

                    {{-- STATUS --}}
                    <div class="filter-group">
                        <label for="status">
                            Status Jadwal
                        </label>

                        <select name="status" id="status">
                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="draft"
                                @selected(($status ?? '') === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="diajukan"
                                @selected(($status ?? '') === 'diajukan')
                            >
                                Diajukan
                            </option>

                            <option
                                value="disetujui"
                                @selected(($status ?? '') === 'disetujui')
                            >
                                Disetujui
                            </option>

                            <option
                                value="ditolak"
                                @selected(($status ?? '') === 'ditolak')
                            >
                                Ditolak
                            </option>
                        </select>
                    </div>

                  {{-- POSYANDU DAN WILAYAH --}}
                <div class="filter-group">
                    <label for="posyandu_id">
                        Posyandu
                    </label>

                    <select name="posyandu_id" id="posyandu_id">

                        <option value="">
                            Semua Posyandu
                        </option>

                        @foreach ($posyandus as $posyandu)
                            <option
                                value="{{ $posyandu->id }}"
                                @selected(
                                    (string) ($posyanduId ?? '')
                                    ===
                                    (string) $posyandu->id
                                )
                            >
                                {{ $posyandu->nama_posyandu }}
                                —
                                {{ $posyandu->wilayah->nama_wilayah ?? 'Wilayah belum diatur' }}
                            </option>
                        @endforeach

                    </select>
                </div>

                    {{-- KEGIATAN --}}
                    <div class="filter-group">
                        <label for="kegiatan_id">
                            Kegiatan
                        </label>

                        <select name="kegiatan_id" id="kegiatan_id">
                            <option value="">
                                Semua Kegiatan
                            </option>

                            @foreach ($kegiatans as $kegiatan)
                                <option
                                    value="{{ $kegiatan->id }}"
                                    @selected(
                                        (string) ($kegiatanId ?? '')
                                        ===
                                        (string) $kegiatan->id
                                    )
                                >
                                    {{ $kegiatan->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="filter-button filter-button-primary"
                    >
                        <i class="fas fa-filter me-2"></i>
                        Terapkan Filter
                    </button>

                    <a
                        href="{{ route('laporan-jadwal.index') }}"
                        class="filter-button filter-button-secondary"
                    >
                        <i class="fas fa-rotate-left me-2"></i>
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>

    {{-- HASIL LAPORAN --}}
    <div class="laporan-result">
        @include('pages.laporan-jadwal.result')
    </div>

</div>
@endsection