@extends('layouts.app')

@section('title', 'Atur Jadwal Bulanan')

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
                Edit Jadwal Bulanan
            </h4>

            <p class="text-muted mb-0 text-white">
                Periode
                <strong text-white>
                    {{ \Carbon\Carbon::create()->month($jadwal->bulan)->translatedFormat('F') }}
                    {{ $jadwal->tahun }}
                </strong>
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('jadwal.show', $jadwal) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fa-solid fa-eye me-1"></i>
                Lihat
            </a>

            <form
                action="{{ route('jadwal.generate', $jadwal) }}"
                method="POST"
                class="confirm-submit"
                data-title="Generate jadwal?"
                data-text="Jadwal yang ada akan diganti dengan hasil generate."
                data-confirm="Ya, Generate"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-warning"
                >
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
                    Generate
                </button>
            </form>

        </div>

    </div>

    <form
        action="{{ route('jadwal.update', $jadwal) }}"
        method="POST"
        id="jadwalForm"
    >
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-1">
                    Informasi Jadwal
                </h6>
            </div>

            <div class="card-body px-4">

                <div class="row">

                    <div class="col-lg-8 col-xl-7">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Periode
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ \Carbon\Carbon::create()->month($jadwal->bulan)->translatedFormat('F') }} {{ $jadwal->tahun }}"
                                disabled
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <div>
                                @if($jadwal->status === 'draft')
                                    <span class="badge text-bg-secondary">Draft</span>
                                @elseif($jadwal->status === 'diajukan')
                                    <span class="badge text-bg-warning">Diajukan</span>
                                @elseif($jadwal->status === 'disetujui')
                                    <span class="badge text-bg-success">Disetujui</span>
                                @elseif($jadwal->status === 'ditolak')
                                    <span class="badge text-bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-jade">Final</span>
                                @endif
                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Catatan
                            </label>

                            <textarea
                                name="catatan"
                                rows="3"
                                class="form-control"
                            >{{ old('catatan', $jadwal->catatan) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="fw-bold mb-1">
                            Detail Jadwal
                        </h6>

                        <p class="text-muted small mb-0">
                            Atur kegiatan Posyandu pada periode tersebut.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-jade"
                        id="addDetail"
                    >
                        <i class="fa-solid fa-plus me-1"></i>
                        Tambah
                    </button>

                </div>

            </div>

            <div class="card-body px-4">

                <div id="detailContainer">

                    @forelse($jadwal->details as $index => $detail)

                        <div class="detail-item border rounded-3 p-3 mb-3">

                            <div class="d-flex justify-content-between mb-3">

                                <strong>
                                    Kegiatan #{{ $index + 1 }}
                                </strong>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger remove-detail"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </div>

                            <input
                                type="hidden"
                                name="details[{{ $index }}][id]"
                                value="{{ $detail->id }}"
                            >

                            <div class="row g-3">

                                <div class="col-lg-4">

                                    <label class="form-label">
                                        Posyandu
                                    </label>

                                    <select
                                        name="details[{{ $index }}][posyandu_id]"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Pilih Posyandu
                                        </option>

                                        @foreach($posyandus as $posyandu)

                                            <option
                                                value="{{ $posyandu->id }}"
                                                {{ $detail->posyandu_id == $posyandu->id ? 'selected' : '' }}
                                            >
                                                {{ $posyandu->nama_posyandu }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-lg-4">

                                    <label class="form-label">
                                        Kegiatan
                                    </label>

                                    <select
                                        name="details[{{ $index }}][kegiatan_id]"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Pilih Kegiatan
                                        </option>

                                        @foreach($kegiatans as $kegiatan)

                                            <option
                                                value="{{ $kegiatan->id }}"
                                                {{ $detail->kegiatan_id == $kegiatan->id ? 'selected' : '' }}
                                            >
                                                {{ $kegiatan->nama_kegiatan }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-lg-4">

                                    <label class="form-label">
                                        Tipe
                                    </label>

                                    <select
                                        name="details[{{ $index }}][tipe_kegiatan]"
                                        class="form-select"
                                        required
                                    >
                                        <option
                                            value="DG"
                                            {{ $detail->tipe_kegiatan === 'DG' ? 'selected' : '' }}
                                        >
                                            DG
                                        </option>

                                        <option
                                            value="LG"
                                            {{ $detail->tipe_kegiatan === 'LG' ? 'selected' : '' }}
                                        >
                                            LG
                                        </option>
                                    </select>

                                </div>

                                <div class="col-lg-3">

                                    <label class="form-label">
                                        Tanggal Mulai
                                    </label>

                                    <input
                                        type="date"
                                        name="details[{{ $index }}][tgl_mulai]"
                                        value="{{ $detail->tgl_mulai?->format('Y-m-d') }}"
                                        class="form-control"
                                        required
                                    >

                                </div>

                                <div class="col-lg-3">

                                    <label class="form-label">
                                        Tanggal Selesai
                                    </label>

                                    <input
                                        type="date"
                                        name="details[{{ $index }}][tgl_selesai]"
                                        value="{{ $detail->tgl_selesai?->format('Y-m-d') }}"
                                        class="form-control"
                                        required
                                    >

                                </div>

                                <div class="col-lg-3">

                                    <label class="form-label">
                                        Jam Mulai
                                    </label>
                                    <input
                                        type="time"
                                        name="details[{{ $index }}][jam_mulai]"
                                        value="{{ $detail->jam_mulai ? \Carbon\Carbon::parse($detail->jam_mulai)->format('H:i') : '' }}"
                                        class="form-control"
                                    >

                                </div>

                                <div class="col-lg-3">

                                    <label class="form-label">
                                        Jam Selesai
                                    </label>

                               <input
                                    type="time"
                                    name="details[{{ $index }}][jam_selesai]"
                                    value="{{ $detail->jam_selesai ? \Carbon\Carbon::parse($detail->jam_selesai)->format('H:i') : '' }}"
                                    class="form-control"
                                >

                                </div>

                                <div class="col-lg-4">

                                    <label class="form-label">
                                        Status
                                    </label>

                                    <select
                                        name="details[{{ $index }}][status]"
                                        class="form-select"
                                    >

                                        <option
                                            value="terjadwal"
                                            {{ $detail->status === 'terjadwal' ? 'selected' : '' }}
                                        >
                                            Terjadwal
                                        </option>

                                        <option
                                            value="selesai"
                                            {{ $detail->status === 'selesai' ? 'selected' : '' }}
                                        >
                                            Selesai
                                        </option>

                                        <option
                                            value="dibatalkan"
                                            {{ $detail->status === 'dibatalkan' ? 'selected' : '' }}
                                        >
                                            Dibatalkan
                                        </option>

                                    </select>

                                </div>

                                <div class="col-lg-8">

                                    <label class="form-label">
                                        Keterangan
                                    </label>

                                    <input
                                        type="text"
                                        name="details[{{ $index }}][keterangan]"
                                        value="{{ $detail->keterangan }}"
                                        class="form-control"
                                        placeholder="Keterangan..."
                                    >

                                </div>

                            </div>

                        </div>

                    @empty

                        <div
                            id="emptyDetail"
                            class="text-center text-muted py-5"
                        >
                            <i class="fa-regular fa-calendar-xmark fa-2x mb-3"></i>

                            <div class="fw-semibold">
                                Belum ada detail jadwal.
                            </div>

                            <small>
                                Klik "Generate" atau "Tambah".
                            </small>
                        </div>

                    @endforelse

                </div>

                <div class="d-flex justify-content-end mt-4">

                    <button
                        type="submit"
                        class="btn btn-jade"
                    >
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        Simpan Jadwal
                    </button>

                </div>

            </div>

        </div>

    </form>

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

    .detail-item {
        background: #fafcfc;
    }

    .detail-item label {
        font-size: 13px;
        font-weight: 600;
    }
</style>
@endpush


@push('scripts')
<script>

let detailIndex = {{ $jadwal->details->count() }};

const posyanduOptions = `
    <option value="">Pilih Posyandu</option>

    @foreach($posyandus as $posyandu)
        <option value="{{ $posyandu->id }}">
            {{ $posyandu->nama_posyandu }}
        </option>
    @endforeach
`;

const kegiatanOptions = `
    <option value="">Pilih Kegiatan</option>

    @foreach($kegiatans as $kegiatan)
        <option value="{{ $kegiatan->id }}">
            {{ $kegiatan->nama_kegiatan }}
        </option>
    @endforeach
`;

document.getElementById('addDetail')?.addEventListener('click', function () {

    document.getElementById('emptyDetail')?.remove();

    const container = document.getElementById('detailContainer');

    const html = `
        <div class="detail-item border rounded-3 p-3 mb-3">

            <div class="d-flex justify-content-between mb-3">

                <strong>
                    Kegiatan #${detailIndex + 1}
                </strong>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger remove-detail"
                >
                    <i class="fa-solid fa-trash"></i>
                </button>

            </div>

            <div class="row g-3">

                <div class="col-lg-4">
                    <label class="form-label">
                        Posyandu
                    </label>

                    <select
                        name="details[${detailIndex}][posyandu_id]"
                        class="form-select"
                        required
                    >
                        ${posyanduOptions}
                    </select>
                </div>

                <div class="col-lg-4">
                    <label class="form-label">
                        Kegiatan
                    </label>

                    <select
                        name="details[${detailIndex}][kegiatan_id]"
                        class="form-select"
                        required
                    >
                        ${kegiatanOptions}
                    </select>
                </div>

                <div class="col-lg-4">
                    <label class="form-label">
                        Tipe
                    </label>

                    <select
                        name="details[${detailIndex}][tipe_kegiatan]"
                        class="form-select"
                        required
                    >
                        <option value="DG">DG</option>
                        <option value="LG">LG</option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label class="form-label">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        name="details[${detailIndex}][tgl_mulai]"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-lg-3">
                    <label class="form-label">
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        name="details[${detailIndex}][tgl_selesai]"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-lg-3">
                    <label class="form-label">
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        name="details[${detailIndex}][jam_mulai]"
                        class="form-control"
                    >
                </div>

                <div class="col-lg-3">
                    <label class="form-label">
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        name="details[${detailIndex}][jam_selesai]"
                        class="form-control"
                    >
                </div>

                <div class="col-lg-4">
                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="details[${detailIndex}][status]"
                        class="form-select"
                    >
                        <option value="terjadwal">
                            Terjadwal
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                        <option value="dibatalkan">
                            Dibatalkan
                        </option>
                    </select>
                </div>

                <div class="col-lg-8">
                    <label class="form-label">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        name="details[${detailIndex}][keterangan]"
                        class="form-control"
                        placeholder="Keterangan..."
                    >
                </div>

            </div>

        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);

    detailIndex++;
});


document.addEventListener('click', function (event) {

    const button = event.target.closest('.remove-detail');

    if (!button) {
        return;
    }

    const item = button.closest('.detail-item');

    item.remove();

});

</script>
@endpush