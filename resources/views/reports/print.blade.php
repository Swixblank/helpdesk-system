<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Laporan Rekapitulasi Kendala IT' }} ({{ $orientation === 'portrait' ? 'Potret' : 'Lanskap' }}) - PT. Kumala Central Partindo</title>
    
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
            line-height: 1.25;
            padding: 0;
            margin: 0;
        }

        /* Sticky Toolbar di bagian atas */
        .screen-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #202225;
            color: #e2e8f0;
            padding: 8px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            font-family: Arial, sans-serif;
            gap: 12px;
        }

        .toolbar-left, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-center {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .toolbar-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Menu Pengalih Tata Letak & Zoom */
        .switch-group {
            display: inline-flex;
            background: #111315;
            padding: 2.5px;
            border-radius: 6px;
            border: 1px solid #3f444e;
            gap: 2px;
        }

        .btn-toggle {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #94a3b8;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            background: transparent;
        }

        .btn-toggle:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-toggle.active {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        .btn {
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 15px;
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
            background-color: #35383c;
            color: #f1f5f9;
            border: 1px solid #4e535a;
        }
        .btn-back:hover {
            background-color: #44484e;
            color: #ffffff;
        }

        .badge {
            background-color: #334155;
            color: #e2e8f0;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            font-family: Arial, sans-serif;
        }

        .badge-accent {
            background-color: #1e3a8a;
            color: #bfdbfe;
        }

        /* Container lembar kerja */
        .document-container {
            padding: 20px 0 40px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            transition: zoom 0.15s ease;
        }

        /* Lembar Halaman Standar Word / PDF */
        .word-page {
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25), 0 0 2px rgba(0, 0, 0, 0.15);
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .page-badge-screen {
            position: absolute;
            top: -18px;
            left: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #cbd5e1;
            font-weight: 600;
        }

        /* ========================================================
           TATA LETAK POTRET (A4 Portrait: 210mm x 297mm)
           ======================================================== */
        @if ($orientation === 'portrait')
            .word-page {
                width: 210mm;
                min-height: 297mm;
                padding: 10mm 12mm 10mm 12mm;
            }

            .kop-surat {
                display: flex;
                align-items: center;
                border-bottom: 2.5px double #000;
                padding-bottom: 6px;
                margin-bottom: 14px;
            }

            .kop-logo {
                width: 48px;
                height: 48px;
                object-fit: contain;
                margin-right: 12px;
            }

            .kop-text {
                flex: 1;
                text-align: center;
            }

            .kop-text h2 {
                font-size: 12pt;
                font-weight: bold;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-bottom: 1px;
            }

            .kop-text h3 {
                font-size: 9.5pt;
                font-weight: bold;
                color: #1e3a8a;
                margin-bottom: 2px;
            }

            .kop-text p {
                font-size: 7.5pt;
                margin: 0;
                color: #333;
                line-height: 1.2;
            }

            .doc-title {
                text-align: center;
                margin-bottom: 8px;
            }

            .doc-title h4 {
                font-size: 10pt;
                font-weight: bold;
                text-decoration: underline;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .doc-title p {
                font-size: 7.5pt;
                margin-top: 2px;
                color: #475569;
            }

            .filter-info {
                font-family: Arial, sans-serif;
                font-size: 7.5pt;
                background: #f8fafc;
                border: 1px solid #cbd5e1;
                padding: 3px 6px;
                margin-bottom: 6px;
                border-radius: 4px;
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            table.data-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 7.2pt;
                table-layout: fixed;
            }

            table.data-table th, 
            table.data-table td {
                border: 1px solid #000;
                padding: 3px 3px;
                vertical-align: middle;
                overflow-wrap: break-word;
                line-height: 1.2;
                box-sizing: border-box;
            }

            table.data-table td {
                overflow: hidden;
            }

            table.data-table th {
                background-color: #f1f5f9;
                font-weight: bold;
                text-align: center;
                text-transform: uppercase;
                font-size: 6.8pt;
                letter-spacing: 0.1px;
                padding: 4px 2px;
                line-height: 1.15;
            }

            /* Kolom Potret (Total: 100%) */
            .col-no { width: 3%; }
            .col-ticket { width: 11.5%; }
            .col-date { width: 7.5%; }
            .col-subject { width: 21.5%; }
            .col-cat { width: 9%; }
            .col-pri { width: 7.5%; }
            .col-user { width: 9.5%; }
            .col-tech { width: 10%; }
            .col-status { width: 12%; }
            .col-res { width: 8.5%; }

            .signature-container {
                margin-top: 10px;
                display: flex;
                justify-content: space-between;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .signature-box {
                width: 44%;
                text-align: center;
                font-size: 8pt;
            }

            .signature-space {
                height: 36px;
            }

            .signature-name {
                font-weight: bold;
                text-decoration: underline;
            }

            .signature-title {
                font-size: 7.5pt;
                color: #333;
            }

        /* ========================================================
           TATA LETAK LANSKAP (A4 Landscape: 297mm x 210mm)
           ======================================================== */
        @else
            .word-page {
                width: 297mm;
                min-height: 210mm;
                padding: 8mm 12mm 6mm 12mm;
            }

            .kop-surat {
                display: flex;
                align-items: center;
                border-bottom: 2.5px double #000;
                padding-bottom: 6px;
                margin-bottom: 14px;
            }

            .kop-logo {
                width: 52px;
                height: 52px;
                object-fit: contain;
                margin-right: 14px;
            }

            .kop-text {
                flex: 1;
                text-align: center;
            }

            .kop-text h2 {
                font-size: 13pt;
                font-weight: bold;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-bottom: 1px;
            }

            .kop-text h3 {
                font-size: 10pt;
                font-weight: bold;
                color: #1e3a8a;
                margin-bottom: 2px;
            }

            .kop-text p {
                font-size: 7.5pt;
                margin: 0;
                color: #333;
                line-height: 1.2;
            }

            .doc-title {
                text-align: center;
                margin-bottom: 8px;
            }

            .doc-title h4 {
                font-size: 10.5pt;
                font-weight: bold;
                text-decoration: underline;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .doc-title p {
                font-size: 7.5pt;
                margin-top: 2px;
                color: #475569;
            }

            .filter-info {
                font-family: Arial, sans-serif;
                font-size: 7.5pt;
                background: #f8fafc;
                border: 1px solid #cbd5e1;
                padding: 3px 6px;
                margin-bottom: 6px;
                border-radius: 4px;
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            table.data-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 7.5pt;
                table-layout: fixed;
            }

            table.data-table th, 
            table.data-table td {
                border: 1px solid #000;
                padding: 3.5px 5px;
                vertical-align: middle;
                word-break: break-word;
                overflow-wrap: break-word;
                line-height: 1.25;
                box-sizing: border-box;
            }

            table.data-table td {
                overflow: hidden;
            }

            table.data-table th {
                background-color: #f1f5f9;
                font-weight: bold;
                text-align: center;
                text-transform: uppercase;
                font-size: 7.2pt;
                letter-spacing: 0.2px;
                line-height: 1.15;
            }

            /* Kolom Lanskap */
            .col-no { width: 28px; }
            .col-ticket { width: 95px; }
            .col-date { width: 68px; }
            .col-cat { width: 85px; }
            .col-pri { width: 60px; }
            .col-user { width: 95px; }
            .col-tech { width: 95px; }
            .col-status { width: 85px; }
            .col-res { width: 65px; }

            .signature-container {
                margin-top: 8px;
                display: flex;
                justify-content: space-between;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .signature-box {
                width: 42%;
                text-align: center;
                font-size: 8pt;
            }

            .signature-space {
                height: 32px;
            }

            .signature-name {
                font-weight: bold;
                text-decoration: underline;
            }

            .signature-title {
                font-size: 7.5pt;
                color: #333;
            }
        @endif

        /* Running Header Halaman Lanjutan */
        .running-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #94a3b8;
            padding-bottom: 4px;
            margin-bottom: 6px;
            font-family: Arial, sans-serif;
            font-size: 7.5pt;
            color: #475569;
        }

        .running-header strong {
            color: #0f172a;
        }

        .filter-info span {
            color: #64748b;
        }

        .filter-info strong {
            color: #0f172a;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .nowrap { white-space: nowrap; }

        /* Footer Halaman Word */
        .page-footer {
            margin-top: auto;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 7pt;
            color: #64748b;
        }

        /* Box Rekap Status di Lembar Penutup */
        .summary-stats-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            margin: 10px 0 14px 0;
            font-family: Arial, sans-serif;
        }

        .summary-stats-box h5 {
            font-size: 8.5pt;
            color: #1e3a8a;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .summary-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            font-size: 7.5pt;
        }

        .summary-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 3px 6px;
            border-radius: 4px;
        }

        /* ========================================================
           MEDIA CETAK (Print Dialog & Save as PDF)
           ======================================================== */
        @media print {
            @page {
                @if ($orientation === 'portrait')
                    size: A4 portrait;
                    margin: 8mm 10mm 8mm 10mm;
                @else
                    size: A4 landscape;
                    margin: 6mm 8mm 6mm 8mm;
                @endif
            }

            body {
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .screen-toolbar {
                display: none !important;
            }

            .page-badge-screen {
                display: none !important;
            }

            .document-container {
                padding: 0 !important;
                gap: 0 !important;
                zoom: 1 !important;
            }

            .word-page {
                width: 100% !important;
                min-height: auto !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always !important;
                break-after: page !important;
            }

            .word-page:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .signature-container {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    @php
        $allTickets = $tickets->values();
        $totalCount = $allTickets->count();

        // LOGIKA PEMBAGIAN HALAMAN (PAGINATION) YANG PADAT, RAPI, & MENGISI PENUH LEMBAR KERJA
        if ($orientation === 'portrait') {
            // A4 Potret (210mm x 297mm): Ketinggian 297mm dimaksimalkan agar lembar kerja terisi penuh
            if ($totalCount <= 20) {
                // Jika total data sedikit (<= 20): Kop + Judul + Filter + Tabel + TTD muat sempurna dalam 1 lembar!
                $firstPageLimit = 20;
                $otherPageLimit = 32;
                $maxRowsForSignatureOnLastPage = 20;
            } else {
                // Jika data banyak:
                // - Halaman 1 memuat Kop + Judul + Filter + 26 baris data (mengisi penuh tinggi halaman 1)
                // - Halaman 2 dan seterusnya memuat 32 baris data per lembar (mengisi penuh halaman)
                // - Halaman terakhir memuat tanda tangan jika barisnya <= 24, jika > 24 dibuat lembar pengesahan khusus
                $firstPageLimit = 26;
                $otherPageLimit = 32;
                $maxRowsForSignatureOnLastPage = 24;
            }
        } else {
            // A4 Lanskap (297mm x 210mm): Ketinggian 210mm dimaksimalkan agar lembar kerja terisi penuh
            if ($totalCount <= 12) {
                // Jika total data sedikit (<= 12): Kop + Judul + Filter + Tabel + TTD muat sempurna dalam 1 lembar!
                $firstPageLimit = 12;
                $otherPageLimit = 25;
                $maxRowsForSignatureOnLastPage = 12;
            } else {
                // Jika data banyak:
                // - Halaman 1 memuat Kop + Judul + Filter + 18 baris data (mengisi penuh tinggi halaman 1 lanskap)
                // - Halaman 2 dan seterusnya memuat 25 baris data per lembar (mengisi penuh lembar kerja lanskap)
                // - Halaman terakhir memuat tanda tangan jika barisnya <= 18, jika > 18 dibuat lembar pengesahan khusus
                $firstPageLimit = 18;
                $otherPageLimit = 25;
                $maxRowsForSignatureOnLastPage = 18;
            }
        }

        $pages = [];

        if ($totalCount === 0) {
            $pages[] = collect([]);
        } else {
            // Halaman 1
            $pages[] = $allTickets->slice(0, $firstPageLimit);
            $offset = $firstPageLimit;

            // Halaman 2 dan seterusnya
            while ($offset < $totalCount) {
                $pages[] = $allTickets->slice($offset, $otherPageLimit);
                $offset += $otherPageLimit;
            }
        }

        // Cek apakah halaman terakhir memiliki ruang untuk tanda tangan
        $lastPageItems = end($pages);
        $thresholdSinglePage = ($orientation === 'portrait' ? 20 : 12);
        $needsExtraPage = ($totalCount > $thresholdSinglePage && $lastPageItems->count() > $maxRowsForSignatureOnLastPage);
        $totalPages = count($pages) + ($needsExtraPage ? 1 : 0);

        // Statistik ringkasan
        $countOpen = $tickets->where('status', 'open')->count();
        $countAssigned = $tickets->where('status', 'assigned')->count();
        $countInProgress = $tickets->where('status', 'in_progress')->count();
        $countResolved = $tickets->where('status', 'resolved')->count();
        $countClosed = $tickets->where('status', 'closed')->count();
        $countReopened = $tickets->where('status', 'reopened')->count();
    @endphp

    <!-- Toolbar Layar -->
    <div class="screen-toolbar">
        <div class="toolbar-left">
            <a href="{{ route('reports.index') }}" class="btn btn-back">
                &larr; Kembali ke Laporan
            </a>
        </div>

        <!-- Menu Tata Letak & Zoom Skala Layar -->
        <div class="toolbar-center">
            <!-- Pilihan Tata Letak -->
            <div class="toolbar-group">
                <span class="toolbar-label">Tata Letak:</span>
                <div class="switch-group">
                    <a 
                        href="{{ request()->fullUrlWithQuery(['orientation' => 'portrait']) }}" 
                        class="btn-toggle {{ $orientation === 'portrait' ? 'active' : '' }}"
                        title="Beralih ke format A4 Potret (Tegak)"
                    >
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="5" y="3" width="14" height="18" rx="2" ry="2"></rect>
                            <line x1="9" y1="7" x2="15" y2="7"></line>
                            <line x1="9" y1="11" x2="15" y2="11"></line>
                        </svg>
                        <span>Potret (Tegak)</span>
                    </a>
                    <a 
                        href="{{ request()->fullUrlWithQuery(['orientation' => 'landscape']) }}" 
                        class="btn-toggle {{ $orientation === 'landscape' ? 'active' : '' }}"
                        title="Beralih ke format A4 Lanskap (Mendatar)"
                    >
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="5" width="18" height="14" rx="2" ry="2"></rect>
                            <line x1="7" y1="9" x2="17" y2="9"></line>
                            <line x1="7" y1="13" x2="17" y2="13"></line>
                        </svg>
                        <span>Lanskap (Mendatar)</span>
                    </a>
                </div>
            </div>

            <!-- Zoom Skala Tampilan Layar -->
            <div class="toolbar-group">
                <span class="toolbar-label">Tampilan:</span>
                <div class="switch-group">
                    <button type="button" class="btn-toggle btn-zoom active" data-zoom="1.0" onclick="setScreenZoom(1.0, this)">100%</button>
                    <button type="button" class="btn-toggle btn-zoom" data-zoom="1.2" onclick="setScreenZoom(1.2, this)">120%</button>
                    <button type="button" class="btn-toggle btn-zoom" data-zoom="1.35" onclick="setScreenZoom(1.35, this)">135%</button>
                </div>
            </div>

            <!-- Badges Info -->
            <div class="toolbar-group">
                <span class="badge">{{ $totalCount }} Data Tiket</span>
                <span class="badge badge-accent">{{ $totalPages }} Lembar Halaman</span>
            </div>
        </div>

        <div class="toolbar-right">
            <button onclick="window.print()" class="btn btn-print">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen per Halaman -->
    <div class="document-container" id="document-container">
        @php $globalRowNumber = 1; @endphp

        @foreach ($pages as $pIdx => $pageTickets)
            @php $pageNumber = $pIdx + 1; @endphp

            <div class="word-page" id="page-{{ $pageNumber }}">
                <div class="page-badge-screen">Halaman {{ $pageNumber }} dari {{ $totalPages }} (A4 {{ $orientation === 'portrait' ? 'Potret' : 'Lanskap' }})</div>

                @if ($pageNumber === 1)
                    <!-- KOP SURAT (Halaman 1) -->
                    <div class="kop-surat">
                        <img src="{{ asset('images/logo-kumala.png') }}" alt="Logo PT. Kumala Central Partindo" class="kop-logo">
                        <div class="kop-text">
                            <h2>PT. KUMALA CENTRAL PARTINDO</h2>
                            <h3>DIVISI TEKNOLOGI INFORMASI & OPERASIONAL</h3>
                            <p>Jl. Mayjen Sutoyo S No. 144, RT. 36, Teluk Dalam, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70117</p>
                            <p>Telepon: (0511) 5209969 | HP: 081251562768 / 081256707725 | Email: kcpbjm@yahoo.co.id</p>
                        </div>
                    </div>

                    <!-- JUDUL LAPORAN -->
                    <div class="doc-title">
                        <h4>{{ $reportTitle ?? 'LAPORAN REKAPITULASI PENANGANAN KENDALA IT (IT HELPDESK)' }}</h4>
                        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y - H:i') }} WITA | Oleh: {{ auth()->user()->name }} (ADMINISTRATOR)</p>
                    </div>

                    <!-- RINGKASAN FILTER -->
                    <div class="filter-info">
                        <div><span>Status:</span> <strong>{{ $statusFilter ? strtoupper(str_replace('_', ' ', $statusFilter)) : 'Semua Status' }}</strong></div>
                        <div><span>Kategori:</span> <strong>{{ $categoryName ?? 'Semua Kategori' }}</strong></div>
                        <div><span>Prioritas:</span> <strong>{{ $priorityFilter ? strtoupper($priorityFilter) : 'Semua Prioritas' }}</strong></div>
                        <div><span>Periode:</span> <strong>{{ $periodText }}</strong></div>
                        <div><span>Total Data:</span> <strong>{{ $totalCount }} Tiket</strong></div>
                    </div>
                @else
                    <!-- RUNNING HEADER HALAMAN LANJUTAN -->
                    <div class="running-header">
                        <span><strong>PT. KUMALA CENTRAL PARTINDO</strong> — Divisi Teknologi Informasi</span>
                        <span>{{ $reportTitle ?? 'Laporan Rekapitulasi Kendala IT' }} (Lanjutan)</span>
                    </div>
                @endif

                <!-- TABEL DATA -->
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-ticket">No. Tiket</th>
                            <th class="col-date">Tanggal</th>
                            <th class="col-subject">Subjek Kendala</th>
                            <th class="col-cat">Kategori</th>
                            <th class="col-pri">Prioritas</th>
                            <th class="col-user">Pelapor</th>
                            <th class="col-tech">Petugas<br>IT</th>
                            <th class="col-status">Status</th>
                            <th class="col-res">Tgl<br>Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pageTickets as $t)
                            <tr>
                                <td class="text-center">{{ $globalRowNumber++ }}</td>
                                <td class="text-center" style="font-family: monospace; font-weight: bold; font-size: {{ $orientation === 'portrait' ? '6.5pt' : '7.2pt' }}; white-space: nowrap;">{{ $t->ticket_number }}</td>
                                <td class="text-center" style="font-size: {{ $orientation === 'portrait' ? '6.8pt' : '7.2pt' }}; white-space: nowrap;">{{ $t->created_at->format('d/m/Y') }}</td>
                                <td style="line-height: 1.2;">{{ $t->subject }}</td>
                                <td class="text-center" style="line-height: 1.15;">{{ $t->category?->name ?? '-' }}</td>
                                <td class="text-center" style="font-size: {{ $orientation === 'portrait' ? '6.8pt' : '7.2pt' }};"><span style="white-space: nowrap;">{{ strtoupper($t->priority) }}</span></td>
                                <td class="text-center" style="line-height: 1.15;">{{ $t->creator?->name ?? '-' }}</td>
                                <td class="text-center" style="line-height: 1.15;">{{ $t->activeAssignment?->assignedTo?->name ?? 'Belum Ada' }}</td>
                                <td class="text-center" style="font-weight: 600; font-size: {{ $orientation === 'portrait' ? '6.8pt' : '7.2pt' }}; line-height: 1.15;">
                                    @if ($t->status === 'in_progress')
                                        <span style="white-space: nowrap;">IN</span><br><span style="white-space: nowrap;">PROGRESS</span>
                                    @else
                                        <span style="white-space: nowrap;">{{ strtoupper(str_replace('_', ' ', $t->status)) }}</span>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size: {{ $orientation === 'portrait' ? '6.8pt' : '7.2pt' }}; white-space: nowrap;">{{ $t->resolved_at ? $t->resolved_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center" style="padding: 16px; color: #64748b;">
                                    Tidak ada data kendala IT yang memenuhi kriteria filter laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- BLOK TANDA TANGAN (Jika muat di halaman ini) -->
                @if ($pageNumber === count($pages) && ! $needsExtraPage)
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
                @endif

                <!-- FOOTER HALAMAN -->
                <div class="page-footer">
                    <span>PT. Kumala Central Partindo — Laporan Helpdesk IT</span>
                    <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WITA</span>
                    <span><strong>Halaman {{ $pageNumber }}</strong> dari <strong>{{ $totalPages }}</strong></span>
                </div>
            </div>
        @endforeach

        <!-- HALAMAN PENUTUP KHUSUS (Jika halaman terakhir padat) -->
        @if ($needsExtraPage)
            <div class="word-page" id="page-{{ $totalPages }}">
                <div class="page-badge-screen">Halaman {{ $totalPages }} dari {{ $totalPages }} (Lembar Pengesahan)</div>

                <div class="running-header">
                    <span><strong>PT. KUMALA CENTRAL PARTINDO</strong> — Divisi Teknologi Informasi</span>
                    <span>Lembar Pengesahan & Rekapitulasi Akhir</span>
                </div>

                <div class="doc-title" style="margin-top: 10px;">
                    <h4>LEMBAR PENGESAHAN REKAPITULASI KENDALA IT</h4>
                    <p>Ringkasan rekapitulasi data dan tanda tangan pengesahan resmi</p>
                </div>

                <div class="summary-stats-box">
                    <h5>Ringkasan Status Kendala (Total: {{ $totalCount }} Tiket)</h5>
                    <div class="summary-stats-grid">
                        <div class="summary-item">Open: <strong>{{ $countOpen }} Tiket</strong></div>
                        <div class="summary-item">Assigned: <strong>{{ $countAssigned }} Tiket</strong></div>
                        <div class="summary-item">In Progress: <strong>{{ $countInProgress }} Tiket</strong></div>
                        <div class="summary-item">Resolved: <strong>{{ $countResolved }} Tiket</strong></div>
                        <div class="summary-item">Closed: <strong>{{ $countClosed }} Tiket</strong></div>
                        <div class="summary-item">Reopened: <strong>{{ $countReopened }} Tiket</strong></div>
                    </div>
                </div>

                <div style="font-size: 8pt; color: #334155; margin-bottom: 16px; line-height: 1.4;">
                    Demikian lembar laporan rekapitulasi penanganan kendala teknologi informasi ini dibuat secara otomatis melalui sistem IT Helpdesk Management System PT. Kumala Central Partindo untuk digunakan sebagai bahan evaluasi operasional dan berkas pertanggungjawaban dinas.
                </div>

                <div class="signature-container" style="margin-top: 25px;">
                    <div class="signature-box">
                        <p>Dibuat Oleh,</p>
                        <p class="signature-title">Administrator IT Helpdesk</p>
                        <div class="signature-space" style="height: 50px;"></div>
                        <p class="signature-name">{{ auth()->user()->name }}</p>
                        <p class="signature-title">NIK/ID: {{ auth()->user()->id }}</p>
                    </div>

                    <div class="signature-box">
                        <p>Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="signature-title">Mengetahui / Menyetujui,</p>
                        <p class="signature-title">Kepala Divisi IT & Operasional</p>
                        <div class="signature-space" style="height: 50px;"></div>
                        <p class="signature-name">( .................................................. )</p>
                        <p class="signature-title">Manager IT PT. Kumala Central Partindo</p>
                    </div>
                </div>

                <div class="page-footer">
                    <span>PT. Kumala Central Partindo — Laporan Helpdesk IT</span>
                    <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WITA</span>
                    <span><strong>Halaman {{ $totalPages }}</strong> dari <strong>{{ $totalPages }}</strong></span>
                </div>
            </div>
        @endif
    </div>

    <!-- Script Pengaturan Skala Zoom Layar -->
    <script>
        function setScreenZoom(level, btnElement) {
            var container = document.getElementById('document-container');
            if (container) {
                container.style.zoom = level;
            }
            document.querySelectorAll('.btn-zoom').forEach(function(b) {
                b.classList.remove('active');
            });
            if (btnElement) {
                btnElement.classList.add('active');
            }
            try {
                localStorage.setItem('helpdesk_report_zoom', level);
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', function() {
            try {
                var savedZoom = localStorage.getItem('helpdesk_report_zoom');
                if (savedZoom && savedZoom !== '1.0') {
                    var targetBtn = document.querySelector('.btn-zoom[data-zoom="' + savedZoom + '"]');
                    if (targetBtn) {
                        setScreenZoom(parseFloat(savedZoom), targetBtn);
                    }
                }
            } catch (e) {}
        });
    </script>
</body>
</html>
