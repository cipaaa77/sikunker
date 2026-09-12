@extends('layouts.app')

@section('title', 'Detail Jadwal Bulanan')
@section('breadcrumb', 'Detail Jadwal')
@section('page-title', 'Detail Jadwal Bulanan')

@push('styles')
<style>
    :root {
        --jade-dark: #174a43;
        --jade: #21665c;
        --jade-light: #e8f2f0;
        --jade-hover: #f0f6f5;
        --text-dark: #344767;
        --text-muted: #8898aa;
        --border: #edf0f0;
    }

    /* ================= PAGE CARD ================= */
    .page-card {
        border-radius: 12px;
        border: 1px solid var(--border);
        animation: fadeUp .35s ease both;
    }

    .page-card-header {
        padding: 20px;
    }

    .page-title-small {
        color: var(--text-dark);
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .page-description {
        color: var(--text-muted);
        font-size: 12px;
        margin-bottom: 0;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ================= BUTTONS ================= */
    .btn-jade {
        background: var(--jade-dark);
        border-color: var(--jade-dark);
        color: #fff;
    }

    .btn-jade:hover {
        background: #123d37;
        border-color: #123d37;
        color: #fff;
    }

    .btn-outline-jade {
        border: 1px solid var(--jade);
        color: var(--jade);
        background: #fff;
    }

    .btn-outline-jade:hover {
        background: var(--jade-light);
        color: var(--jade-dark);
    }

    /* ================= STATUS BADGE ================= */
    .badge-draft { background: #f1f3f5; color: #6c757d; font-weight: 600; }
    .badge-diajukan { background: #fff3cd; color: #856404; font-weight: 600; }
    .badge-disetujui { background: var(--jade-light); color: var(--jade-dark); font-weight: 600; }
    .badge-ditolak { background: #f8d7da; color: #842029; font-weight: 600; }

    /* ================= STATUS STEPPER ================= */
    .status-stepper {
        display: flex;
        align-items: flex-start;
        gap: 0;
        padding: 22px 8px 6px;
    }

    .step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
    }

    .step-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        background: #f1f3f5;
        color: #adb5bd;
        border: 2px solid #f1f3f5;
        transition: all .25s ease;
        z-index: 2;
    }

    .step.completed .step-circle {
        background: var(--jade-dark);
        border-color: var(--jade-dark);
        color: #fff;
    }

    .step.current .step-circle {
        background: #fff;
        border-color: var(--jade-dark);
        color: var(--jade-dark);
        box-shadow: 0 0 0 4px var(--jade-light);
    }

    .step.rejected .step-circle {
        background: #b33a3a;
        border-color: #b33a3a;
        color: #fff;
    }

    .step-label {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #adb5bd;
    }

    .step.completed .step-label,
    .step.current .step-label {
        color: var(--jade-dark);
    }

    .step.rejected .step-label {
        color: #b33a3a;
    }

    .step::before {
        content: '';
        position: absolute;
        top: 19px;
        left: -50%;
        width: 100%;
        height: 2px;
        background: #f1f3f5;
        z-index: 1;
    }

    .step:first-child::before {
        display: none;
    }

    .step.completed::before,
    .step.current::before {
        background: var(--jade-dark);
    }

    /* ================= INFO GRID ================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 11px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: var(--jade-light);
        color: var(--jade-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .info-label {
        color: var(--text-muted);
        font-size: 11px;
        margin-bottom: 2px;
    }

    .info-value {
        color: var(--text-dark);
        font-size: 13px;
        font-weight: 600;
    }

    /* ================= NOTE BLOCK ================= */
    .note-block {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #fbfcfc;
        padding: 14px 16px;
    }

    .note-block-label {
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 5px;
    }

    .note-block-text {
        color: var(--text-dark);
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 0;
        white-space: pre-line;
    }

    .note-block.note-rejected {
        background: #fdf3f3;
        border-color: #f1d7d7;
    }

    /* ================= SECTION TITLE ================= */
    .section-title {
        color: var(--text-dark);
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .section-subtitle {
        color: var(--text-muted);
        font-size: 12px;
        margin-bottom: 0;
    }

    /* ================= DETAIL CARD ================= */
    .jadwal-detail-card {
        border: 1px solid #e8ecef;
        border-radius: 10px;
        background: #fff;
        padding: 14px;
        height: 100%;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .jadwal-detail-card:hover {
        box-shadow: 0 6px 18px rgba(23, 74, 67, .07);
        transform: translateY(-2px);
    }

    .jadwal-detail-title {
        color: var(--text-dark);
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
        color: var(--text-dark);
        font-size: 12px;
        font-weight: 600;
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--jade-light);
        color: var(--jade-dark);
        font-size: 18px;
    }

    /* ================= MOBILE ================= */
    @media (max-width: 767px) {
        .page-card-header {
            padding: 16px;
        }

        .header-action {
            width: 100%;
            margin-top: 12px;
        }

        .header-action .btn {
            flex: 1;
        }

        .status-stepper {
            padding: 16px 0 4px;
        }

        .step-label {
            font-size: 10.5px;
        }
    }
</style>
@endpush

@section('content')

@php
    $status = strtolower($jadwal->status ?? 'draft');

    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $periodeText = ($namaBulan[(int) $jadwal->bulan] ?? '-') . ' ' . $jadwal->tahun;

    $statusSteps = ['draft', 'diajukan', 'disetujui'];
    $currentStepIndex = array_search($status, $statusSteps);
@endphp

<div class="card page-card">

    {{-- ================= HEADER ================= --}}
    <div class="page-card-header">
        <div class="row align-items-start">

            <div class="col-md-7">
                <div class="page-title-small">
                    Jadwal Posyandu — {{ $periodeText }}
                </div>
                <p class="page-description">
                    Detail lengkap jadwal bulanan beserta kegiatan Posyandu.
                </p>
            </div>

            <div class="col-md-5">
                <div class="d-flex justify-content-md-end flex-wrap gap-2 header-action">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    @if($status !== 'disetujui')
                        <a href="{{ route('jadwal.edit', $jadwal) }}" class="btn btn-outline-jade btn-sm">
                            <i class="fas fa-pen me-1"></i>
                            Edit
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <div class="border-top"></div>

    {{-- ================= STATUS ================= --}}
    <div class="p-3">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div class="section-title">Status Jadwal</div>

            @if($status === 'draft')
                <span class="badge badge-draft">Draft</span>
            @elseif($status === 'diajukan')
                <span class="badge badge-diajukan">Diajukan</span>
            @elseif($status === 'disetujui')
                <span class="badge badge-disetujui">Disetujui</span>
            @elseif($status === 'ditolak')
                <span class="badge badge-ditolak">Ditolak</span>
            @else
                <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
            @endif
        </div>

        @if($status === 'ditolak')

            <div class="note-block note-rejected">
                <div class="note-block-label">
                    <i class="fas fa-circle-xmark me-1"></i>
                    Jadwal Ditolak
                </div>
                <p class="note-block-text">
                    Jadwal ini ditolak dan perlu diperiksa kembali sebelum diajukan ulang.
                </p>
            </div>

        @else

            <div class="status-stepper">

                <div class="step {{ $currentStepIndex > 0 ? 'completed' : ($currentStepIndex === 0 ? 'current' : '') }}">
                    <div class="step-circle"><i class="fas fa-pen"></i></div>
                    <div class="step-label">Draft</div>
                </div>

                <div class="step {{ $currentStepIndex > 1 ? 'completed' : ($currentStepIndex === 1 ? 'current' : '') }}">
                    <div class="step-circle"><i class="fas fa-paper-plane"></i></div>
                    <div class="step-label">Diajukan</div>
                </div>

                <div class="step {{ $currentStepIndex === 2 ? 'completed' : '' }}">
                    <div class="step-circle"><i class="fas fa-circle-check"></i></div>
                    <div class="step-label">Disetujui</div>
                </div>

            </div>

        @endif

    </div>

    <div class="border-top"></div>

    {{-- ================= INFO SUMMARY ================= --}}
    <div class="p-3">

        <div class="info-grid mb-3">

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar-days"></i></div>
                <div>
                    <div class="info-label">Periode</div>
                    <div class="info-value">{{ $periodeText }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div>
                    <div class="info-label">Dibuat Oleh</div>
                    <div class="info-value">{{ $jadwal->pembuat->name ?? 'Sistem' }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="info-label">Tanggal Dibuat</div>
                    <div class="info-value">{{ $jadwal->created_at?->format('d-m-Y H:i') ?? '-' }}</div>
                </div>
            </div>

            @if($status === 'disetujui')
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-user-check"></i></div>
                    <div>
                        <div class="info-label">Disetujui Oleh</div>
                        <div class="info-value">{{ $jadwal->approver->name ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="info-label">Tanggal Disetujui</div>
                        <div class="info-value">
                            {{ $jadwal->disetujui_pada ? \Carbon\Carbon::parse($jadwal->disetujui_pada)->format('d-m-Y H:i') : '-' }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-list-check"></i></div>
                <div>
                    <div class="info-label">Jumlah Kegiatan</div>
                    <div class="info-value">{{ $jadwal->details->count() }} detail</div>
                </div>
            </div>

        </div>

        @if($jadwal->catatan)
            <div class="note-block mb-3">
                <div class="note-block-label">
                    <i class="fas fa-note-sticky me-1"></i>
                    Catatan Jadwal
                </div>
                <p class="note-block-text">{{ $jadwal->catatan }}</p>
            </div>
        @endif

        @if(!empty($jadwal->catatan_koordinator))
            <div class="note-block">
                <div class="note-block-label">
                    <i class="fas fa-comment-dots me-1"></i>
                    Catatan Koordinator
                </div>
                <p class="note-block-text">{{ $jadwal->catatan_koordinator }}</p>
            </div>
        @endif

    </div>

    <div class="border-top"></div>

    {{-- ================= DETAIL KEGIATAN ================= --}}
    <div class="p-3">

        <div class="mb-3">
            <div class="section-title">Detail Kegiatan</div>
            <p class="section-subtitle">
                Daftar Posyandu, jenis kegiatan, tanggal, dan jam pelaksanaan.
            </p>
        </div>

        <div class="row g-3">

            @forelse($jadwal->details as $detail)

                <div class="col-md-6 col-xl-4">
                    <div class="jadwal-detail-card">

                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-location-dot me-2 mt-1" style="color: var(--jade-dark);"></i>

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
                            <div class="detail-label">Kegiatan</div>
                            <div class="detail-value">{{ $detail->kegiatan->nama_kegiatan ?? '-' }}</div>
                        </div>

                        <div class="mb-2">
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-value">
                                {{ optional($detail->tgl_mulai)->translatedFormat('d F Y') }}
                                @if($detail->tgl_selesai && $detail->tgl_selesai != $detail->tgl_mulai)
                                    - {{ optional($detail->tgl_selesai)->translatedFormat('d F Y') }}
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="detail-label">Waktu</div>
                            <div class="detail-value">
                                {{ $detail->jam_mulai }} - {{ $detail->jam_selesai }}
                            </div>
                        </div>

                        @if($detail->keterangan)
                            <div class="mb-3">
                                <div class="detail-label">Keterangan</div>
                                <div class="jadwal-detail-text">{{ $detail->keterangan }}</div>
                            </div>
                        @endif

                        <a
                            href="{{ route('jadwal.pdf-one', ['jadwal' => $jadwal->id, 'posyandu' => $detail->posyandu_id]) }}"
                            class="btn btn-sm btn-outline-danger w-100"
                        >
                            <i class="fas fa-file-pdf me-1"></i>
                            PDF Posyandu
                        </a>

                    </div>
                </div>

            @empty

                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-xmark"></i>
                        </div>
                        <h6 class="fw-semibold">Belum Ada Detail Kegiatan</h6>
                        <p class="text-muted small mb-0">
                            Jadwal ini belum memiliki detail kegiatan Posyandu.
                        </p>
                    </div>
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection