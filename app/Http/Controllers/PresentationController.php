<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PresentationController extends Controller
{
    /**
     * Ekstrak slug dari full URL yang tersimpan di DB.
     * Contoh input:  "http://localhost:8080/presentation/hello-world-php-123"
     * Contoh output: "hello-world-php-123"
     */
    private function extractSlug(string $fullUrl): string
    {
        return basename(parse_url($fullUrl, PHP_URL_PATH));
    }

    /**
     * Cari tutorial berdasarkan slug.
     * Karena DB simpan full URL, kita pakai LIKE '%-{slug}'
     */
    private function findBySlug(string $field, string $slug): Tutorial
    {
        $tutorial = Tutorial::where($field, 'like', "%{$slug}")->first();

        abort_if(!$tutorial, 404, 'Tutorial tidak ditemukan.');

        return $tutorial;
    }

    // -------------------------------------------------------
    // ZONA 2 — Presentation (publik, hanya status = show)
    // -------------------------------------------------------
    public function show(string $slug)
    {
        $tutorial = $this->findBySlug('url_presentation', $slug);

        $details = $tutorial->details()
            ->where('status', 'show')
            ->orderBy('order')
            ->get();

        return view('presentation.show', compact('tutorial', 'details', 'slug'));
    }

    // -------------------------------------------------------
    // AJAX Polling — auto-refresh tiap 5 detik
    // -------------------------------------------------------
    public function poll(string $slug)
    {
        $tutorial = $this->findBySlug('url_presentation', $slug);

        $details = $tutorial->details()
            ->where('status', 'show')
            ->orderBy('order')
            ->get(['id', 'order', 'status', 'text', 'gambar', 'code', 'url']);

        return response()->json([
            'count'   => $details->count(),
            'details' => $details,
            'hash'    => md5(
                $details->pluck('id')->implode(',') .
                $details->pluck('status')->implode(',')
            ),
        ]);
    }

    // -------------------------------------------------------
    // ZONA 3 — Finished PDF (publik, semua detail)
    // -------------------------------------------------------
    public function finished(string $slug)
    {
        $tutorial = $this->findBySlug('url_finished', $slug);

        $details = $tutorial->details()->orderBy('order')->get();

        $detailsWithImages = $details->map(function ($detail) {
            $detail->gambar_base64 = null;

            if ($detail->gambar) {
                $path = storage_path('app/public/' . $detail->gambar);
                if (file_exists($path)) {
                    $mime = mime_content_type($path);
                    $data = base64_encode(file_get_contents($path));
                    $detail->gambar_base64 = "data:{$mime};base64,{$data}";
                }
            }

            return $detail;
        });

        $pdf = Pdf::loadView('presentation.pdf', [
            'tutorial' => $tutorial,
            'details'  => $detailsWithImages,
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => false,
            'defaultFont'          => 'DejaVu Sans',
            'dpi'                  => 150,
        ]);

        return $pdf->stream(Str::slug($tutorial->judul) . '.pdf');
    }
}