<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use App\Models\TutorialDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TutorialDetailController extends Controller
{
    /**
     * Tampilkan semua detail dari satu tutorial
     */
    public function index(Tutorial $tutorial)
    {
        // Ambil semua detail, diurutkan berdasarkan kolom 'order'
        $details = $tutorial->details()->orderBy('order')->get();
        return view('tutorial-details.index', compact('tutorial', 'details'));
    }

    /**
     * Tampilkan form tambah detail
     */
    public function create(Tutorial $tutorial)
    {
        // Hitung order berikutnya otomatis (max + 1)
        $nextOrder = $tutorial->details()->max('order') + 1;
        return view('tutorial-details.create', compact('tutorial', 'nextOrder'));
    }

    /**
     * Simpan detail baru ke database
     */
    public function store(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'tipe'   => 'required|in:text,gambar,code,url',
            'order'  => 'required|integer|min:1',
            'status' => 'required|in:show,hide',
            // Validasi konten berdasarkan tipe yang dipilih
            'text'   => 'required_if:tipe,text|nullable|string',
            'code'   => 'required_if:tipe,code|nullable|string',
            'url'    => 'required_if:tipe,url|nullable|url',
            'gambar' => 'required_if:tipe,gambar|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'tipe.required'   => 'Tipe konten wajib dipilih.',
            'order.required'  => 'Urutan (order) wajib diisi.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
            'url.url'         => 'Format URL tidak valid.',
        ]);

        $data = [
            'tutorial_id' => $tutorial->id,
            'order'       => $request->order,
            'status'      => $request->status,
            'text'        => null,
            'gambar'      => null,
            'code'        => null,
            'url'         => null,
        ];

        // Isi kolom sesuai tipe yang dipilih
        switch ($request->tipe) {
            case 'text':
                $data['text'] = $request->text;
                break;
            case 'code':
                $data['code'] = $request->code;
                break;
            case 'url':
                $data['url'] = $request->url;
                break;
            case 'gambar':
                if ($request->hasFile('gambar')) {
                    // Simpan gambar ke storage/app/public/tutorial-images/
                    $path = $request->file('gambar')->store('tutorial-images', 'public');
                    $data['gambar'] = $path;
                }
                break;
        }

        TutorialDetail::create($data);

        return redirect()->route('tutorial-details.index', $tutorial->id)
            ->with('success', 'Detail langkah berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit detail
     */
    public function edit(Tutorial $tutorial, TutorialDetail $detail)
    {
        // Pastikan detail memang milik tutorial ini
        abort_if($detail->tutorial_id !== $tutorial->id, 403);
        return view('tutorial-details.edit', compact('tutorial', 'detail'));
    }

    /**
     * Simpan perubahan detail
     */
    public function update(Request $request, Tutorial $tutorial, TutorialDetail $detail)
    {
        abort_if($detail->tutorial_id !== $tutorial->id, 403);

        $request->validate([
            'tipe'   => 'required|in:text,gambar,code,url',
            'order'  => 'required|integer|min:1',
            'status' => 'required|in:show,hide',
            'text'   => 'required_if:tipe,text|nullable|string',
            'code'   => 'required_if:tipe,code|nullable|string',
            'url'    => 'required_if:tipe,url|nullable|url',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $data = [
            'order'  => $request->order,
            'status' => $request->status,
            'text'   => null,
            'code'   => null,
            'url'    => null,
        ];

        switch ($request->tipe) {
            case 'text':
                $data['text'] = $request->text;
                break;
            case 'code':
                $data['code'] = $request->code;
                break;
            case 'url':
                $data['url'] = $request->url;
                break;
            case 'gambar':
                if ($request->hasFile('gambar')) {
                    // Hapus gambar lama jika ada
                    if ($detail->gambar) {
                        Storage::disk('public')->delete($detail->gambar);
                    }
                    $path = $request->file('gambar')->store('tutorial-images', 'public');
                    $data['gambar'] = $path;
                } else {
                    // Tidak upload gambar baru → pertahankan gambar lama
                    $data['gambar'] = $detail->gambar;
                }
                break;
        }

        $detail->update($data);

        return redirect()->route('tutorial-details.index', $tutorial->id)
            ->with('success', 'Detail berhasil diperbarui!');
    }

    /**
     * Hapus detail
     */
    public function destroy(Tutorial $tutorial, TutorialDetail $detail)
    {
        abort_if($detail->tutorial_id !== $tutorial->id, 403);

        // Hapus file gambar dari storage jika ada
        if ($detail->gambar) {
            Storage::disk('public')->delete($detail->gambar);
        }

        $detail->delete();

        return redirect()->route('tutorial-details.index', $tutorial->id)
            ->with('success', 'Detail berhasil dihapus.');
    }

    /**
     * Toggle status show/hide via AJAX (tombol cepat di halaman index)
     */
    public function toggleStatus(Tutorial $tutorial, TutorialDetail $detail)
    {
        abort_if($detail->tutorial_id !== $tutorial->id, 403);

        $detail->update([
            'status' => $detail->status === 'show' ? 'hide' : 'show'
        ]);

        return response()->json([
            'success'    => true,
            'new_status' => $detail->status,
            'message'    => 'Status berhasil diubah menjadi ' . $detail->status,
        ]);
    }
}