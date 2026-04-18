<?php

namespace Database\Seeders;

use App\Models\Tutorial;
use App\Models\TutorialDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TutorialSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // DATA CONTOH 1: Tutorial PHP Hello World
        // (sesuai contoh di dokumen requirement)
        // ==========================================
        $tutorial1 = Tutorial::create([
            'judul'            => 'Hello World dengan PHP',
            'kode_matkul'      => 'A11.64404',
            'url_presentation' => 'http://localhost:8080/presentation/hello-world-dengan-php-5487645461659754',
            'url_finished'     => 'http://localhost:8080/finished/hello-world-dengan-php-8758648921535',
            'creator_email'    => 'nadjwasalsabila@gmail.com',
        ]);

        // Buat 11 detail untuk tutorial ini
        $detailsData = [
            ['text' => 'Pastikan PHP sudah terinstal di komputer Anda. Buka terminal dan ketik: php -v', 'order' => 1, 'status' => 'show'],
            ['text' => 'Buat folder baru bernama hello-world di direktori htdocs atau www.', 'order' => 2, 'status' => 'show'],
            ['code' => "<?php\n// File: index.php\necho 'Hello World';\n?>", 'order' => 3, 'status' => 'hide'],
            ['text' => 'Simpan file dengan nama index.php di dalam folder yang sudah dibuat.', 'order' => 4, 'status' => 'hide'],
            ['url'  => 'http://localhost/hello-world/index.php', 'order' => 5, 'status' => 'hide'],
            ['text' => 'Buka browser dan akses URL di atas. Kamu akan melihat tulisan Hello World.', 'order' => 6, 'status' => 'hide'],
            ['code' => "<?php\n\$nama = 'Mahasiswa';\necho 'Hello, ' . \$nama . '!';\n?>", 'order' => 7, 'status' => 'hide'],
            ['text' => 'Coba ubah variabel $nama dengan nama Anda sendiri dan refresh browser.', 'order' => 8, 'status' => 'hide'],
            ['gambar' => 'uploads/gambar/screenshot-hello-world.png', 'order' => 9, 'status' => 'hide'],
            ['code' => "<?php\nfor(\$i=1; \$i<=5; \$i++) {\n    echo 'Hello ke-' . \$i . '<br>';\n}\n?>", 'order' => 10, 'status' => 'hide'],
            ['text' => 'Selamat! Kamu sudah berhasil membuat program Hello World pertama dengan PHP.', 'order' => 11, 'status' => 'hide'],
        ];

        foreach ($detailsData as $detail) {
            TutorialDetail::create(array_merge(
                ['tutorial_id' => $tutorial1->id, 'text' => null, 'gambar' => null, 'code' => null, 'url' => null],
                $detail
            ));
        }

        // ==========================================
        // DATA CONTOH 2: Tutorial Laravel Dasar
        // ==========================================
        $tutorial2 = Tutorial::create([
            'judul'            => 'Instalasi Laravel 11',
            'kode_matkul'      => 'A11.54314',
            'url_presentation' => 'http://localhost:8080/presentation/instalasi-laravel-11-3948759283749',
            'url_finished'     => 'http://localhost:8080/finished/instalasi-laravel-11-9087654321987',
            'creator_email'    => 'nadjwasalsabila@gmail.com',
        ]);

        $detailsLaravel = [
            ['text' => 'Pastikan PHP versi 8.2 ke atas dan Composer sudah terpasang.', 'order' => 1, 'status' => 'show'],
            ['code' => "composer create-project laravel/laravel nama-project", 'order' => 2, 'status' => 'show'],
            ['code' => "cd nama-project\nphp artisan serve", 'order' => 3, 'status' => 'show'],
            ['text' => 'Buka browser dan akses http://localhost:8000 untuk memastikan Laravel berjalan.', 'order' => 4, 'status' => 'hide'],
            ['gambar' => 'uploads/gambar/laravel-welcome.png', 'order' => 5, 'status' => 'hide'],
        ];

        foreach ($detailsLaravel as $detail) {
            TutorialDetail::create(array_merge(
                ['tutorial_id' => $tutorial2->id, 'text' => null, 'gambar' => null, 'code' => null, 'url' => null],
                $detail
            ));
        }

        // ==========================================
        // DATA ACAK: 3 tutorial tambahan pakai Factory
        // ==========================================
        Tutorial::factory(3)->create()->each(function ($tutorial) {
            // Setiap tutorial dapat 5-8 detail acak dengan order berurutan
            $jumlahDetail = rand(5, 8);
            for ($i = 1; $i <= $jumlahDetail; $i++) {
                TutorialDetail::factory()->create([
                    'tutorial_id' => $tutorial->id,
                    'order'       => $i,
                    'status'      => $i <= 2 ? 'show' : 'hide', // 2 pertama selalu show
                ]);
            }
        });

        $this->command->info('✅ Seeder berhasil: ' . Tutorial::count() . ' tutorial, ' . TutorialDetail::count() . ' detail dibuat.');
    }
}