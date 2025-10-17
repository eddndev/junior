<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalCost = fake()->randomFloat(2, 1000, 50000);
        $profitMargin = fake()->randomFloat(2, 10, 40); // 10% to 40% profit margin
        $suggestedPrice = $totalCost + ($totalCost * ($profitMargin / 100));
        $finalPrice = fake()->randomFloat(2, $suggestedPrice * 0.95, $suggestedPrice * 1.05);

        return [
            'quote_number' => 'QUO-' . fake()->unique()->numberBetween(10000, 99999),
            'client_id' => \App\Models\Client::factory(),
            'created_by' => \App\Models\User::factory(),
            'total_cost' => $totalCost,
            'profit_margin_percent' => $profitMargin,
            'suggested_price' => $suggestedPrice,
            'final_price' => $finalPrice,
            'status' => fake()->randomElement(['draft', 'sent', 'accepted', 'rejected', 'expired']),
            'valid_until' => fake()->dateTimeBetween('now', '+30 days'),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the quote has been accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the quote is pending/sent.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
        ]);
    }

    /**
     * Indicate that the quote has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'valid_until' => fake()->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }
}
