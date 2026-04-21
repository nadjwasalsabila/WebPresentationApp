@extends('layouts.app')

@section('title', 'Detail: ' . $tutorial->judul)

@section('content')
    <div class="bg-white rounded-2xl shadow p-6">

        {{-- Header --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <a href="{{ route('tutorials.index') }}" class="text-gray-600 text-sm hover:underline">← Kembali ke
                    Master</a>
                <h2 class="text-xl font-bold text-gray-800 mt-1">{{ $tutorial->judul }}</h2>
                <p class="text-sm text-gray-500">
                    <span
                        class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5 rounded">{{ $tutorial->kode_matkul }}</span>
                    &nbsp;•&nbsp; {{ $details->count() }} langkah
                </p>
            </div>
            <a href="{{ route('tutorial-details.create', $tutorial->id) }}"
                class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                + Tambah Langkah
            </a>
        </div>

        {{-- URL Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-xs">
                <p class="font-semibold text-gray-700 mb-1">🔗 URL Presentation (per langkah)</p>
                <a href="{{ $tutorial->url_presentation }}" target="_blank" class="text-gray-600 hover:underline break-all">
                    {{ $tutorial->url_presentation }}
                </a>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-xs">
                <p class="font-semibold text-gray-700 mb-1">📄 URL Finished (PDF lengkap)</p>
                <a href="{{ $tutorial->url_finished }}" target="_blank" class="text-gray-600 hover:underline break-all">
                    {{ $tutorial->url_finished }}
                </a>
            </div>
        </div>

        {{-- Daftar Detail --}}
        @if($details->isEmpty())
            <div class="text-center py-12 text-gray-400">
                <p class="text-4xl mb-3">📝</p>
                <p class="text-sm">Belum ada langkah. Klik "Tambah Langkah" untuk mulai.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($details as $detail)
                    <div class="border border-gray-200 rounded-xl p-4 flex items-start gap-4 hover:shadow-sm transition"
                        id="detail-row-{{ $detail->id }}">

                        {{-- Badge urutan --}}
                        <div
                            class="flex-shrink-0 w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-600 text-sm">
                            {{ $detail->order }}
                        </div>

                        {{-- Konten preview --}}
                        <div class="flex-1 min-w-0">
                            @if($detail->text)
                                <span
                                    class="inline-block text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded mb-1">TEXT</span>
                                <p class="text-sm text-gray-700 truncate">{{ Str::limit($detail->text, 100) }}</p>
                            @elseif($detail->code)
                                <span
                                    class="inline-block text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded mb-1">CODE</span>
                                <p class="text-sm text-gray-500 font-mono truncate">{{ Str::limit($detail->code, 80) }}</p>
                            @elseif($detail->gambar)
                                <span
                                    class="inline-block text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded mb-1">GAMBAR</span>
                                <p class="text-sm text-gray-500">{{ basename($detail->gambar) }}</p>
                            @elseif($detail->url)
                                <span
                                    class="inline-block text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded mb-1">URL</span>
                                <p class="text-sm text-gray-600 truncate">{{ $detail->url }}</p>
                            @endif
                        </div>

                        {{-- Toggle Status --}}
                        <div class="flex-shrink-0">
                            <button onclick="toggleStatus({{ $tutorial->id }}, {{ $detail->id }}, this)"
                                data-status="{{ $detail->status }}" class="text-xs font-semibold px-3 py-1.5 rounded-full border transition
                                                                            {{ $detail->status === 'show'
                        ? 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200'
                        : 'bg-gray-100 text-gray-500 border-gray-300 hover:bg-gray-200' }}">
                                {{ $detail->status === 'show' ? '👁 Show' : '🙈 Hide' }}
                            </button>
                        </div>

                        {{-- Aksi Edit & Hapus --}}
                        <div class="flex-shrink-0 flex gap-2">
                            <a href="{{ route('tutorial-details.edit', [$tutorial->id, $detail->id]) }}"
                                class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded font-medium transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('tutorial-details.destroy', [$tutorial->id, $detail->id]) }}"
                                onsubmit="return confirm('Hapus langkah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded font-medium transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Toggle status show/hide via AJAX
         * Tidak perlu reload halaman — langsung update tombol
         */
        function toggleStatus(tutorialId, detailId, btn) {
            btn.disabled = true;
            btn.textContent = '...';

            fetch(`/tutorials/${tutorialId}/details/${detailId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        || '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const isShow = data.new_status === 'show';
                        btn.dataset.status = data.new_status;
                        btn.textContent = isShow ? '👁 Show' : '🙈 Hide';
                        btn.className = `text-xs font-semibold px-3 py-1.5 rounded-full border transition ${isShow
                            ? 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200'
                            : 'bg-gray-100 text-gray-500 border-gray-300 hover:bg-gray-200'
                            }`;
                    }
                })
                .catch(() => {
                    btn.textContent = 'Error';
                })
                .finally(() => {
                    btn.disabled = false;
                });
        }
    </script>
@endpush
