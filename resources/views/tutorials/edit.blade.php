@extends('layouts.app')

@section('title', 'Edit Tutorial')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6">

    <div class="mb-6">
        <a href="{{ route('tutorials.index') }}" class="text-gray-600 text-sm hover:underline">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Edit Tutorial</h2>
    </div>

    <form method="POST" action="{{ route('tutorials.update', $tutorial->id) }}">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tutorial</label>
            <input
                type="text"
                name="judul"
                value="{{ old('judul', $tutorial->judul) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gray-400 focus:outline-none @error('judul') border-gray-400 @enderror"
            >
            @error('judul')
                <p class="text-gray-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kode Mata Kuliah --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>

            @if(count($mataKuliahList) > 0)
                <select name="kode_matkul" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gray-400 focus:outline-none">
                    @foreach($mataKuliahList as $mk)
                        <option
                            value="{{ $mk['kdmk'] }}"
                            {{ ($tutorial->kode_matkul == $mk['kdmk']) ? 'selected' : '' }}
                        >
                            {{ $mk['kdmk'] }} — {{ $mk['nama'] }}
                        </option>
                    @endforeach
                </select>
            @else
                <input
                    type="text"
                    name="kode_matkul"
                    value="{{ old('kode_matkul', $tutorial->kode_matkul) }}"
                    class="w-full border border-gray-400 bg-gray-50 rounded-lg px-4 py-2 text-sm"
                >
                <p class="text-gray-600 text-xs mt-1">⚠️ API tidak tersedia. Edit manual.</p>
            @endif
        </div>

        {{-- Tampilkan URL (read-only, tidak bisa diubah) --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">URL Presentation (tidak bisa diubah)</label>
            <input type="text" value="{{ $tutorial->url_presentation }}" readonly
                   class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-sm text-gray-500 cursor-not-allowed">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">URL Finished (tidak bisa diubah)</label>
            <input type="text" value="{{ $tutorial->url_finished }}" readonly
                   class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-sm text-gray-500 cursor-not-allowed">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                Update Tutorial
            </button>
            <a href="{{ route('tutorials.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
