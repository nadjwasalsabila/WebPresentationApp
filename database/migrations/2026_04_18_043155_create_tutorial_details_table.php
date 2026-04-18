<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutorial_details', function (Blueprint $table) {
            $table->id();

            // Foreign Key ke tabel tutorials
            $table->foreignId('tutorial_id')
                  ->constrained('tutorials')
                  ->onDelete('cascade'); // Jika master dihapus, detail ikut terhapus

            // Konten detail (semua nullable karena tidak harus diisi bersamaan)
            $table->text('text')->nullable();          // Konten teks/paragraf
            $table->string('gambar')->nullable();      // Path file gambar
            $table->text('code')->nullable();          // Cuplikan kode program
            $table->string('url')->nullable();         // Link URL eksternal

            $table->unsignedInteger('order')->default(0); // Urutan tampil
            $table->enum('status', ['show', 'hide'])->default('show'); // Visibilitas

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutorial_details');
    }
};