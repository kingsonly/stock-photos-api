<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenges>
 */
class ChallengesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "featured_image" => fake()->image('public/img',640,480, null, false),
            "challenge_name" => fake()->name(),
            "description" => fake()->text(),
            "presented_by" => 1,
            "prize" => fake()->randomDigit(),
            "prize_header" => fake()->text(),
            "prize_description" => fake()->text(),
        ];
    }
}
