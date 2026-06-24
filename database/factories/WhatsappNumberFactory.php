<?php

namespace Database\Factories;

use App\Models\WhatsappNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class WhatsappNumberFactory extends Factory
{
    protected $model = WhatsappNumber::class;

    public function definition(): array
    {
        return [
            'label' => fake()->words(2, true),
            'number' => '628' . fake()->numerify('#########'),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
