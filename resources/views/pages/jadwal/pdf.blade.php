<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Jadwal Posyandu
    </title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #21665c;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 17px;
            color: #174a43;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 13px;
        }

        .header p {
            margin: 3px 0;
            font-size: 9px;
        }

        .information {
            width: 100%;
            margin-bottom: 15px;
        }

        .information td {
            padding: 3px;
        }

        .label {
            width: 100px;
            font-weight: bold;
        }

        .schedule {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule th {
            background: #e8f2f0;
            color: #174a43;
            border: 1px solid #555;
            padding: 6px 4px;
            text-align: center;
        }

        .schedule td {
            border: 1px solid #777;
            padding: 5px 4px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
        }

    </style>

</head>

<body>

    <div class="header">

        <h1>
            SISTEM PENJADWALAN POSYANDU
        </h1>

        <h2>
            JADWAL KEGIATAN POSYANDU
        </h2>

        <p>
            Periode:
            {{ \Carbon\Carbon::create()
                ->month($jadwal->bulan)
                ->translatedFormat('F') }}
            {{ $jadwal->tahun }}
        </p>

    </div>


    <table class="information">

        <tr>
            <td class="label">
                Status
            </td>

            <td>
                : {{ ucfirst($jadwal->status) }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Dibuat Oleh
            </td>

            <td>
                : {{ $jadwal->dibuatOleh->name ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Catatan
            </td>

            <td>
                : {{ $jadwal->catatan ?? '-' }}
            </td>
        </tr>

    </table>


    <table class="schedule">

        <thead>

            <tr>

                <th width="4%">
                    No
                </th>

                <th width="10%">
                    Tanggal
                </th>

                <th width="15%">
                    Posyandu
                </th>

                <th width="13%">
                    Wilayah
                </th>

                <th width="17%">
                    Kegiatan
                </th>

                <th width="12%">
                    Waktu
                </th>

                <th width="7%">
                    Tipe
                </th>

                <th width="9%">
                    Status
                </th>

                <th width="13%">
                    Keterangan
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($jadwal->details as $index => $detail)

                <tr>

                    <td class="center">
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

                    <td class="center">

                        @if($detail->jam_mulai && $detail->jam_selesai)

                            {{ substr($detail->jam_mulai, 0, 5) }}
                            -
                            {{ substr($detail->jam_selesai, 0, 5) }}

                        @else
                            -
                        @endif

                    </td>

                    <td class="center">
                        {{ $detail->tipe_kegiatan }}
                    </td>

                    <td class="center">
                        {{ ucfirst($detail->status) }}
                    </td>

                    <td>
                        {{ $detail->keterangan ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="9"
                        class="center"
                    >
                        Belum terdapat detail jadwal.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">

        Dicetak pada:
        {{ now()->translatedFormat('d F Y H:i') }}

    </div>

</body>

</html>