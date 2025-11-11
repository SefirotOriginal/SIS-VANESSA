<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $creationDate = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'product_presentation_id' => \App\Models\ProductPresentation::factory(),
            'batch_number' => fake()->unique()->bothify('BATCH-####??'),
            'creation_date' => $creationDate,
            'expiration_date' => fake()->dateTimeBetween($creationDate, '+2 years'),
            'stock' => fake()->numberBetween(10, 1000),
            'min_stock' => fake()->numberBetween(5, 50),
            'max_stock' => fake()->numberBetween(100, 5000),
        ];
    }
}
