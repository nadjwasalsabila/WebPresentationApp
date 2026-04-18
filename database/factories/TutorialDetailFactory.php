<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TutorialDetailFactory extends Factory
{
    public function definition(): array
    {
        // Tentukan tipe konten secara acak
        $tipe = $this->faker->randomElement(['text', 'gambar', 'code', 'url']);

        return [
            'tutorial_id' => null, // Diisi saat pemanggilan di Seeder
            'text'    => $tipe === 'text'   ? $this->faker->paragraphs(2, true) : null,
            'gambar'  => $tipe === 'gambar' ? 'uploads/gambar/' . $this->faker->image(null, 400, 300, null, false) : null,
            'code'    => $tipe === 'code'   ? "<?php\necho 'Hello World';\n" : null,
            'url'     => $tipe === 'url'    ? $this->faker->url() : null,
            'order'   => 0, // Akan diatur ulang di Seeder supaya berurutan
            'status'  => $this->faker->randomElement(['show', 'hide']),
        ];
    }
}