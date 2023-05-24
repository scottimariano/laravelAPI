<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Discount;
use App\Models\DiscountRange;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DiscountRangeFactory extends Factory
{
            /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscountRange::class;
    
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountIds = Discount::pluck('id')->toArray();

        return [
            'from_days' => fake()->numberBetween(1, 30),
            'to_days' => fake()->numberBetween(31, 60),
            'discount' => fake()->numberBetween(1, 100),
            'code' => fake()->word(),
            'discount_id' => fake()->unique()->randomElement($discountIds)
        ];
    }
}
