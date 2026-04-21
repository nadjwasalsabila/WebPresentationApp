{{-- 
    Partial reusable untuk satu blok langkah tutorial.
    Dipakai di presentation/show.blade.php (render awal, sebelum JS polling aktif)
--}}
<div class="step-block" id="step-{{ $detail->order }}">
    <div class="step-header">
        <span class="step-number">{{ $detail->order }}</span>
        @if($detail->text)
            <span class="step-type-badge badge-text">Text</span>
        @elseif($detail->code)
            <span class="step-type-badge badge-code">Code</span>
        @elseif($detail->gambar)
            <span class="step-type-badge badge-gambar">Gambar</span>
        @elseif($detail->url)
            <span class="step-type-badge badge-url">URL</span>
        @endif
    </div>

    {{-- Konten TEXT --}}
    @if($detail->text)
        <div class="step-text">{!! nl2br(e($detail->text)) !!}</div>
    @endif

    {{-- Konten CODE dengan Syntax Highlighting --}}
    @if($detail->code)
        <div class="step-code-wrapper">
            <div class="step-code-toolbar">
                <span>code</span>
                <button class="btn-copy" onclick="copyCode(this)">Salin</button>
            </div>
            <pre><code>{{ $detail->code }}</code></pre>
        </div>
    @endif

    {{-- Konten GAMBAR --}}
    @if($detail->gambar)
        <img src="{{ Storage::url($detail->gambar) }}"
             alt="Gambar langkah {{ $detail->order }}"
             class="step-image">
    @endif

    {{-- Konten URL --}}
    @if($detail->url)
        <a href="{{ $detail->url }}" target="_blank" class="step-url-card">
            <span class="step-url-icon">🔗</span>
            <span class="step-url-text">{{ $detail->url }}</span>
        </a>
    @endif
</div>

