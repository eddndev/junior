<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 50000, 500000);
        $spentAmount = fake()->randomFloat(2, 0, $totalAmount * 0.9);
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+1 year');

        return [
            'name' => fake()->catchPhrase() . ' Budget',
            'code' => 'BUD-' . fake()->unique()->numberBetween(1000, 9999),
            'area_id' => \App\Models\Area::factory(),
            'total_amount' => $totalAmount,
            'spent_amount' => $spentAmount,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['draft', 'active', 'completed', 'cancelled']),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the budget is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the budget is fully consumed.
     */
    public function fullyConsumed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'spent_amount' => $attributes['total_amount'],
                'status' => 'completed',
            ];
        });
    }
}
