<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User; // Pastikan Anda mengimpor model User jika Anda menggunakannya
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['Technology', 'Health', 'Lifestyle', 'Education']),
            'status' => $this->faker->randomElement(['published', 'archived']),
            'user_id' => User::factory(), // Menggunakan factory User untuk menghasilkan ID user
            'image' => null, // Atur sesuai kebutuhan Anda
        ];
    }
}
