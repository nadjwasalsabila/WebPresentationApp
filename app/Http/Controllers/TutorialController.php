<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TutorialController extends Controller
{
    private string $apiBase = 'https://jwt-auth-eight-neon.vercel.app';

    /**
     * Ambil data mata kuliah dari API eksternal.
     * Sertakan refreshToken di header/body sesuai spesifikasi API.
     */
    private function getMataKuliah(): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . session('refreshToken'),
                ])
                ->post("{$this->apiBase}/getMakul", [
                    'refreshToken' => session('refreshToken'),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Sesuaikan key setelah cek response di Postman
                return $data['data'] ?? $data ?? [];
            }
        } catch (\Exception $e) {
            // API tidak merespons, kembalikan array kosong
        }

        return [];
    }

    /**
     * Generate URL presentation yang unik
     * Format: /presentation/{slug-judul}-{random_angka}
     */
    private function generateUrlPresentation(string $judul): string
    {
        do {
            $slug   = Str::slug($judul);
            $random = mt_rand(1000000000, 9999999999);
            $url    = url("presentation/{$slug}-{$random}");
        } while (Tutorial::where('url_presentation', $url)->exists());

        return $url;
    }

    /**
     * Generate URL finished yang unik
     * Format: /finished/{slug-judul}-{random_angka}
     */
    private function generateUrlFinished(string $judul): string
    {
        do {
            $slug   = Str::slug($judul);
            $random = mt_rand(100000000000, 999999999999);
            $url    = url("finished/{$slug}-{$random}");
        } while (Tutorial::where('url_finished', $url)->exists());

        return $url;
    }

    // ================================================
    // INDEX — Tampilkan semua tutorial (DataTable)
    // ================================================
    public function index()
    {
        $tutorials = Tutorial::orderBy('created_at', 'desc')->get();
        return view('tutorials.index', compact('tutorials'));
    }

    // ================================================
    // CREATE — Tampilkan form tambah tutorial
    // ================================================
    public function create()
    {
        // Ambil data mata kuliah dari API
        $mataKuliahList = $this->getMataKuliah();
        return view('tutorials.create', compact('mataKuliahList'));
    }

    // ================================================
    // STORE — Simpan tutorial baru ke database
    // ================================================
    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'kode_matkul' => 'required|string|max:50',
        ], [
            'judul.required'       => 'Judul tutorial wajib diisi.',
            'kode_matkul.required' => 'Mata kuliah wajib dipilih.',
        ]);

        // Generate URL unik otomatis
        $urlPresentation = $this->generateUrlPresentation($request->judul);
        $urlFinished     = $this->generateUrlFinished($request->judul);

        Tutorial::create([
            'judul'            => $request->judul,
            'kode_matkul'      => $request->kode_matkul,
            'url_presentation' => $urlPresentation,
            'url_finished'     => $urlFinished,
            'creator_email'    => session('user_email'),
        ]);

        return redirect()->route('tutorials.index')
            ->with('success', 'Tutorial berhasil ditambahkan!');
    }

    // ================================================
    // EDIT — Tampilkan form edit tutorial
    // ================================================
    public function edit(Tutorial $tutorial)
    {
        $mataKuliahList = $this->getMataKuliah();
        return view('tutorials.edit', compact('tutorial', 'mataKuliahList'));
    }

    // ================================================
    // UPDATE — Simpan perubahan tutorial
    // ================================================
    public function update(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'kode_matkul' => 'required|string|max:50',
        ]);

        $tutorial->update([
            'judul'       => $request->judul,
            'kode_matkul' => $request->kode_matkul,
            // URL TIDAK diubah saat edit — supaya link yang sudah dibagikan tetap valid
        ]);

        return redirect()->route('tutorials.index')
            ->with('success', 'Tutorial berhasil diperbarui!');
    }

    // ================================================
    // DESTROY — Hapus tutorial (beserta semua detailnya via cascade)
    // ================================================
    public function destroy(Tutorial $tutorial)
    {
        $tutorial->delete(); // cascade delete ke tutorial_details otomatis

        return redirect()->route('tutorials.index')
            ->with('success', 'Tutorial berhasil dihapus.');
    }
}