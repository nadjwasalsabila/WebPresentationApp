<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $tutorial->judul }}</title>
    <style>
        /* ================================================
           CSS untuk DomPDF — hanya CSS2, tanpa flexbox
        ================================================ */

        @page {
            margin: 20mm 18mm 20mm 18mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.65;
            color: #1a1a1a;
            background: #ffffff;
        }

        /* =====================
           Header Dokumen
        ===================== */
        .doc-header {
            border-bottom: 2.5pt solid #1a1a1a;
            padding-bottom: 12pt;
            margin-bottom: 24pt;
        }

        .doc-title {
            font-size: 20pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 6pt;
            line-height: 1.2;
        }

        .doc-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8pt;
        }

        .doc-meta-table td {
            font-size: 8pt;
            color: #555;
            padding: 1pt 12pt 1pt 0;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            background: #f0f0f0;
            border: 0.5pt solid #ccc;
            border-radius: 3pt;
            padding: 1pt 5pt;
            font-size: 7.5pt;
            color: #333;
            font-weight: bold;
        }

        .badge-matkul {
            background: #dbeafe;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        /* =====================
           Nomor Halaman
        ===================== */
        .page-number {
            position: fixed;
            bottom: -14mm;
            right: 0;
            font-size: 7.5pt;
            color: #aaa;
        }

        /* =====================
           Blok Satu Langkah
        ===================== */
        .step-block {
            margin-bottom: 20pt;
            page-break-inside: avoid;
        }

        /* Garis pemisah antar langkah */
        .step-divider {
            border: none;
            border-top: 0.5pt solid #e5e5e5;
            margin-bottom: 16pt;
        }

        /* Header langkah: nomor + tipe */
        .step-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8pt;
        }

        .step-number-circle {
            width: 20pt;
            height: 20pt;
            background: #1a1a1a;
            border-radius: 50%;
            color: #fff;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            line-height: 20pt;
            display: inline-block;
        }

        .step-type-label {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 2pt 6pt;
            border-radius: 3pt;
        }

        .label-text   { background: #ede9fe; color: #5b21b6; border: 0.5pt solid #c4b5fd; }
        .label-code   { background: #fef3c7; color: #92400e; border: 0.5pt solid #fcd34d; }
        .label-gambar { background: #fce7f3; color: #9d174d; border: 0.5pt solid #f9a8d4; }
        .label-url    { background: #e0f2fe; color: #075985; border: 0.5pt solid #7dd3fc; }
        .label-hidden { background: #fff7ed; color: #9a3412; border: 0.5pt solid #fdba74; }

        /* Konten TEXT */
        .step-text {
            font-size: 10pt;
            line-height: 1.7;
            color: #1a1a1a;
            border-left: 2pt solid #d1d5db;
            padding-left: 12pt;
            margin: 4pt 0;
        }

        /* Konten CODE */
        .step-code-wrapper {
            border: 0.5pt solid #d1d5db;
            border-radius: 4pt;
            margin: 4pt 0;
            overflow: hidden;
        }

        .step-code-header {
            background: #f8f7f4;
            border-bottom: 0.5pt solid #d1d5db;
            padding: 4pt 10pt;
            font-size: 7.5pt;
            color: #666;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .step-code-body {
            background: #fafafa;
            padding: 10pt 12pt;
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 8.5pt;
            line-height: 1.6;
            color: #1a1a1a;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Konten GAMBAR */
        .step-image {
            max-width: 100%;
            max-height: 200pt;
            border: 0.5pt solid #d1d5db;
            border-radius: 4pt;
            margin: 4pt 0;
        }

        /* Konten URL */
        .step-url-box {
            border: 0.5pt solid #bfdbfe;
            border-radius: 4pt;
            background: #eff6ff;
            padding: 8pt 12pt;
            margin: 4pt 0;
            font-size: 9pt;
            color: #1d4ed8;
            word-break: break-all;
        }

        /* =====================
           Footer PDF
        ===================== */
        .doc-footer {
            margin-top: 32pt;
            padding-top: 10pt;
            border-top: 0.5pt solid #e5e5e5;
            font-size: 7.5pt;
            color: #aaa;
            text-align: center;
        }

        /* =====================
           Watermark status hidden
        ===================== */
        .hidden-marker {
            font-size: 7pt;
            color: #d97706;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- Nomor Halaman --}}
    <div class="page-number">
        Halaman <span class="pagenum"></span>
    </div>

    {{-- =====================
         Header Dokumen
    ===================== --}}
    <div class="doc-header">
        <div class="doc-title">{{ $tutorial->judul }}</div>
        <table class="doc-meta-table">
            <tr>
                <td><span class="badge badge-matkul">{{ $tutorial->kode_matkul }}</span></td>
                <td>Pembuat: {{ $tutorial->creator_email }}</td>
                <td>Tanggal: {{ $tutorial->created_at->format('d F Y') }}</td>
                <td>{{ $details->count() }} langkah</td>
            </tr>
        </table>
    </div>

    {{-- =====================
         Semua Langkah
    ===================== --}}
    @foreach($details as $detail)

        {{-- Garis pemisah (tidak untuk langkah pertama) --}}
        @if(!$loop->first)
            <hr class="step-divider">
        @endif

        <div class="step-block">

            {{-- Header Langkah --}}
            <table class="step-header-table">
                <tr>
                    <td style="width: 26pt; vertical-align: middle;">
                        <div class="step-number-circle">{{ $detail->order }}</div>
                    </td>
                    <td style="vertical-align: middle; padding-left: 6pt;">
                        {{-- Badge tipe konten --}}
                        @if($detail->text)
                            <span class="step-type-label label-text">Text</span>
                        @elseif($detail->code)
                            <span class="step-type-label label-code">Code</span>
                        @elseif($detail->gambar)
                            <span class="step-type-label label-gambar">Gambar</span>
                        @elseif($detail->url)
                            <span class="step-type-label label-url">URL</span>
                        @endif

                        {{-- Marker kalau langkah ini hidden --}}
                        @if($detail->status === 'hide')
                            &nbsp;<span class="hidden-marker">(referensi — tidak ditampilkan saat presentasi)</span>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Konten TEXT --}}
            @if($detail->text)
                <div class="step-text">{{ $detail->text }}</div>
            @endif

            {{-- Konten CODE --}}
            @if($detail->code)
                <div class="step-code-wrapper">
                    <div class="step-code-header">Kode Program — Langkah {{ $detail->order }}</div>
                    <div class="step-code-body">{{ $detail->code }}</div>
                </div>
            @endif

            {{-- Konten GAMBAR (base64 agar tampil di PDF) --}}
            @if($detail->gambar && $detail->gambar_base64)
                <img src="{{ $detail->gambar_base64 }}" class="step-image" alt="Gambar langkah {{ $detail->order }}">
            @elseif($detail->gambar && !$detail->gambar_base64)
                <div class="step-url-box" style="color: #666; background: #f9f9f9;">
                    ⚠️ Gambar tidak ditemukan: {{ basename($detail->gambar) }}
                </div>
            @endif

            {{-- Konten URL --}}
            @if($detail->url)
                <div class="step-url-box">
                    🔗 {{ $detail->url }}
                </div>
            @endif

        </div>
    @endforeach

    {{-- =====================
         Footer Dokumen
    ===================== --}}
    <div class="doc-footer">
        Dokumen ini dibuat otomatis oleh Tutorial App &bull;
        {{ $tutorial->creator_email }} &bull;
        {{ now()->format('d F Y, H:i') }} WIB
    </div>

</body>
</html>