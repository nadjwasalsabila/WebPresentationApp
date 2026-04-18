<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    /**
     * Halaman Presentation — hanya tampilkan detail status 'show'
     * Diakses publik tanpa login
     */
    public function show(string $slug)
    {
        $tutorial = Tutorial::where('url_presentation', 'like', "%/presentation/{$slug}")
            ->orWhere('url_presentation', url("presentation/{$slug}"))
            ->firstOrFail();

        $details = $tutorial->details()
            ->where('status', 'show')
            ->orderBy('order')
            ->get();

        return view('presentation.show', compact('tutorial', 'details', 'slug'));
    }

    /**
     * AJAX Polling endpoint — cek perubahan detail untuk auto-refresh
     */
    public function poll(string $slug)
    {
        $tutorial = Tutorial::where('url_presentation', 'like', "%/presentation/{$slug}")
            ->orWhere('url_presentation', url("presentation/{$slug}"))
            ->firstOrFail();

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

    /**
     * URL Finished — generate & stream PDF langsung ke browser
     * Tampilkan SEMUA detail (show + hide)
     */
    public function finished(string $slug)
    {
        // Cari tutorial berdasarkan slug di URL finished
        $tutorial = Tutorial::where('url_finished', 'like', "%/finished/{$slug}")
            ->orWhere('url_finished', url("finished/{$slug}"))
            ->firstOrFail();

        // Ambil SEMUA detail tanpa filter status, urut berdasarkan order
        $details = $tutorial->details()
            ->orderBy('order')
            ->get();

        // Encode semua gambar ke base64 supaya tampil di PDF
        // DomPDF tidak bisa akses path URL langsung, butuh base64
        $detailsWithImages = $details->map(function ($detail) {
            if ($detail->gambar) {
                $path = storage_path('app/public/' . $detail->gambar);
                if (file_exists($path)) {
                    $mime = mime_content_type($path);
                    $data = base64_encode(file_get_contents($path));
                    $detail->gambar_base64 = "data:{$mime};base64,{$data}";
                } else {
                    $detail->gambar_base64 = null;
                }
            } else {
                $detail->gambar_base64 = null;
            }
            return $detail;
        });

        // Generate PDF dari view khusus
        $pdf = Pdf::loadView('presentation.pdf', [
            'tutorial' => $tutorial,
            'details'  => $detailsWithImages,
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => false, // matikan remote URL demi keamanan
            'defaultFont'          => 'DejaVu Sans',
            'dpi'                  => 150,
        ]);

        // Nama file PDF untuk download
        $filename = \Illuminate\Support\Str::slug($tutorial->judul) . '.pdf';

        // 'inline' = tampil di browser | 'attachment' = langsung download
        return $pdf->stream($filename);
    }
}