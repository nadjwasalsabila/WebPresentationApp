<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PresentationController extends Controller
{
    /**
     * Cari tutorial berdasarkan slug yang ada di URL.
     * Slug adalah bagian terakhir dari URL, contoh:
     *   URL: http://localhost:8000/presentation/hello-world-php-123456
     *   Slug: hello-world-php-123456
     *
     * Kita cari dengan LIKE karena URL tersimpan lengkap di DB.
     */
    private function findBySlug(string $field, string $slug): Tutorial
    {
        // Coba exact match dulu (paling cepat)
        $tutorial = Tutorial::where($field, 'like', "%/{$slug}")->first();

        if (!$tutorial) {
            // Fallback: cocokkan bagian akhir slug
            $tutorial = Tutorial::all()->first(function ($t) use ($field, $slug) {
                $stored = $t->$field;
                $storedSlug = basename(parse_url($stored, PHP_URL_PATH));
                return $storedSlug === $slug;
            });
        }

        abort_if(!$tutorial, 404, 'Tutorial tidak ditemukan.');

        return $tutorial;
    }

    /*
    |------------------------------------------------------------------
    | ZONA 2 — Halaman Presentation
    | Hanya tampilkan detail dengan status = show
    |------------------------------------------------------------------
    */
    public function show(string $slug)
    {
        $tutorial = $this->findBySlug('url_presentation', $slug);

        $details = $tutorial->details()
            ->where('status', 'show')
            ->orderBy('order')
            ->get();

        return view('presentation.show', compact('tutorial', 'details', 'slug'));
    }

    /*
    |------------------------------------------------------------------
    | AJAX Polling — dipanggil JS setiap 5 detik
    | Return JSON berisi detail terbaru + hash untuk deteksi perubahan
    |------------------------------------------------------------------
    */
    public function poll(string $slug)
    {
        $tutorial = $this->findBySlug('url_presentation', $slug);

        $details = $tutorial->details()
            ->where('status', 'show')
            ->orderBy('order')
            ->get(['id', 'order', 'status', 'text', 'gambar', 'code', 'url']);

        $hash = md5(
            $details->pluck('id')->implode(',') .
            $details->pluck('status')->implode(',')
        );

        return response()->json([
            'count'   => $details->count(),
            'details' => $details,
            'hash'    => $hash,
        ]);
    }

    /*
    |------------------------------------------------------------------
    | ZONA 3 — Finished PDF
    | Tampilkan SEMUA detail (show + hide) sebagai PDF
    |------------------------------------------------------------------
    */
    public function finished(string $slug)
    {
        $tutorial = $this->findBySlug('url_finished', $slug);

        // Ambil semua detail tanpa filter status
        $details = $tutorial->details()
            ->orderBy('order')
            ->get();

        // Encode gambar ke base64 agar muncul di PDF DomPDF
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

        $filename = Str::slug($tutorial->judul) . '.pdf';

        // stream = buka di browser (bisa print/save dari sana)
        return $pdf->stream($filename);
    }
}