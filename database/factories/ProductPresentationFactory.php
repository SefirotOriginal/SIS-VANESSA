<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPresentation>
 */
class ProductPresentationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'presentation_id' => \App\Models\Presentation::factory(),
            'bar_code' => fake()->unique()->ean13(),
            'content' => fake()->word() . ' ' . fake()->randomElement(['mg', 'ml', 'g', 'L']),
            'formula' => fake()->optional(0.5)->word(),
            'purchase_price' => fake()->randomFloat(2, 1, 100),
            'sale_price' => fake()->randomFloat(2, 10, 200),
        ];
    }
}
