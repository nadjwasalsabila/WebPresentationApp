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
            margin: 22mm 20mm 22mm 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.7;
            color: #1c1c1c;
            background: #ffffff;
        }

        /* =====================
           Watermark / Background
        ===================== */
        .page-watermark {
            position: fixed;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #d0d0d0;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* =====================
           Nomor Halaman
        ===================== */
        .page-number {
            position: fixed;
            bottom: -14mm;
            right: 0;
            font-size: 7.5pt;
            color: #999;
            font-style: italic;
        }

        /* =====================
           Accent Bar (top)
        ===================== */
        .accent-bar {
            width: 100%;
            height: 5pt;
            background: #111111;
            margin-bottom: 0;
            border-radius: 0;
        }

        /* =====================
           Header Dokumen
        ===================== */
        .doc-header {
            padding: 16pt 0 14pt 0;
            margin-bottom: 22pt;
            border-bottom: 1pt solid #e0e0e0;
        }

        .doc-category {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #888;
            margin-bottom: 6pt;
        }

        .doc-title {
            font-size: 22pt;
            font-weight: bold;
            color: #111111;
            margin-bottom: 10pt;
            line-height: 1.2;
        }

        .doc-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6pt;
        }

        .doc-meta-table td {
            font-size: 8pt;
            color: #666;
            padding: 0 14pt 0 0;
            vertical-align: middle;
        }

        .doc-meta-table td:first-child {
            padding: 0 14pt 0 0;
        }

        .badge-matkul {
            display: inline-block;
            background: #111111;
            color: #ffffff;
            border-radius: 2pt;
            padding: 2pt 7pt;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 0.06em;
        }

        .meta-icon {
            color: #aaa;
            margin-right: 2pt;
        }

        /* =====================
           Langkah Blocks
        ===================== */
        .step-block {
            margin-bottom: 18pt;
            page-break-inside: avoid;
        }

        .step-divider {
            border: none;
            border-top: 0.5pt solid #ececec;
            margin: 16pt 0;
        }

        /* Step header row */
        .step-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8pt;
        }

        /* Circle number */
        .step-number-circle {
            width: 22pt;
            height: 22pt;
            background: #111111;
            border-radius: 50%;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            line-height: 22pt;
            display: inline-block;
        }

        /* Type label */
        .step-type-label {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 2pt 7pt;
            border-radius: 2pt;
            vertical-align: middle;
        }

        .label-text   { background: #f2f2f2; color: #333; border: 0.5pt solid #ddd; }
        .label-code   { background: #1c1c1c; color: #e8e8e8; border: 0.5pt solid #111; }
        .label-gambar { background: #f7f7f7; color: #555; border: 0.5pt solid #ddd; }
        .label-url    { background: #f0f0f0; color: #222; border: 0.5pt solid #d0d0d0; }
        .label-hidden { background: #fafafa; color: #999; border: 0.5pt dashed #ccc; }

        /* =====================
           Konten TEXT
        ===================== */
        .step-text {
            font-size: 10pt;
            line-height: 1.75;
            color: #1c1c1c;
            border-left: 3pt solid #111111;
            padding: 4pt 0 4pt 12pt;
            margin: 4pt 0;
            background: #fafafa;
        }

        /* =====================
           Konten CODE
        ===================== */
        .step-code-wrapper {
            border: 0.5pt solid #d0d0d0;
            border-radius: 4pt;
            margin: 4pt 0;
            overflow: hidden;
            background: #fdfdfd;
        }

        .step-code-header {
            background: #1c1c1c;
            border-bottom: none;
            padding: 5pt 12pt;
            font-size: 7.5pt;
            color: #bbb;
            font-family: 'DejaVu Sans Mono', monospace;
            letter-spacing: 0.03em;
        }

        .step-code-header-dots {
            color: #555;
            font-size: 9pt;
            letter-spacing: 2pt;
            margin-right: 6pt;
        }

        .step-code-body {
            background: #f8f8f8;
            padding: 10pt 14pt;
            font-family: 'DejaVu Sans Mono', monospace;
            font-size: 8.5pt;
            line-height: 1.65;
            color: #1a1a1a;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Line numbers column */
        .code-line-num {
            color: #bbb;
            margin-right: 10pt;
            user-select: none;
        }

        /* =====================
           Konten GAMBAR
        ===================== */
        .step-image-wrapper {
            margin: 6pt 0;
            text-align: center;
            border: 0.5pt solid #e0e0e0;
            border-radius: 4pt;
            padding: 6pt;
            background: #fafafa;
        }

        .step-image {
            max-width: 100%;
            max-height: 200pt;
            border-radius: 3pt;
        }

        /* =====================
           Konten URL
        ===================== */
        .step-url-box {
            border: 0.5pt solid #d0d0d0;
            border-radius: 4pt;
            background: #f9f9f9;
            padding: 8pt 12pt;
            margin: 4pt 0;
            font-size: 9pt;
            color: #444;
            word-break: break-all;
        }

        /* =====================
           Hidden marker
        ===================== */
        .hidden-marker {
            font-size: 7pt;
            color: #aaa;
            font-style: italic;
        }

        /* =====================
           Summary Box (bawah header)
        ===================== */
        .summary-box {
            background: #f7f7f7;
            border: 0.5pt solid #e0e0e0;
            border-left: 3pt solid #111111;
            border-radius: 3pt;
            padding: 8pt 12pt;
            margin-bottom: 20pt;
        }

        .summary-box-title {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #888;
            margin-bottom: 4pt;
        }

        .summary-box-text {
            font-size: 9pt;
            color: #333;
        }

        /* =====================
           Section Heading (opsional)
        ===================== */
        .section-heading {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #aaa;
            border-bottom: 0.5pt solid #ececec;
            padding-bottom: 4pt;
            margin-bottom: 14pt;
        }

        /* =====================
           Footer PDF
        ===================== */
        .doc-footer {
            margin-top: 30pt;
            padding-top: 10pt;
            border-top: 1pt solid #e0e0e0;
            font-size: 7.5pt;
            color: #bbb;
            text-align: center;
            letter-spacing: 0.02em;
        }

        .doc-footer strong {
            color: #888;
        }
    </style>
</head>
<body>

    {{-- Nomor Halaman --}}
    <div class="page-number">
        Hal. <span class="pagenum"></span>
    </div>

    {{-- Watermark tipis di footer tiap halaman --}}
    <div class="page-watermark">
        Tutorial App &bull; {{ $tutorial->creator_email }}
    </div>

    {{-- ============================
         Accent bar atas
    ============================ --}}
    <div class="accent-bar"></div>

    {{-- ============================
         Header Dokumen
    ============================ --}}
    <div class="doc-header">
        <div class="doc-category">Dokumen Tutorial &mdash; Panduan Langkah demi Langkah</div>
        <div class="doc-title">{{ $tutorial->judul }}</div>

        <table class="doc-meta-table">
            <tr>
                <td style="width: auto;">
                    <span class="badge-matkul">{{ $tutorial->kode_matkul }}</span>
                </td>
                <td>
                    <span class="meta-icon">&#9993;</span> {{ $tutorial->creator_email }}
                </td>
                <td>
                    <span class="meta-icon">&#128197;</span> {{ $tutorial->created_at->format('d F Y') }}
                </td>
                <td style="text-align: right; color: #999; font-size: 7.5pt;">
                    {{ $details->count() }} langkah
                </td>
            </tr>
        </table>
    </div>

    {{-- ============================
         Summary Box (jumlah langkah)
    ============================ --}}
    <div class="summary-box">
        <div class="summary-box-title">Ringkasan Dokumen</div>
        <div class="summary-box-text">
            Tutorial ini terdiri dari <strong>{{ $details->count() }} langkah</strong> yang mencakup
            @php
                $countText  = $details->where('text','!=',null)->filter(fn($d) => $d->text)->count();
                $countCode  = $details->where('code','!=',null)->filter(fn($d) => $d->code)->count();
                $countImage = $details->where('gambar','!=',null)->filter(fn($d) => $d->gambar)->count();
                $countUrl   = $details->where('url','!=',null)->filter(fn($d) => $d->url)->count();
                $parts = [];
                if ($countText)  $parts[] = "{$countText} teks";
                if ($countCode)  $parts[] = "{$countCode} kode program";
                if ($countImage) $parts[] = "{$countImage} gambar";
                if ($countUrl)   $parts[] = "{$countUrl} referensi URL";
            @endphp
            {{ implode(', ', $parts) }}.
        </div>
    </div>

    {{-- ============================
         Section Label
    ============================ --}}
    <div class="section-heading">&#9646; Langkah-langkah Tutorial</div>

    {{-- ============================
         Semua Langkah
    ============================ --}}
    @foreach($details as $detail)

        @if(!$loop->first)
            <hr class="step-divider">
        @endif

        <div class="step-block">

            {{-- Header Langkah --}}
            <table class="step-header-table">
                <tr>
                    <td style="width: 28pt; vertical-align: middle;">
                        <div class="step-number-circle">{{ $detail->order }}</div>
                    </td>
                    <td style="vertical-align: middle; padding-left: 7pt;">
                        @if($detail->text)
                            <span class="step-type-label label-text">&#9632;&nbsp; Text</span>
                        @elseif($detail->code)
                            <span class="step-type-label label-code">&#9632;&nbsp; Code</span>
                        @elseif($detail->gambar)
                            <span class="step-type-label label-gambar">&#9636;&nbsp; Gambar</span>
                        @elseif($detail->url)
                            <span class="step-type-label label-url">&#128279;&nbsp; URL</span>
                        @endif

                        @if($detail->status === 'hide')
                            &nbsp;<span class="hidden-marker">(referensi &mdash; tidak ditampilkan saat presentasi)</span>
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
                    <div class="step-code-header">
                        <span class="step-code-header-dots">&#9679;&#9679;&#9679;</span>
                        Kode Program &mdash; Langkah {{ $detail->order }}
                    </div>
                    <div class="step-code-body">{{ $detail->code }}</div>
                </div>
            @endif

            {{-- Konten GAMBAR (base64 agar tampil di PDF) --}}
            @if($detail->gambar && $detail->gambar_base64)
                <div class="step-image-wrapper">
                    <img src="{{ $detail->gambar_base64 }}" class="step-image" alt="Gambar langkah {{ $detail->order }}">
                </div>
            @elseif($detail->gambar && !$detail->gambar_base64)
                <div class="step-url-box" style="color: #999; background: #f9f9f9;">
                    &#9888; Gambar tidak ditemukan: {{ basename($detail->gambar) }}
                </div>
            @endif

            {{-- Konten URL --}}
            @if($detail->url)
                <div class="step-url-box">
                    &#128279; <strong>Referensi:</strong> {{ $detail->url }}
                </div>
            @endif

        </div>
    @endforeach

    {{-- ============================
         Footer Dokumen
    ============================ --}}
    <div class="doc-footer">
        Dokumen ini dibuat otomatis oleh <strong>Tutorial App</strong> &bull;
        {{ $tutorial->creator_email }} &bull;
        {{ now()->format('d F Y, H:i') }} WIB
    </div>

</body>
</html>
