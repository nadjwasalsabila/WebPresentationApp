@extends('layouts.app')

@section('title', 'Manajemen Tutorial')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Manajemen Master Tutorial</h2>
        <a href="{{ route('tutorials.create') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + Tambah Tutorial
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="tabelTutorial" class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Judul</th>
                    <th class="px-4 py-3 text-left">Kode Matkul</th>
                    <th class="px-4 py-3 text-left">Creator</th>
                    <th class="px-4 py-3 text-left">URL Presentation</th>
                    <th class="px-4 py-3 text-left">Dibuat</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($tutorials as $i => $tutorial)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $tutorial->judul }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded">
                            {{ $tutorial->kode_matkul }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $tutorial->creator_email }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ $tutorial->url_presentation }}"
                           target="_blank"
                           class="text-gray-600 hover:underline text-xs break-all">
                            🔗 Buka
                        </a>
                        &nbsp;|&nbsp;
                        <a href="{{ $tutorial->url_finished }}"
                           target="_blank"
                           class="text-gray-600 hover:underline text-xs">
                            📄 PDF
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $tutorial->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            {{-- Tombol kelola detail --}}
                            <a href="{{ route('tutorial-details.index', $tutorial->id) }}"
                               class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs px-3 py-1 rounded font-medium transition">
                                Detail
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('tutorials.edit', $tutorial->id) }}"
                               class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs px-3 py-1 rounded font-medium transition">
                                Edit
                            </a>
                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('tutorials.destroy', $tutorial->id) }}"
                                  onsubmit="return confirm('Hapus tutorial ini beserta semua detailnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs px-3 py-1 rounded font-medium transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi DataTables
    $(document).ready(function () {
        $('#tabelTutorial').DataTable({
            language: {
                search:       "Cari:",
                lengthMenu:   "Tampilkan _MENU_ data",
                info:         "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty:    "Tidak ada data",
                zeroRecords:  "Data tidak ditemukan",
                paginate: {
                    first:    "Pertama",
                    last:     "Terakhir",
                    next:     "Selanjutnya",
                    previous: "Sebelumnya",
                }
            },
            order: [[5, 'desc']], // Sort by created_at descending
            columnDefs: [
                { orderable: false, targets: [6] } // Kolom Aksi tidak bisa di-sort
            ]
        });
    });
</script>
@endpush
