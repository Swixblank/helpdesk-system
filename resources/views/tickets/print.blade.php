<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket {{ $ticket->ticket_number }} - PT. Kumala Central Partindo</title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-color: #f1f5f9;
            color: #000;
            line-height: 1.4;
            font-size: 12pt;
            padding: 20px;
        }

        .screen-toolbar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
        }

        .btn {
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
        }

        .sheet {
            background: #ffffff;
            max-width: 800px;
            margin: 0 auto;
            padding: 35px 45px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 85px;
            height: auto;
            margin-right: 20px;
            object-contain: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h2 {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .kop-text h4 {
            font-size: 11pt;
            font-weight: normal;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            font-size: 9pt;
            color: #222;
            line-height: 1.3;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            margin-bottom: 22px;
        }

        .doc-title h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .doc-title p {
            font-size: 11pt;
            font-weight: bold;
        }

        /* Tabel Data */
        table.meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 11pt;
        }

        table.meta-table td {
            padding: 5px 8px;
            vertical-align: top;
        }

        table.meta-table td.label {
            width: 25%;
            font-weight: bold;
        }

        table.meta-table td.separator {
            width: 2%;
        }

        table.meta-table td.value {
            width: 73%;
        }

        /* Box Rincian Masalah */
        .detail-box {
            border: 1px solid #000;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 11pt;
        }

        .detail-box-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 6px;
            text-transform: uppercase;
            font-size: 11pt;
        }

        /* Tabel Riwayat Tindakan */
        table.log-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10.5pt;
        }

        table.log-table th, table.log-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            text-align: left;
        }

        table.log-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }

        /* Kolom Tanda Tangan */
        .ttd-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            text-align: center;
            font-size: 11pt;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 30%;
        }

        .ttd-space {
            height: 65px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Badge Cetak */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border: 1px solid #000;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .screen-toolbar {
                display: none !important;
            }

            .sheet {
                box-shadow: none;
                border: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }

            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar Layar (Tidak Tercetak) -->
    <div class="screen-toolbar">
        <div>
            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-back">
                &larr; Kembali ke Detail Tiket
            </a>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <button onclick="window.print()" class="btn btn-print">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Lembar Kerja / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Lembar Cetak Dokumen -->
    <div class="sheet">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-kumala.png') }}" alt="Logo PT. Kumala Central Partindo" class="kop-logo">
            <div class="kop-text">
                <h2>PT. KUMALA CENTRAL PARTINDO</h2>
                <h4>Distributor Resmi Suku Cadang & Perlengkapan Kendaraan Bermotor</h4>
                <p>Jl. Mayjen Sutoyo S No. 144, RT. 36, Teluk Dalam, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70117</p>
                <p>Telepon: (0511) 5209969 | HP: 081251562768 / 081256707725 | Email: kcpbjm@yahoo.co.id</p>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            <h3>SURAT TUGAS & LEMBAR PENANGANAN KENDALA IT</h3>
            <p>Nomor Tiket: {{ $ticket->ticket_number }}</p>
        </div>

        <!-- Informasi Utama Tiket -->
        <table class="meta-table">
            <tr>
                <td class="label">Tanggal Pengaduan</td>
                <td class="separator">:</td>
                <td class="value">{{ $ticket->created_at->format('d/m/Y H:i') }} WITA</td>
            </tr>
            <tr>
                <td class="label">Nama Pelapor</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $ticket->creator->name ?? '-' }}</strong> ({{ $ticket->creator->email ?? '-' }})</td>
            </tr>
            <tr>
                <td class="label">Kategori Kendala</td>
                <td class="separator">:</td>
                <td class="value">{{ $ticket->category->name ?? 'Umum' }}</td>
            </tr>
            <tr>
                <td class="label">Tingkat Prioritas</td>
                <td class="separator">:</td>
                <td class="value">
                    <span class="badge">{{ strtoupper($ticket->priority) }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">Status Tiket</td>
                <td class="separator">:</td>
                <td class="value">
                    <span class="badge">{{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">Teknisi Penangan (IT)</td>
                <td class="separator">:</td>
                <td class="value">
                    <strong>{{ $ticket->activeAssignment->assignedTo->name ?? 'Belum Ditugaskan' }}</strong>
                </td>
            </tr>
            <tr>
                <td class="label">Subjek Masalah</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $ticket->subject }}</strong></td>
            </tr>
        </table>

        <!-- Uraian Masalah Lengkap -->
        <div class="detail-box">
            <div class="detail-box-title">Uraian / Deskripsi Kerusakan:</div>
            <p style="white-space: pre-line;">{{ $ticket->description }}</p>
        </div>

        <!-- Catatan Tindak Lanjut / Solusi Penanganan -->
        <div style="margin-bottom: 8px; font-weight: bold; font-size: 11pt;">
            TINDAK LANJUT &amp; CATATAN PERBAIKAN:
        </div>
        <table class="log-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Waktu</th>
                    <th style="width: 25%;">Petugas / Pengguna</th>
                    <th style="width: 50%;">Tindakan / Solusi Teknis</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ticket->comments as $comment)
                    <tr>
                        <td style="text-align: center;">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $comment->user->name ?? '-' }}</strong>
                            <div style="font-size: 9pt; color: #444;">({{ $comment->user->getRoleNames()->first() ?? 'Staff' }})</div>
                        </td>
                        <td>{{ $comment->message }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #555; font-style: italic;">
                            Belum ada catatan tindak lanjut perbaikan tambahan pada tiket ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanggal dan Pengesahan -->
        <div style="text-align: right; margin-bottom: 10px; font-size: 11pt;">
            Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>

        <div class="ttd-container">
            <div class="ttd-box">
                <p>Pelapor,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $ticket->creator->name ?? '....................................' }}</p>
                <p style="font-size: 9.5pt;">Karyawan</p>
            </div>

            <div class="ttd-box">
                <p>Teknisi Penangan,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $ticket->activeAssignment->assignedTo->name ?? '....................................' }}</p>
                <p style="font-size: 9.5pt;">IT Support</p>
            </div>

            <div class="ttd-box">
                <p>Mengetahui,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">.........................................</p>
                <p style="font-size: 9.5pt;">Pimpinan / Kepala IT</p>
            </div>
        </div>
    </div>

</body>
</html>
