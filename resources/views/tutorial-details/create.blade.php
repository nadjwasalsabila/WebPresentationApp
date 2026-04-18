@extends('layouts.app')
@section('title', 'Tambah Langkah')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6">

    <div class="mb-6">
        <a href="{{ route('tutorial-details.index', $tutorial->id) }}" class="text-blue-600 text-sm hover:underline">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Tambah Langkah Baru</h2>
        <p class="text-sm text-gray-500">Tutorial: <strong>{{ $tutorial->judul }}</strong></p>
    </div>

    <form method="POST"
          action="{{ route('tutorial-details.store', $tutorial->id) }}"
          enctype="multipart/form-data"
          id="formDetail">
        @csrf

        {{-- Pilih Tipe Konten --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-4 gap-2">
                @foreach(['text' => '📝 Text', 'gambar' => '🖼 Gambar', 'code' => '💻 Code', 'url' => '🔗 URL'] as $val => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="{{ $val }}" class="sr-only peer"
                           {{ old('tipe', 'text') === $val ? 'checked' : '' }}
                           onchange="switchTipe('{{ $val }}')">
                    <div class="text-center border-2 rounded-xl py-3 text-sm font-medium transition
                                peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                                border-gray-200 text-gray-600 hover:border-gray-300">
                        {{ $label }}
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Area Konten (ganti sesuai tipe) --}}
        <div class="mb-5">

            {{-- TEXT --}}
            <div id="area-text" class="konten-area">
                <label class="block text-sm font-medium text-gray-700 mb-1">Teks Penjelasan</label>
                <textarea name="text" rows="5" placeholder="Tulis penjelasan langkah ini..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none resize-y">{{ old('text') }}</textarea>
            </div>

            {{-- GAMBAR --}}
            <div id="area-gambar" class="konten-area hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
                <input type="file" name="gambar" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, GIF, WebP. Maks 2MB.</p>
            </div>

            {{-- CODE --}}
            <div id="area-code" class="konten-area hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cuplikan Kode</label>
                <textarea name="code" rows="8" placeholder="<?php echo 'Hello World'; ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-400 focus:outline-none resize-y bg-gray-900 text-green-400">{{ old('code') }}</textarea>
            </div>

            {{-- URL --}}
            <div id="area-url" class="konten-area hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat URL</label>
                <input type="url" name="url" value="{{ old('url') }}"
                    placeholder="https://contoh.com/referensi"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
        </div>

        {{-- Order & Status --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan (Order)</label>
                <input type="number" name="order" value="{{ old('order', $nextOrder) }}" min="1"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Awal</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="show" {{ old('status', 'show') === 'show' ? 'selected' : '' }}>👁 Show (tampil)</option>
                    <option value="hide" {{ old('status') === 'hide' ? 'selected' : '' }}>🙈 Hide (tersembunyi)</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                Simpan Langkah
            </button>
            <a href="{{ route('tutorial-details.index', $tutorial->id) }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function switchTipe(tipe) {
    // Sembunyikan semua area konten
    document.querySelectorAll('.konten-area').forEach(el => el.classList.add('hidden'));
    // Tampilkan area yang sesuai
    document.getElementById('area-' + tipe).classList.remove('hidden');
}
// Jalankan saat halaman load (untuk old() value)
switchTipe('{{ old('tipe', 'text') }}');
</script>
@endpush