<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorialDetail extends Model
{
    use HasFactory;

    protected $table = 'tutorial_details';

    protected $fillable = [
        'tutorial_id',
        'text',
        'gambar',
        'code',
        'url',
        'order',
        'status',
    ];

    /**
     * Relasi balik ke Tutorial (Many-to-One / belongsTo).
     * Setiap TutorialDetail dimiliki oleh satu Tutorial.
     */
    public function tutorial()
    {
        return $this->belongsTo(Tutorial::class, 'tutorial_id');
    }

    /**
     * Helper: cek apakah detail ini sedang ditampilkan
     */
    public function isVisible(): bool
    {
        return $this->status === 'show';
    }
}