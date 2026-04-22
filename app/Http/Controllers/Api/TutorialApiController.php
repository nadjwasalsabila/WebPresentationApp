<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;

class TutorialApiController extends Controller
{
    public function index($kode_matkul)
    {
        // ambil data berdasarkan kode matkul
        $tutorials = Tutorial::where('kode_matkul', $kode_matkul)->get();

        // format response JSON
        return response()->json([
            "success" => true,
            "total" => $tutorials->count(),
            "data" => $tutorials->map(function ($item) {
                return [
                    "judul" => $item->judul,
                    "url_presentation" => $item->url_presentation,
                    "url_finished" => $item->url_finished,
                    "creator_email" => $item->creator_email,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                ];
            })
        ]);
    }
}