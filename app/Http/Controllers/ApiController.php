<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * GET /api/{kode_matkul}
     *
     * Endpoint publik (tanpa autentikasi) untuk sistem luar seperti SiAdin.
     * Mengembalikan semua tutorial berdasarkan kode mata kuliah.
     *
     * Contoh: GET /api/A11.64404
     */
    public function listByMatkul(string $kode_matkul)
    {
        // Validasi format kode_matkul — cegah injection
        // Format yang valid: A11.64404, B12.54321, dll.
        if (!preg_match('/^[A-Z][0-9]{2}\.[0-9]{4,6}$/i', $kode_matkul)) {
            return response()->json([
                'success' => false,
                'message' => 'Format kode mata kuliah tidak valid.',
                'data'    => [],
            ], 422);
        }

        $tutorials = Tutorial::where('kode_matkul', $kode_matkul)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                // Hanya return field yang dibutuhkan (sesuai spesifikasi)
                return [
                    'judul'            => $t->judul,
                    'url_presentation' => $t->url_presentation,
                    'url_finished'     => $t->url_finished,
                    'creator_email'    => $t->creator_email,
                    'created_at'       => $t->created_at->toIso8601String(),
                    'updated_at'       => $t->updated_at->toIso8601String(),
                ];
            });

        if ($tutorials->isEmpty()) {
            return response()->json([
                'success'      => false,
                'message'      => "Tidak ada tutorial untuk mata kuliah '{$kode_matkul}'.",
                'kode_matkul'  => $kode_matkul,
                'total'        => 0,
                'data'         => [],
            ], 404);
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Data tutorial berhasil diambil.',
            'kode_matkul' => $kode_matkul,
            'total'       => $tutorials->count(),
            'data'        => $tutorials,
        ], 200);
    }
}