<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Posyandu</title>

    <style>
        @page {
            margin: 90px 30px 60px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        /* ================= HEADER (FIXED) ================= */
        .page-header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 70px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #21665c;
            padding-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
            padding-bottom: 8px;
        }

        .header-logo-cell {
            width: 60px;
            text-align: left;
        }

        .header-logo-cell img {
            width: 52px;
            height: 52px;
        }

        .header-spacer-cell {
            width: 60px;
        }

        .header-title-cell {
            text-align: center;
        }

        .header-title-cell h1 {
            margin: 0;
            font-size: 16px;
            color: #174a43;
            letter-spacing: .3px;
        }

        .header-title-cell h2 {
            margin: 4px 0 0;
            font-size: 12px;
            color: #21665c;
            font-weight: normal;
        }

        .header-title-cell p {
            margin: 4px 0 0;
            font-size: 9px;
            color: #555;
        }

        /* ================= FOOTER (FIXED) ================= */
        .page-footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #dfe6e4;
            padding-top: 6px;
            font-size: 8px;
            color: #8898aa;
        }

        .page-footer .footer-left {
            float: left;
        }

        .page-footer .footer-right {
            float: right;
        }

        /* ================= INFO PANEL ================= */
        .info-panel {
            width: 100%;
            border: 1px solid #dce9e6;
            background: #f7faf9;
            border-radius: 6px;
            margin-bottom: 14px;
            border-collapse: separate;
        }

        .info-panel td {
            padding: 6px 12px;
            vertical-align: top;
        }

        .info-label {
            width: 90px;
            font-weight: bold;
            color: #174a43;
        }

        .info-value {
            color: #333;
        }

        .status-badge {
            padding: 2px 9px;
            border-radius: 4px;
            font-weight: bold;
        }

        /* ================= SCHEDULE TABLE ================= */
        .schedule {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule th {
            background: #174a43;
            color: #fff;
            border: 1px solid #123d37;
            padding: 6px 4px;
            text-align: center;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: .2px;
        }

        .schedule td {
            border: 1px solid #dde5e3;
            padding: 5px 4px;
            vertical-align: top;
        }

        .schedule tbody tr.row-even {
            background: #f7faf9;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- ================= LOGO ================= --}}
    @php
        $logoPath = storage_path('app/public/logo_posyandu.png');
        $logoData = is_file($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $statusColors = [
            'draft' => ['bg' => '#f1f3f5', 'text' => '#6c757d'],
            'diajukan' => ['bg' => '#fff3cd', 'text' => '#856404'],
            'disetujui' => ['bg' => '#e8f2f0', 'text' => '#174a43'],
            'ditolak' => ['bg' => '#f8d7da', 'text' => '#842029'],
        ];

        $statusKey = strtolower($jadwal->status ?? 'draft');
        $statusColor = $statusColors[$statusKey] ?? ['bg' => '#e9ecef', 'text' => '#495057'];

        /*
         * Nama & tanggal persetujuan.
         * Kalau relasi "approver" belum ke-load / belum di-define dengan
         * benar di Model, coba fallback ambil langsung dari tabel users
         * lewat kolom disetujui_oleh supaya datanya tetap terisi.
         */
        $approverName = $jadwal->approver->name
            ?? optional(\App\Models\User::find($jadwal->disetujui_oleh))->name
            ?? '-';

        $tglDisetujui = $jadwal->disetujui_pada
            ? \Carbon\Carbon::parse($jadwal->disetujui_pada)->translatedFormat('d F Y H:i')
            : '-';
    @endphp

    {{-- ================= HEADER (FIXED, REPEATS EVERY PAGE) ================= --}}
    <div class="page-header">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    @if($logoData)
                        <img src="{{ $logoData }}" alt="Logo Posyandu">
                    @endif
                </td>

                <td class="header-title-cell">
                    <h1>SISTEM PENJADWALAN POSYANDU</h1>
                    <h2>Jadwal Kegiatan Posyandu</h2>
                    <p>
                        Periode:
                        {{ \Carbon\Carbon::create()->month($jadwal->bulan)->translatedFormat('F') }}
                        {{ $jadwal->tahun }}
                    </p>
                </td>

                <td class="header-spacer-cell"></td>
            </tr>
        </table>
    </div>

    {{-- ================= FOOTER (FIXED, REPEATS EVERY PAGE) ================= --}}
    <div class="page-footer">
        <span class="footer-left">
            Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}
        </span>
        <span class="footer-right">
            Halaman {PAGE_NUM} dari {PAGE_COUNT}
        </span>
    </div>

    {{-- ================= INFO PANEL ================= --}}
    <table class="info-panel">
        <tr>
            <td class="info-label">Status</td>
            <td class="info-value">
                <span class="status-badge" style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }};">
                    {{ ucfirst($jadwal->status) }}
                </span>
            </td>

            <td class="info-label">Dibuat Oleh</td>
            <td class="info-value">{{ $jadwal->pembuat->name ?? '-' }}</td>
        </tr>

        @if($statusKey === 'disetujui')
            <tr>
                <td class="info-label">Disetujui Oleh</td>
                <td class="info-value">{{ $approverName }}</td>

                <td class="info-label">Tgl Disetujui</td>
                <td class="info-value">{{ $tglDisetujui }}</td>
            </tr>
        @endif

        <tr>
            <td class="info-label">Catatan</td>
            <td class="info-value" colspan="3">{{ $jadwal->catatan ?? '-' }}</td>
        </tr>
    </table>

    {{-- ================= SCHEDULE TABLE ================= --}}
    <table class="schedule">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="11%">Tanggal</th>
                <th width="18%">Posyandu</th>
                <th width="15%">Wilayah</th>
                <th width="20%">Kegiatan</th>
                <th width="13%">Waktu</th>
                <th width="19%">Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse($jadwal->details as $index => $detail)
                <tr class="{{ $index % 2 === 0 ? 'row-even' : 'row-odd' }}">
                    <td class="center">{{ $index + 1 }}</td>

                    <td>{{ \Carbon\Carbon::parse($detail->tgl_mulai)->translatedFormat('d F Y') }}</td>

                    <td>{{ $detail->posyandu->nama_posyandu ?? '-' }}</td>

                    <td>{{ $detail->posyandu->wilayah->nama_wilayah ?? '-' }}</td>

                    <td>{{ $detail->kegiatan->nama_kegiatan ?? '-' }}</td>

                    <td class="center">
                        @if($detail->jam_mulai && $detail->jam_selesai)
                            {{ substr($detail->jam_mulai, 0, 5) }} - {{ substr($detail->jam_selesai, 0, 5) }}
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Belum terdapat detail jadwal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>