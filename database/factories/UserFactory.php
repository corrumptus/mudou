<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userTypes = [
            'is_admin' => fake()->boolean(),
            'is_teacher' => fake()->boolean(),
            'is_student' => fake()->boolean(),
            'is_monitor' => fake()->boolean()
        ];

        if (array_search(true, $userTypes) == false)
            $userTypes[array_keys($userTypes)[rand(0, 3)]] = true;

        if ($userTypes['is_monitor'] == true && $userTypes['is_student'] == false)
            $userTypes['is_student'] = true;

        return [
            'id' => rand(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'cpf' => fake()->unique()->numerify(str_repeat('#', 11)),
            'birth_date' => fake()->date(),
            'is_admin' => $userTypes['is_admin'],
            'is_teacher' => $userTypes['is_teacher'],
            'is_student' => $userTypes['is_student'],
            'is_monitor' => $userTypes['is_monitor'],
            'profile_picture_hash' => null,
            'is_first_access' => false,
            'is_active' => true
        ];
    }
}
