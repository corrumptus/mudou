<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => rand(),
            'name' => $this->generateName(),
            'code' => fake()->unique()->currencyCode(),
            'amount_semesters' => fake()->randomDigit()+1,
            'is_active' => true
        ];
    }

    private function generateName() {
        do
            $name = fake()->name();
        while (strpos($name, "'") != false);

        return $name;
    }
}
