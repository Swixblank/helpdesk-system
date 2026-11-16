<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pengguna & Staf - PT. Kumala Central Partindo</title>
    
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
            background-color: #525659;
            color: #000;
            line-height: 1.35;
            font-size: 10pt;
            padding: 0;
            margin: 0;
        }

        .screen-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #2b2d30;
            color: #e2e8f0;
            padding: 10px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
            font-family: Arial, sans-serif;
        }

        .btn {
            font-family: Arial, sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 5px;
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
            background-color: #3f4246;
            color: #f1f5f9;
            border: 1px solid #5a5f66;
        }
        .btn-back:hover {
            background-color: #4e5359;
            color: #ffffff;
        }

        .toolbar-info {
            font-size: 12px;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .document-container {
            padding: 24px 0 48px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .word-page {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25), 0 0 2px rgba(0, 0, 0, 0.15);
            padding: 18mm 20mm 15mm 20mm;
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Kop Surat Perusahaan */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 18px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h2 {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .kop-text h3 {
            font-size: 12pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
        }

        .kop-text p {
            font-size: 9pt;
            margin: 1px 0;
            color: #333;
        }

        /* Judul Laporan */
        .doc-title {
            text-align: center;
            margin-bottom: 14px;
        }

        .doc-title h4 {
            font-size: 12.5pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title p {
            font-size: 9pt;
            margin-top: 4px;
            color: #475569;
        }

        /* Ringkasan Filter & Statistik */
        .filter-info {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            margin-bottom: 14px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 20px;
        }

        table.data-table th, 
        table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .nowrap { white-space: nowrap; }

        /* Footer Halaman Word */
        .page-footer {
            margin-top: auto;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #64748b;
        }

        /* Blok Tanda Tangan */
        .signature-container {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 44%;
            text-align: center;
            font-size: 10pt;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-title {
            font-size: 9pt;
            color: #333;
        }

        @media print {
            body {
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .screen-toolbar {
                display: none !important;
            }

            .document-container {
                padding: 0 !important;
                gap: 0 !important;
            }

            .word-page {
                width: 100% !important;
                min-height: 297mm !important;
                margin: 0 !important;
                padding: 15mm 18mm 15mm 18mm !important;
                box-shadow: none !important;
                border: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar Layar -->
    <div class="screen-toolbar">
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-back">
                &larr; Kembali ke Manajemen Pengguna
            </a>
        </div>

        <div class="toolbar-info">
            <span>Format: <strong>Laporan Data Pengguna & Staf (A4 Portrait)</strong></span>
            <span>Total: <strong>{{ $users->count() }} Pengguna</strong></span>
        </div>

        <div>
            <button onclick="window.print()" class="btn btn-print">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen A4 -->
    <div class="document-container">
        <div class="word-page">
            <!-- Kop Surat Resmi -->
            <div class="kop-surat">
                <img src="{{ asset('images/logo-kumala.png') }}" alt="Logo PT. Kumala Central Partindo" class="kop-logo">
                <div class="kop-text">
                    <h2>PT. KUMALA CENTRAL PARTINDO</h2>
                    <h3>DIVISI TEKNOLOGI INFORMASI & OPERASIONAL</h3>
                    <p>Jl. Mayjen Sutoyo S No. 144, RT. 36, Teluk Dalam, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70117</p>
                    <p>Telepon: (0511) 5209969 | HP: 081251562768 / 081256707725 | Email: kcpbjm@yahoo.co.id</p>
                </div>
            </div>

            <!-- Judul Dokumen -->
            <div class="doc-title">
                <h4>LAPORAN DATA PENGGUNA DAN STAF IT SUPPORT</h4>
                <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y - H:i') }} WITA | Oleh: {{ auth()->user()->name }} (ADMINISTRATOR)</p>
            </div>

            <!-- Ringkasan Filter & Total -->
            <div class="filter-info">
                <div>Filter Peran: <strong>{{ $roleFilter ? strtoupper($roleFilter) : 'Semua Peran' }}</strong> | Status: <strong>{{ $statusFilter !== '' ? ($statusFilter ? 'Aktif' : 'Nonaktif') : 'Semua Status' }}</strong></div>
                <div>Total Data: <strong>{{ $users->count() }} Pengguna Terdaftar</strong></div>
            </div>

            <!-- Tabel Data Pengguna & Staf -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Email Kantor</th>
                        <th style="width: 100px;">Peran (Role)</th>
                        <th style="width: 75px;">Status</th>
                        <th style="width: 95px;">Aktivitas Tiket</th>
                        <th style="width: 85px;">Tgl Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $u)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td style="font-weight: 600;">{{ $u->name }}</td>
                            <td class="nowrap" style="font-family: monospace; font-size: 8.5pt;">{{ $u->email }}</td>
                            <td class="text-center nowrap" style="text-transform: uppercase; font-weight: 600;">
                                {{ str_replace('_', ' ', $u->getRoleNames()->first() ?? 'User') }}
                            </td>
                            <td class="text-center nowrap">
                                {{ $u->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </td>
                            <td class="text-center">
                                @if ($u->hasRole('employee'))
                                    {{ $u->tickets->count() }} tiket dibuat
                                @elseif ($u->hasRole('it_support'))
                                    {{ $u->assignments->count() }} tiket ditangani
                                @else
                                    Sistem Admin
                                @endif
                            </td>
                            <td class="text-center nowrap">{{ $u->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 20px; color: #64748b;">
                                Tidak ada data pengguna yang memenuhi kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Blok Tanda Tangan Resmi -->
            <div class="signature-container">
                <div class="signature-box">
                    <p>Dibuat Oleh,</p>
                    <p class="signature-title">Administrator IT Helpdesk</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">{{ auth()->user()->name }}</p>
                    <p class="signature-title">NIK/ID: {{ auth()->user()->id }}</p>
                </div>

                <div class="signature-box">
                    <p>Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="signature-title">Mengetahui / Menyetujui,</p>
                    <p class="signature-title">Kepala Divisi IT & Operasional</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">( .................................................. )</p>
                    <p class="signature-title">Manager IT PT. Kumala Central Partindo</p>
                </div>
            </div>

            <!-- Footer Halaman -->
            <div class="page-footer">
                <span>PT. Kumala Central Partindo — Laporan Master Pengguna & Staf IT</span>
                <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WITA</span>
                <span>Dokumen Resmi Helpdesk IT</span>
            </div>
        </div>
    </div>

</body>
</html>
