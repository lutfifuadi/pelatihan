<?php

namespace Database\Factories;

use App\Models\Dinas;
use Illuminate\Database\Eloquent\Factories\Factory;

class DinasFactory extends Factory
{
    protected $model = Dinas::class;

    public function definition(): array
    {
        return [
            'nama_dinas' => fake()->unique()->company(),
            'singkatan' => fake()->unique()->lexify('???'),
            'is_active' => true,
        ];
    }
}
