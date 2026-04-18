<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kode_matkul', 50);
            $table->string('url_presentation')->unique(); // URL untuk tampilan per langkah
            $table->string('url_finished')->unique();     // URL untuk tampilan PDF lengkap
            $table->string('creator_email');
            $table->timestamps(); // created_at & updated_at otomatis
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};