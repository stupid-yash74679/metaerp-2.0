<?php

namespace Database\Factories;

use App\Models\Currency; // Correct model namespace
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode(),
            'name' => $this->faker->words(2, true), // e.g., "US Dollar"
            'symbol' => $this->faker->randomElement(['$', '€', '£', '¥']), // Or derive from code
            'exchange_rate' => $this->faker->randomFloat(4, 0.5, 1.5),
            'is_active' => true, // Assuming you have this field, as used in seeder
        ];
    }
}
