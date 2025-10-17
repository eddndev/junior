<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-2 months', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+4 months');

        return [
            'name' => fake()->catchPhrase(),
            'code' => 'CAMP-' . fake()->unique()->numberBetween(1000, 9999),
            'created_by' => \App\Models\User::factory(),
            'description' => fake()->paragraph(),
            'objectives' => fake()->paragraphs(2, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['planning', 'active', 'paused', 'completed', 'cancelled']),
            'budget' => fake()->randomFloat(2, 5000, 100000),
        ];
    }

    /**
     * Indicate that the campaign is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the campaign is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => fake()->dateTimeBetween('-6 months', '-2 months'),
            'end_date' => fake()->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the campaign is in planning phase.
     */
    public function planning(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'planning',
            'start_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
        ]);
    }
}
