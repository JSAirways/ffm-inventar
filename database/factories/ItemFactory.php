<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = \App\Models\Item::class;

    public function definition(): array
    {
        return [
            'description' => fake()->sentence(3),
            'amount' => fake()->numberBetween(1, 10),
            'status' => fake()->randomElement(['available', 'used', 'maintenance']),
            'notes' => fake()->optional()->sentence(),
            'photo_path' => null,
            'location_id' => \App\Models\Location::factory(),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}

