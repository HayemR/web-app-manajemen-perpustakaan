<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Teknologi Informasi',
                'Pemrograman & Rekayasa Perangkat Lunak',
                'Sains & Matematika',
                'Fiksi & Sastra',
                'Sejarah & Kebudayaan',
                'Bisnis & Manajemen',
                'Pengembangan Diri',
            ]),
        ];
    }
}
