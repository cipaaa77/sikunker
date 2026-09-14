<div class="card laporan-card">

    {{-- HEADER HASIL --}}
    <div class="laporan-header">

        <div class="laporan-header-content">
            <h1>Hasil Laporan Jadwal</h1>

            <p>
                Daftar detail jadwal sesuai filter yang dipilih.
            </p>
        </div>

        <span class="badge bg-light text-dark">
            {{ $totalDetail }} Detail
        </span>

    </div>

    {{-- RINGKASAN --}}
    <div
        style="
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            padding: 22px 28px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
        "
    >

        {{-- DRAFT --}}
        <div
            style="
                padding: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 9px;
                background: #f8fafc;
            "
        >
            <div
                style="
                    color: #64748b;
                    font-size: 12px;
                    font-weight: 600;
                "
            >
                Draft
            </div>

            <div
                style="
                    margin-top: 5px;
                    color: #334155;
                    font-size: 23px;
                    font-weight: 700;
                "
            >
                {{ $statistikStatus['draft'] ?? 0 }}
            </div>

            <div
                style="
                    margin-top: 3px;
                    color: #94a3b8;
                    font-size: 11px;
                "
            >
                Jadwal
            </div>
        </div>

        {{-- DIAJUKAN --}}
        <div
            style="
                padding: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 9px;
                background: #f8fafc;
            "
        >
            <div
                style="
                    color: #64748b;
                    font-size: 12px;
                    font-weight: 600;
                "
            >
                Diajukan
            </div>

            <div
                style="
                    margin-top: 5px;
                    color: #334155;
                    font-size: 23px;
                    font-weight: 700;
                "
            >
                {{ $statistikStatus['diajukan'] ?? 0 }}
            </div>

            <div
                style="
                    margin-top: 3px;
                    color: #94a3b8;
                    font-size: 11px;
                "
            >
                Jadwal
            </div>
        </div>

        {{-- DISETUJUI --}}
        <div
            style="
                padding: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 9px;
                background: #f8fafc;
            "
        >
            <div
                style="
                    color: #64748b;
                    font-size: 12px;
                    font-weight: 600;
                "
            >
                Disetujui
            </div>

            <div
                style="
                    margin-top: 5px;
                    color: #334155;
                    font-size: 23px;
                    font-weight: 700;
                "
            >
                {{ $statistikStatus['disetujui'] ?? 0 }}
            </div>

            <div
                style="
                    margin-top: 3px;
                    color: #94a3b8;
                    font-size: 11px;
                "
            >
                Jadwal
            </div>
        </div>

        {{-- DITOLAK --}}
        <div
            style="
                padding: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 9px;
                background: #f8fafc;
            "
        >
            <div
                style="
                    color: #64748b;
                    font-size: 12px;
                    font-weight: 600;
                "
            >
                Ditolak
            </div>

            <div
                style="
                    margin-top: 5px;
                    color: #334155;
                    font-size: 23px;
                    font-weight: 700;
                "
            >
                {{ $statistikStatus['ditolak'] ?? 0 }}
            </div>

            <div
                style="
                    margin-top: 3px;
                    color: #94a3b8;
                    font-size: 11px;
                "
            >
                Jadwal
            </div>
        </div>

    </div>

    {{-- TABEL --}}
    @if ($details->isNotEmpty())

        <div style="overflow-x: auto;">

            <table
                style="
                    width: 100%;
                    min-width: 980px;
                    border-collapse: collapse;
                "
            >

                <thead>
                    <tr
                        style="
                            background: #f8fafc;
                            border-bottom: 1px solid #e5e7eb;
                        "
                    >
                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            NO
                        </th>

                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            TANGGAL
                        </th>

                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            JAM
                        </th>

                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            POSYANDU
                        </th>

                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            KEGIATAN
                        </th>

                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            STATUS
                        </th>

                        <th
                            style="
                                padding: 15px 22px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: 700;
                                text-align: left;
                                white-space: nowrap;
                            "
                        >
                            AKSI
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($details as $index => $detail)

                        <tr
                            style="
                                border-bottom: 1px solid #e5e7eb;
                                background: #ffffff;
                            "
                        >

                            {{-- NOMOR --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    color: #334155;
                                    font-size: 14px;
                                    white-space: nowrap;
                                "
                            >
                                {{ $index + 1 }}
                            </td>

                            {{-- TANGGAL --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    color: #334155;
                                    font-size: 14px;
                                    white-space: nowrap;
                                "
                            >
                                @if ($detail->tgl_mulai)
                                    {{ \Carbon\Carbon::parse($detail->tgl_mulai)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- JAM --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    color: #334155;
                                    font-size: 14px;
                                    white-space: nowrap;
                                "
                            >
                                @if ($detail->jam_mulai)
                                    {{ \Carbon\Carbon::parse($detail->jam_mulai)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- POSYANDU --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    color: #334155;
                                    font-size: 14px;
                                "
                            >
                                {{ $detail->posyandu->nama_posyandu ?? '-' }}
                            </td>

                            {{-- KEGIATAN --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    color: #334155;
                                    font-size: 14px;
                                "
                            >
                                {{ $detail->kegiatan->nama_kegiatan ?? '-' }}
                            </td>

                            {{-- STATUS JADWAL INDUK --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    font-size: 14px;
                                    white-space: nowrap;
                                "
                            >
                                @switch($detail->jadwal_status)

                                    @case('draft')
                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>
                                        @break

                                    @case('diajukan')
                                        <span class="badge bg-warning text-dark">
                                            Diajukan
                                        </span>
                                        @break

                                    @case('disetujui')
                                        <span class="badge bg-success">
                                            Disetujui
                                        </span>
                                        @break

                                    @case('ditolak')
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                        @break

                                    @default
                                        <span class="badge bg-light text-dark">
                                            Tidak Diketahui
                                        </span>

                                @endswitch
                            </td>

                            {{-- AKSI --}}
                            <td
                                style="
                                    padding: 16px 22px;
                                    white-space: nowrap;
                                "
                            >
                                @if (!empty($detail->jadwal_id))

                                    <a
                                        href="{{ route('jadwal.show', $detail->jadwal_id) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Detail
                                    </a>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        {{-- DATA KOSONG --}}
        <div
            style="
                padding: 50px 20px;
                background: #ffffff;
                text-align: center;
            "
        >

            <div
                style="
                    margin-bottom: 13px;
                    color: #94a3b8;
                    font-size: 34px;
                "
            >
                <i class="fas fa-calendar-xmark"></i>
            </div>

            <div
                style="
                    color: #475569;
                    font-size: 15px;
                    font-weight: 600;
                "
            >
                Tidak ada jadwal ditemukan
            </div>

            <div
                style="
                    margin-top: 6px;
                    color: #94a3b8;
                    font-size: 13px;
                "
            >
                Silakan ubah filter tanggal, status, posyandu,
                atau kegiatan.
            </div>

        </div>

    @endif

</div>

<style>
    @media (max-width: 700px) {
        .laporan-header {
            align-items: flex-start !important;
            flex-direction: column !important;
            gap: 10px;
        }
    }

    @media (max-width: 850px) {
        .laporan-card > div[style*="grid-template-columns"] {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 500px) {
        .laporan-card > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>