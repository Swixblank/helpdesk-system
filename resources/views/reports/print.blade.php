<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Kendala IT - PT. Kumala Central Partindo</title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        /* Latar belakang abu-abu seperti antarmuka Microsoft Word / PDF Viewer */
        body {
            background-color: #525659;
            color: #000;
            line-height: 1.3;
            font-size: 10pt;
            padding: 0;
            margin: 0;
        }

        /* Sticky Toolbar di bagian atas */
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

        .badge {
            background-color: #1e3a8a;
            color: #bfdbfe;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Container seluruh lembar halaman Word */
        .document-container {
            padding: 24px 0 48px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        /* Lembar Halaman A4 per halaman seperti di Microsoft Word */
        .word-page {
            width: 210mm;
            min-height: 297mm;
            height: 297mm;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25), 0 0 2px rgba(0, 0, 0, 0.15);
            padding: 16mm 18mm 14mm 18mm;
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Tag indikator nomor halaman di atas kertas saat mode layar */
        .page-badge-screen {
            position: absolute;
            top: -18px;
            left: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Kop Surat Perusahaan (Hanya di Halaman 1) */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 15px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h2 {
            font-size: 14.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .kop-text h3 {
            font-size: 11.5pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
        }

        .kop-text p {
            font-size: 8.5pt;
            margin: 1px 0;
            color: #333;
        }

        /* Judul Laporan */
        .doc-title {
            text-align: center;
            margin-bottom: 10px;
        }

        .doc-title h4 {
            font-size: 11.5pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title p {
            font-size: 8.5pt;
            margin-top: 3px;
            color: #475569;
        }

        /* Running Header di Halaman 2, 3, dst. */
        .running-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #94a3b8;
            padding-bottom: 4px;
            margin-bottom: 12px;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            color: #475569;
        }

        .running-header strong {
            color: #0f172a;
        }

        /* Filter Info Bar */
        .filter-info {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-info span {
            color: #64748b;
        }

        .filter-info strong {
            color: #0f172a;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        table.data-table th, 
        table.data-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.3px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .nowrap { white-space: nowrap; }

        .col-no { width: 26px; }
        .col-ticket { width: 85px; }
        .col-date { width: 62px; }
        .col-cat { width: 75px; }
        .col-pri { width: 55px; }
        .col-user { width: 75px; }
        .col-tech { width: 80px; }
        .col-status { width: 65px; }
        .col-res { width: 62px; }

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
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 44%;
            text-align: center;
            font-size: 9.5pt;
        }

        .signature-space {
            height: 55px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-title {
            font-size: 8.5pt;
            color: #333;
        }

        /* Box Rekap Status di Lembar Penutup */
        .summary-stats-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 15px 0 20px 0;
            font-family: Arial, sans-serif;
        }

        .summary-stats-box h5 {
            font-size: 9.5pt;
            color: #1e3a8a;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .summary-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            font-size: 8.5pt;
        }

        .summary-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            border-radius: 4px;
        }

        /* Media Cetak (Print & Save as PDF) */
        @media print {
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
            }

            .word-page {
                width: 100% !important;
                min-height: 297mm !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 14mm 16mm 12mm 16mm !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always !important;
                break-after: page !important;
            }

            .word-page:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    @php
        // Pembagian baris data per lembar halaman Word
        $firstPageLimit = 12; // Halaman 1 memuat Kop Surat, Judul, & Filter Info
        $otherPageLimit = 16; // Halaman berikutnya memuat tabel & running header

        $allTickets = $tickets->values();
        $totalCount = $allTickets->count();
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

        // Cek apakah halaman terakhir memiliki ruang untuk tanda tangan (<= 10 baris)
        // Jika > 10 baris, buat 1 lembar halaman penutup khusus tanda tangan & rekap
        $lastPageItems = end($pages);
        $needsExtraPage = ($lastPageItems->count() > 10);
        $totalPages = count($pages) + ($needsExtraPage ? 1 : 0);

        // Hitung statistik untuk rekapitulasi
        $countOpen = $tickets->where('status', 'open')->count();
        $countAssigned = $tickets->where('status', 'assigned')->count();
        $countInProgress = $tickets->where('status', 'in_progress')->count();
        $countResolved = $tickets->where('status', 'resolved')->count();
        $countClosed = $tickets->where('status', 'closed')->count();
        $countReopened = $tickets->where('status', 'reopened')->count();
    @endphp

    <!-- Toolbar Layar (Tidak ikut tercetak saat Save to PDF) -->
    <div class="screen-toolbar">
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-back">
                &larr; Kembali ke Laporan
            </a>
        </div>

        <div class="toolbar-info">
            <span>Tata Letak: <strong style="color: #ffffff;">Microsoft Word (A4 Portrait)</strong></span>
            <span class="badge">{{ $totalCount }} Data</span>
            <span class="badge">{{ $totalPages }} Lembar Halaman</span>
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

    <!-- Lembar-Lembar Dokumen (Per Halaman) -->
    <div class="document-container">
        @php $globalRowNumber = 1; @endphp

        @foreach ($pages as $pIdx => $pageTickets)
            @php $pageNumber = $pIdx + 1; @endphp

            <div class="word-page" id="page-{{ $pageNumber }}">
                <div class="page-badge-screen">Halaman {{ $pageNumber }} dari {{ $totalPages }}</div>

                @if ($pageNumber === 1)
                    <!-- KOP SURAT (Hanya Halaman 1) -->
                    <div class="kop-surat">
                        <img src="{{ asset('images/logo-kumala.png') }}" alt="Logo PT. Kumala Central Partindo" class="kop-logo">
                        <div class="kop-text">
                            <h2>PT. KUMALA CENTRAL PARTINDO</h2>
                            <h3>DIVISI TEKNOLOGI INFORMASI & OPERASIONAL</h3>
                            <p>Jl. Mayjen Sutoyo S No. 144, RT. 36, Teluk Dalam, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70117</p>
                            <p>Telepon: (0511) 5209969 | HP: 081251562768 / 081256707725 | Email: kcpbjm@yahoo.co.id</p>
                        </div>
                    </div>

                    <!-- JUDUL DOKUMEN -->
                    <div class="doc-title">
                        <h4>LAPORAN REKAPITULASI PENANGANAN KENDALA IT (IT HELPDESK)</h4>
                        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y - H:i') }} WITA | Oleh: {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->getRoleNames()->first() ?? 'Staff') }})</p>
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
                    <!-- RUNNING HEADER (Halaman 2, 3, dst.) -->
                    <div class="running-header">
                        <span><strong>PT. KUMALA CENTRAL PARTINDO</strong> — Divisi Teknologi Informasi</span>
                        <span>Laporan Rekapitulasi Kendala IT (Lanjutan)</span>
                    </div>
                @endif

                <!-- TABEL DATA HALAMAN INI -->
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-ticket">No. Tiket</th>
                            <th class="col-date">Tanggal</th>
                            <th>Subjek Kendala</th>
                            <th class="col-cat">Kategori</th>
                            <th class="col-pri">Prioritas</th>
                            <th class="col-user">Pelapor</th>
                            <th class="col-tech">Petugas IT</th>
                            <th class="col-status">Status</th>
                            <th class="col-res">Tgl Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pageTickets as $t)
                            <tr>
                                <td class="text-center">{{ $globalRowNumber++ }}</td>
                                <td class="nowrap" style="font-family: monospace; font-weight: bold; font-size: 8pt;">{{ $t->ticket_number }}</td>
                                <td class="text-center nowrap">{{ $t->created_at->format('d/m/Y') }}</td>
                                <td>{{ $t->subject }}</td>
                                <td>{{ $t->category?->name ?? '-' }}</td>
                                <td class="text-center">{{ strtoupper($t->priority) }}</td>
                                <td>{{ $t->creator?->name ?? '-' }}</td>
                                <td>{{ $t->activeAssignment?->assignedTo?->name ?? 'Belum Ada' }}</td>
                                <td class="text-center nowrap" style="font-weight: 600;">{{ strtoupper(str_replace('_', ' ', $t->status)) }}</td>
                                <td class="text-center nowrap">{{ $t->resolved_at ? $t->resolved_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center" style="padding: 20px; color: #64748b;">
                                    Tidak ada data kendala IT yang memenuhi kriteria filter laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- JIKA INI HALAMAN TERAKHIR DAN TANDA TANGAN MUAT DI HALAMAN INI -->
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

        <!-- HALAMAN PENUTUP KHUSUS (Jika baris di halaman terakhir padat > 10 baris) -->
        @if ($needsExtraPage)
            <div class="word-page" id="page-{{ $totalPages }}">
                <div class="page-badge-screen">Halaman {{ $totalPages }} dari {{ $totalPages }} (Lembar Pengesahan)</div>

                <!-- RUNNING HEADER -->
                <div class="running-header">
                    <span><strong>PT. KUMALA CENTRAL PARTINDO</strong> — Divisi Teknologi Informasi</span>
                    <span>Lembar Pengesahan & Rekapitulasi Akhir</span>
                </div>

                <div class="doc-title" style="margin-top: 15px;">
                    <h4>LEMBAR PENGESAHAN REKAPITULASI KENDALA IT</h4>
                    <p>Ringkasan rekapitulasi data dan tanda tangan pengesahan resmi</p>
                </div>

                <!-- RINGKASAN AKHIR DOKUMEN -->
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

                <div style="font-size: 9.5pt; color: #334155; margin-bottom: 25px; line-height: 1.5;">
                    Demikian lembar laporan rekapitulasi penanganan kendala teknologi informasi ini dibuat secara otomatis melalui sistem IT Helpdesk Management System PT. Kumala Central Partindo untuk digunakan sebagai bahan evaluasi operasional dan berkas pertanggungjawaban dinas.
                </div>

                <!-- BLOK TANDA TANGAN RESMI -->
                <div class="signature-container" style="margin-top: 40px;">
                    <div class="signature-box">
                        <p>Dibuat Oleh,</p>
                        <p class="signature-title">Administrator IT Helpdesk</p>
                        <div class="signature-space" style="height: 70px;"></div>
                        <p class="signature-name">{{ auth()->user()->name }}</p>
                        <p class="signature-title">NIK/ID: {{ auth()->user()->id }}</p>
                    </div>

                    <div class="signature-box">
                        <p>Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="signature-title">Mengetahui / Menyetujui,</p>
                        <p class="signature-title">Kepala Divisi IT & Operasional</p>
                        <div class="signature-space" style="height: 70px;"></div>
                        <p class="signature-name">( .................................................. )</p>
                        <p class="signature-title">Manager IT PT. Kumala Central Partindo</p>
                    </div>
                </div>

                <!-- FOOTER HALAMAN PENUTUP -->
                <div class="page-footer">
                    <span>PT. Kumala Central Partindo — Laporan Helpdesk IT</span>
                    <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WITA</span>
                    <span><strong>Halaman {{ $totalPages }}</strong> dari <strong>{{ $totalPages }}</strong></span>
                </div>
            </div>
        @endif
    </div>

</body>
</html>
