<?php

namespace Database\Factories;

use App\Models\Committee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Committee>
 */
class CommitteeFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'position' => $this->faker->word,
            'access_level' => $this->faker->randomElement([Committee::ACCESS_READ, Committee::ACCESS_WRITE, Committee::ACCESS_ADMIN])
        ];
    }
}
