<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tutorial->judul }} — Lengkap</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        :root {
            --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --text: #1a1a1a;
            --text-muted: #666;
            --border: #ddd;
            --code-bg: #f8f8f8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            color: var(--text);
            font-size: 14px;
            line-height: 1.75;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 32px;
        }

        /* Header Dokumen */
        .doc-header {
            border-bottom: 2px solid var(--text);
            padding-bottom: 24px;
            margin-bottom: 40px;
        }

        .doc-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .doc-meta {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .doc-meta span { display: flex; align-items: center; gap: 4px; }

        /* Tombol aksi (tidak muncul saat print) */
        .action-bar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 8px;
            z-index: 100;
        }

        .btn-action {
            background: #1a1a1a;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            font-family: var(--font-body);
            transition: background 0.15s;
        }

        .btn-action:hover { background: #333; }

        /* Blok Langkah */
        .step-block {
            margin-bottom: 36px;
            page-break-inside: avoid;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--text);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .label-hidden {
            background: #fef3c7;
            color: #92400e;
        }

        .step-text {
            padding: 12px 16px;
            border-left: 3px solid var(--border);
            font-size: 14px;
        }

        .step-code-wrapper {
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
        }

        .step-code-header {
            background: #f8f7f4;
            border-bottom: 1px solid var(--border);
            padding: 6px 14px;
            font-size: 11px;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }

        pre { margin: 0 !important; background: var(--code-bg) !important; }
        pre code { font-family: var(--font-mono) !important; font-size: 12px !important; padding: 14px !important; }

        .step-image { max-width: 100%; border-radius: 6px; border: 1px solid var(--border); }

        .step-url {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 13px;
            color: #2563eb;
            word-break: break-all;
        }

        /* Print styles */
        @media print {
            .action-bar { display: none !important; }
            body { padding: 20px; }
            .step-block { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

{{-- Tombol Aksi (tidak muncul saat print/PDF) --}}
<div class="action-bar">
    <button class="btn-action" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <button class="btn-action" style="background:#555" onclick="history.back()">← Kembali</button>
</div>

{{-- Header Dokumen --}}
<div class="doc-header">
    <h1 class="doc-title">{{ $tutorial->judul }}</h1>
    <div class="doc-meta">
        <span>📚 {{ $tutorial->kode_matkul }}</span>
        <span>👤 {{ $tutorial->creator_email }}</span>
        <span>📋 {{ $details->count() }} langkah (lengkap)</span>
        <span>📅 {{ $tutorial->created_at->format('d M Y') }}</span>
    </div>
</div>

{{-- Semua Langkah (termasuk yang hidden) --}}
@foreach($details as $detail)
<div class="step-block">
    <div class="step-header">
        <span class="step-num">{{ $detail->order }}</span>
        @if($detail->status === 'hide')
            <span class="step-label label-hidden">Langkah {{ $detail->order }} (bonus)</span>
        @endif
    </div>

    @if($detail->text)
        <div class="step-text">{!! nl2br(e($detail->text)) !!}</div>
    @endif

    @if($detail->code)
        <div class="step-code-wrapper">
            <div class="step-code-header">Kode Program</div>
            <pre><code>{{ $detail->code }}</code></pre>
        </div>
    @endif

    @if($detail->gambar)
        <img src="{{ Storage::url($detail->gambar) }}"
             alt="Gambar langkah {{ $detail->order }}"
             class="step-image">
    @endif

    @if($detail->url)
        <div class="step-url">
            🔗 <a href="{{ $detail->url }}" target="_blank">{{ $detail->url }}</a>
        </div>
    @endif
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('pre code').forEach(el => hljs.highlightElement(el));
    });
</script>
</body>
</html>