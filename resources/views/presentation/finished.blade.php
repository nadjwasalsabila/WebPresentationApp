<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tutorial->judul }} — Lengkap</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        :root {
            --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --bg:        #ffffff;
            --bg-light:  #f8f9fa;
            --text:      #1f2937;
            --text-muted:#6b7280;
            --border:    #e5e7eb;
            --dark:      #374151;
            --darker:    #1f2937;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            color: var(--text);
            font-size: 14px;
            line-height: 1.75;
            background: var(--bg);
        }

        /* ===== Top Action Bar ===== */
        .action-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: var(--darker);
            color: #fff;
            padding: 12px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .action-bar-title {
            font-size: 13px;
            font-weight: 600;
            opacity: 0.85;
            letter-spacing: 0.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 50%;
        }

        .action-bar-buttons {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-action {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            font-family: var(--font-body);
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn-action:hover { background: rgba(255,255,255,0.22); }
        .btn-action.primary {
            background: #fff;
            color: var(--darker);
            border-color: #fff;
            font-weight: 600;
        }
        .btn-action.primary:hover { background: #f3f4f6; }

        /* ===== Main Content ===== */
        .main-wrap {
            max-width: 800px;
            margin: 0 auto;
            padding: 96px 32px 60px;
        }

        /* ===== Doc Header ===== */
        .doc-header {
            padding-bottom: 28px;
            margin-bottom: 40px;
            border-bottom: 2px solid var(--darker);
        }

        .doc-badge {
            display: inline-block;
            background: var(--darker);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            letter-spacing: 0.04em;
        }

        .doc-title {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.25;
            color: var(--darker);
            margin-bottom: 14px;
        }

        .doc-meta {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            font-size: 12px;
            color: var(--text-muted);
        }

        .doc-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ===== Step Block ===== */
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
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--darker);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .label-text   { background: #f3f4f6; color: #374151; }
        .label-code   { background: #1f2937; color: #f9fafb; }
        .label-gambar { background: #e5e7eb; color: #374151; }
        .label-url    { background: #374151; color: #f9fafb; }
        .label-hidden { background: #fef3c7; color: #92400e; }

        .step-text {
            padding: 14px 18px;
            border-left: 3px solid var(--border);
            font-size: 14px;
            line-height: 1.8;
            color: var(--text);
        }

        .step-code-wrapper {
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .step-code-header {
            background: var(--bg-light);
            border-bottom: 1px solid var(--border);
            padding: 7px 14px;
            font-size: 11px;
            color: var(--text-muted);
            font-family: var(--font-mono);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-copy {
            background: none;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            cursor: pointer;
            color: var(--text-muted);
            font-family: var(--font-body);
            transition: all 0.15s;
        }
        .btn-copy:hover { background: #fff; color: var(--text); }

        pre { margin: 0 !important; background: #fafafa !important; border-radius: 0 !important; }
        pre code { font-family: var(--font-mono) !important; font-size: 13px !important; padding: 16px !important; }

        .step-image { max-width: 100%; border-radius: 8px; border: 1px solid var(--border); display: block; }

        .step-url {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            color: var(--darker);
            word-break: break-all;
            text-decoration: none;
            background: var(--bg-light);
            transition: background 0.15s;
        }
        .step-url:hover { background: #f0f0f0; }

        /* ===== Divider antara langkah ===== */
        .step-divider {
            border: none;
            border-top: 1px dashed var(--border);
            margin: 32px 0;
        }

        /* ===== Print styles ===== */
        @media print {
            .action-bar { display: none !important; }
            .main-wrap { padding-top: 32px; }
            .step-block { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

{{-- Top Bar --}}
<div class="action-bar">
    <span class="action-bar-title">📄 {{ $tutorial->judul }}</span>
    <div class="action-bar-buttons">
        <button class="btn-action" onclick="history.back()">← Kembali</button>
        <button class="btn-action primary" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    </div>
</div>

<div class="main-wrap">

    {{-- Header Dokumen --}}
    <div class="doc-header">
        <div class="doc-badge">{{ $tutorial->kode_matkul }}</div>
        <h1 class="doc-title">{{ $tutorial->judul }}</h1>
        <div class="doc-meta">
            <span>👤 {{ $tutorial->creator_email }}</span>
            <span>📋 {{ $details->count() }} langkah (lengkap)</span>
            <span>📅 {{ $tutorial->created_at->format('d M Y') }}</span>
        </div>
    </div>

    {{-- Semua Langkah --}}
    @foreach($details as $i => $detail)
        @if($i > 0)<hr class="step-divider">@endif

        <div class="step-block">
            <div class="step-header">
                <span class="step-num">{{ $detail->order }}</span>

                @if($detail->text)
                    <span class="step-label label-text">Text</span>
                @elseif($detail->code)
                    <span class="step-label label-code">Code</span>
                @elseif($detail->gambar)
                    <span class="step-label label-gambar">Gambar</span>
                @elseif($detail->url)
                    <span class="step-label label-url">URL</span>
                @endif

                @if($detail->status === 'hide')
                    <span class="step-label label-hidden">Bonus</span>
                @endif
            </div>

            @if($detail->text)
                <div class="step-text">{!! nl2br(e($detail->text)) !!}</div>
            @endif

            @if($detail->code)
                <div class="step-code-wrapper">
                    <div class="step-code-header">
                        <span>Kode Program</span>
                        <button class="btn-copy" onclick="copyCode(this)">Salin</button>
                    </div>
                    <pre><code>{{ $detail->code }}</code></pre>
                </div>
            @endif

            @if($detail->gambar)
                <img src="{{ Storage::url($detail->gambar) }}"
                     alt="Gambar langkah {{ $detail->order }}"
                     class="step-image">
            @endif

            @if($detail->url)
                <a href="{{ $detail->url }}" target="_blank" class="step-url">
                    🔗 <span>{{ $detail->url }}</span>
                </a>
            @endif
        </div>
    @endforeach

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('pre code').forEach(el => hljs.highlightElement(el));
    });

    function copyCode(btn) {
        const code = btn.closest('.step-code-wrapper').querySelector('code').innerText;
        navigator.clipboard.writeText(code).then(() => {
            btn.textContent = 'Tersalin!';
            setTimeout(() => btn.textContent = 'Salin', 2000);
        });
    }
</script>
</body>
</html>

