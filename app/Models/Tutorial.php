<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;

    protected $table = 'tutorials';

    // Kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'judul',
        'kode_matkul',
        'url_presentation',
        'url_finished',
        'creator_email',
    ];

    /**
     * Relasi One-to-Many ke TutorialDetail.
     * Satu Tutorial punya banyak TutorialDetail.
     */
    public function details()
    {
        return $this->hasMany(TutorialDetail::class, 'tutorial_id')
                    ->orderBy('order', 'asc'); // Selalu tampil urut berdasarkan kolom order
    }

    /**
     * Shortcut: hanya detail dengan status 'show'
     * Dipakai di halaman presentation (URL presentation)
     */
    public function visibleDetails()
    {
        return $this->hasMany(TutorialDetail::class, 'tutorial_id')
                    ->where('status', 'show')
                    ->orderBy('order', 'asc');
    }
}