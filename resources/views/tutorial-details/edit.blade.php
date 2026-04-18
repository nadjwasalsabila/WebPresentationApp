@extends('layouts.app')
@section('title', 'Edit Langkah')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6">

    <div class="mb-6">
        <a href="{{ route('tutorial-details.index', $tutorial->id) }}" class="text-blue-600 text-sm hover:underline">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Edit Langkah #{{ $detail->order }}</h2>
        <p class="text-sm text-gray-500">Tutorial: <strong>{{ $tutorial->judul }}</strong></p>
    </div>

    {{-- Tentukan tipe saat ini berdasarkan kolom yang terisi --}}
    @php
        $tipeSekarang = $detail->text ? 'text' : ($detail->code ? 'code' : ($detail->gambar ? 'gambar' : 'url'));
    @endphp

    <form method="POST"
          action="{{ route('tutorial-details.update', [$tutorial->id, $detail->id]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Pilih Tipe --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten</label>
            <div class="grid grid-cols-4 gap-2">
                @foreach(['text' => '📝 Text', 'gambar' => '🖼 Gambar', 'code' => '💻 Code', 'url' => '🔗 URL'] as $val => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="tipe" value="{{ $val }}" class="sr-only peer"
                           {{ (old('tipe', $tipeSekarang) === $val) ? 'checked' : '' }}
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

        {{-- Area Konten --}}
        <div class="mb-5">
            <div id="area-text" class="konten-area">
                <label class="block text-sm font-medium text-gray-700 mb-1">Teks Penjelasan</label>
                <textarea name="text" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none resize-y">{{ old('text', $detail->text) }}</textarea>
            </div>

            <div id="area-gambar" class="konten-area hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Baru (kosongkan jika tidak ganti)</label>
                @if($detail->gambar)
                    <div class="mb-2">
                        <img src="{{ Storage::url($detail->gambar) }}" class="h-24 rounded-lg border object-cover" alt="Gambar saat ini">
                        <p class="text-xs text-gray-400 mt-1">Gambar saat ini</p>
                    </div>
                @endif
                <input type="file" name="gambar" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            </div>

            <div id="area-code" class="konten-area hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cuplikan Kode</label>
                <textarea name="code" rows="8" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-400 focus:outline-none resize-y bg-gray-900 text-green-400">{{ old('code', $detail->code) }}</textarea>
            </div>

            <div id="area-url" class="konten-area hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat URL</label>
                <input type="url" name="url" value="{{ old('url', $detail->url) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
        </div>

        {{-- Order & Status --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan (Order)</label>
                <input type="number" name="order" value="{{ old('order', $detail->order) }}" min="1"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    <option value="show" {{ ($detail->status === 'show') ? 'selected' : '' }}>👁 Show</option>
                    <option value="hide" {{ ($detail->status === 'hide') ? 'selected' : '' }}>🙈 Hide</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                Update Langkah
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
    document.querySelectorAll('.konten-area').forEach(el => el.classList.add('hidden'));
    document.getElementById('area-' + tipe).classList.remove('hidden');
}
switchTipe('{{ old('tipe', $tipeSekarang) }}');
</script>
@endpush