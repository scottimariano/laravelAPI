<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\DiscountRange;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
        /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discount::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'active' => fake()->boolean(75),
            'brand_id' => fake()->numberBetween(1, 3),
            'access_type_code' => fake()->randomElement(['A','B','C']),
            'priority' => fake()->numberBetween(1, 30),
            'region_id' => fake()->numberBetween(1, 4),
            'start_date' => fake()->dateTimeBetween('now', '+1 year'),
            'end_date' => fake()->dateTimeBetween('now', '+1 year')
        ];
    }
}