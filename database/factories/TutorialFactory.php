<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TutorialFactory extends Factory
{
    public function definition(): array
    {
        $judul = $this->faker->sentence(4, false); // Judul acak
        $slug  = Str::slug($judul);
        $uniqueId = $this->faker->unique()->numerify('##########'); // 10 digit angka unik

        return [
            'judul'            => rtrim($judul, '.'),
            'kode_matkul'      => $this->faker->randomElement([
                'A11.64404', 'A11.54314', 'A11.63301', 'B11.72201'
            ]),
            'url_presentation' => "http://localhost:8080/presentation/{$slug}-{$uniqueId}",
            'url_finished'     => "http://localhost:8080/finished/{$slug}-" . $this->faker->unique()->numerify('#############'),
            'creator_email'    => $this->faker->safeEmail(),
        ];
    }
}