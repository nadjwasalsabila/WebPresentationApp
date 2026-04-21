<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tutorial->judul }}</title>

    {{-- Google Fonts: Notion-like feel --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Highlight.js untuk syntax highlighting kode --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        /* ========================================
           Variabel & Reset
        ======================================== */
        :root {
            --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --bg:          #ffffff;
            --bg-sidebar:  #f7f6f3;
            --text:        #37352f;
            --text-muted:  #9b9a97;
            --text-light:  #c5c4c0;
            --border:      #e9e8e4;
            --accent:      #2eaadc;
            --accent-soft: #e7f5fb;
            --code-bg:     #f1f0ed;
            --step-show:   #2eaadc;
            --step-hide:   #e9e8e4;
            --radius:      6px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--text);
            line-height: 1.7;
            font-size: 16px;
        }

        /* ========================================
           Layout Utama — dua kolom seperti Notion
        ======================================== */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar kiri: daftar langkah */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            padding: 24px 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0 16px;
            margin-bottom: 8px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 16px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 4px;
            margin: 0 8px;
            transition: background 0.1s, color 0.1s;
            cursor: pointer;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: rgba(0,0,0,0.05);
            color: var(--text);
        }

        .sidebar-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--step-show);
            flex-shrink: 0;
        }

        /* Konten utama */
        .main-content {
            flex: 1;
            max-width: 780px;
            margin: 0 auto;
            padding: 60px 80px;
        }

        /* ========================================
           Header Tutorial
        ======================================== */
        .page-header {
            margin-bottom: 48px;
            padding-bottom: 32px;
            border-bottom: 1px solid var(--border);
        }

        .page-emoji {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .page-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .page-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .badge-matkul {
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* ========================================
           Blok Detail (satu langkah)
        ======================================== */
        .step-block {
            margin-bottom: 32px;
            animation: fadeSlideIn 0.3s ease-out;
        }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--text);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-type-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .badge-text   { background: #f0e6ff; color: #7c3aed; }
        .badge-code   { background: #fff3e0; color: #d97706; }
        .badge-gambar { background: #fce7f3; color: #be185d; }
        .badge-url    { background: #e0f2fe; color: #0284c7; }

        /* Konten text */
        .step-text {
            font-size: 15px;
            line-height: 1.8;
            color: var(--text);
            padding: 16px 20px;
            border-left: 3px solid var(--border);
        }

        /* Konten code — syntax highlighted */
        .step-code-wrapper {
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .step-code-toolbar {
            background: #f8f7f4;
            border-bottom: 1px solid var(--border);
            padding: 8px 14px;
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
            transition: all 0.15s;
        }

        .btn-copy:hover { background: var(--bg); color: var(--text); }

        .step-code-wrapper pre {
            margin: 0 !important;
            border-radius: 0 !important;
            background: #fafafa !important;
        }

        .step-code-wrapper pre code {
            font-family: var(--font-mono) !important;
            font-size: 13px !important;
            padding: 16px !important;
        }

        /* Gambar */
        .step-image {
            border-radius: var(--radius);
            border: 1px solid var(--border);
            max-width: 100%;
            display: block;
        }

        /* URL/Link */
        .step-url-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--text);
            transition: background 0.15s;
        }

        .step-url-card:hover { background: var(--bg-sidebar); }
        .step-url-icon { font-size: 20px; }
        .step-url-text { font-size: 13px; color: var(--accent); word-break: break-all; }

        /* ========================================
           Status Bar bawah — Auto Refresh Indicator
        ======================================== */
        .status-bar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--text);
            color: #fff;
            font-size: 12px;
            padding: 8px 14px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0.85;
            z-index: 100;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4ade80;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        /* Pesan kosong */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state-emoji { font-size: 40px; margin-bottom: 12px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { padding: 32px 24px; }
            .page-title { font-size: 26px; }
        }
    </style>
</head>
<body>

<div class="layout">

    {{-- =====================
         SIDEBAR — Daftar Langkah
    ===================== --}}
    <aside class="sidebar">
        <p class="sidebar-title">Langkah Tutorial</p>
        <div id="sidebar-list">
            @foreach($details as $d)
            <a href="#step-{{ $d->order }}" class="sidebar-item">
                <span class="sidebar-dot"></span>
                Langkah {{ $d->order }}
            </a>
            @endforeach
        </div>

        <div style="padding: 16px 16px 0; margin-top: 16px; border-top: 1px solid var(--border);">
            <p style="font-size: 11px; color: var(--text-muted);">
                🔄 Auto refresh aktif<br>
                <span style="color: var(--text-light);">Cek setiap 5 detik</span>
            </p>
        </div>
    </aside>

    {{-- =====================
         KONTEN UTAMA
    ===================== --}}
    <div class="main-content">

        {{-- Header --}}
        <div class="page-header">
            <span class="page-emoji">📚</span>
            <h1 class="page-title">{{ $tutorial->judul }}</h1>
            <div class="page-meta">
                <span class="page-meta-item">
                    <span class="badge-matkul">{{ $tutorial->kode_matkul }}</span>
                </span>
                <span class="page-meta-item">
                    👤 {{ $tutorial->creator_email }}
                </span>
                <span class="page-meta-item" id="step-count">
                    📋 <span id="count-num">{{ $details->count() }}</span> langkah ditampilkan
                </span>
            </div>
        </div>

        {{-- Daftar Langkah --}}
        <div id="steps-container">
            @forelse($details as $detail)
                @include('presentation._step_block', ['detail' => $detail])
            @empty
                <div class="empty-state">
                    <p class="empty-state-emoji">⏳</p>
                    <p>Belum ada langkah yang ditampilkan.</p>
                    <p style="font-size: 13px; margin-top: 8px;">Tunggu dosen membuka langkah berikutnya.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- Status Bar Auto Refresh --}}
<div class="status-bar">
    <span class="pulse-dot"></span>
    <span id="refresh-label">Live</span>
</div>

<script>
// ====================================================
// AUTO REFRESH — Polling setiap 5 detik
// Bandingkan hash dari server dengan hash lokal.
// Jika berbeda → render ulang konten tanpa reload penuh.
// ====================================================

const POLL_URL = window.location.origin + '/presentation/{{ $slug }}/poll';
const POLL_INTERVAL = 5000; // 5 detik
let currentHash = '{{ md5($details->pluck('id')->implode(',') . $details->pluck('status')->implode(',')) }}';

// Inisialisasi highlight.js
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('pre code').forEach(el => hljs.highlightElement(el));
});

// Template HTML untuk satu blok langkah
function renderStep(d) {
    let konten = '';

    if (d.text) {
        konten = `<div class="step-text">${escHtml(d.text).replace(/\n/g,'<br>')}</div>`;
    } else if (d.code) {
        konten = `
            <div class="step-code-wrapper">
                <div class="step-code-toolbar">
                    <span>code</span>
                    <button class="btn-copy" onclick="copyCode(this)">Salin</button>
                </div>
                <pre><code>${escHtml(d.code)}</code></pre>
            </div>`;
    } else if (d.gambar) {
        konten = `<img src="/storage/${d.gambar}" class="step-image" alt="Gambar langkah ${d.order}">`;
    } else if (d.url) {
        konten = `
            <a href="${d.url}" target="_blank" class="step-url-card">
                <span class="step-url-icon">🔗</span>
                <span class="step-url-text">${escHtml(d.url)}</span>
            </a>`;
    }

    const tipe    = d.text ? 'text' : (d.code ? 'code' : (d.gambar ? 'gambar' : 'url'));
    const badges  = { text:'badge-text', code:'badge-code', gambar:'badge-gambar', url:'badge-url' };
    const labels  = { text:'Text', code:'Code', gambar:'Gambar', url:'URL' };

    return `
        <div class="step-block" id="step-${d.order}">
            <div class="step-header">
                <span class="step-number">${d.order}</span>
                <span class="step-type-badge ${badges[tipe]}">${labels[tipe]}</span>
            </div>
            ${konten}
        </div>`;
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function copyCode(btn) {
    const code = btn.closest('.step-code-wrapper').querySelector('code').innerText;
    navigator.clipboard.writeText(code).then(() => {
        btn.textContent = 'Tersalin!';
        setTimeout(() => btn.textContent = 'Salin', 2000);
    });
}

// Render ulang sidebar
function updateSidebar(details) {
    const list = document.getElementById('sidebar-list');
    list.innerHTML = details.map(d =>
        `<a href="#step-${d.order}" class="sidebar-item">
            <span class="sidebar-dot"></span> Langkah ${d.order}
        </a>`
    ).join('');
}

// Polling loop
async function poll() {
    try {
        const res  = await fetch(POLL_URL);
        const data = await res.json();

        document.getElementById('refresh-label').textContent = 'Live';

        // Hanya render ulang jika ada perubahan (hash berbeda)
        if (data.hash !== currentHash) {
            currentHash = data.hash;

            const container = document.getElementById('steps-container');

            if (data.details.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <p class="empty-state-emoji">⏳</p>
                        <p>Belum ada langkah yang ditampilkan.</p>
                    </div>`;
            } else {
                container.innerHTML = data.details.map(renderStep).join('');
                // Re-apply syntax highlighting pada kode baru
                container.querySelectorAll('pre code').forEach(el => hljs.highlightElement(el));
            }

            updateSidebar(data.details);
            document.getElementById('count-num').textContent = data.count;
        }

    } catch (err) {
        document.getElementById('refresh-label').textContent = 'Reconnecting...';
    }
}

// Mulai polling
setInterval(poll, POLL_INTERVAL);
</script>
</body>
</html>

