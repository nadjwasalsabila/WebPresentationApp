@extends('layouts.app')

@section('title', 'Tambah Tutorial')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6">

    <div class="mb-6">
        <a href="{{ route('tutorials.index') }}" class="text-gray-600 text-sm hover:underline">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Tambah Tutorial Baru</h2>
        <p class="text-sm text-gray-500 mt-1">URL presentation & finished akan di-generate otomatis.</p>
    </div>

    <form method="POST" action="{{ route('tutorials.store') }}">
        @csrf

        {{-- Judul --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Judul Tutorial <span class="text-gray-500">*</span>
            </label>
            <input
                type="text"
                name="judul"
                value="{{ old('judul') }}"
                placeholder="Contoh: Hello World dengan PHP"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gray-400 focus:outline-none @error('judul') border-gray-400 @enderror"
            >
            @error('judul')
                <p class="text-gray-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kode Mata Kuliah (dari API) --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Mata Kuliah <span class="text-gray-500">*</span>
            </label>

            @if(count($mataKuliahList) > 0)
                <select
                    name="kode_matkul"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gray-400 focus:outline-none @error('kode_matkul') border-gray-400 @enderror"
                >
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($mataKuliahList as $mk)
                        <option
                            value="{{ $mk['kdmk'] }}"
                            {{ old('kode_matkul') == $mk['kdmk'] ? 'selected' : '' }}
                        >
                            {{ $mk['kdmk'] }} — {{ $mk['nama'] }}
                        </option>
                    @endforeach
                </select>
            @else
                {{-- Fallback jika API tidak merespons --}}
                <input
                    type="text"
                    name="kode_matkul"
                    value="{{ old('kode_matkul') }}"
                    placeholder="Masukkan kode matkul manual (API tidak tersedia)"
                    class="w-full border border-gray-400 bg-gray-50 rounded-lg px-4 py-2 text-sm"
                >
                <p class="text-gray-600 text-xs mt-1">
                    ⚠️ Data mata kuliah dari API tidak tersedia. Isi manual.
                </p>
            @endif

            @error('kode_matkul')
                <p class="text-gray-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Info URL --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-6 text-xs text-gray-700">
            ℹ️ <strong>URL Presentation</strong> dan <strong>URL Finished</strong> akan di-generate secara otomatis
            dan dijamin unik. Tidak perlu diisi manual.
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3">
            <button
                type="submit"
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-lg text-sm transition"
            >
                Simpan Tutorial
            </button>
            <a href="{{ route('tutorials.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
