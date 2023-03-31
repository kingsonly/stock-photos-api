<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\stockfiles>
 */
class StockfilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $getRandomNumber = rand(0,1);
        $types = ["image","video"];
        return [
            "user_id" => 1,
            "file_path" => fake()->image('public/img',640,480, null, false),
            "location" => fake()->text(),
            "file_type" => $types[$getRandomNumber],
            "name" => fake()->name(),
            "views" => fake()->randomDigit(),
        ];
    }
}
