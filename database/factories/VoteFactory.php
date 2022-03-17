<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $images_url = [];
        for ($i = 0; $i < rand(1, 3); $i++) {
            $images_url[] = $this->faker->imageUrl();
        }
        return [
            'image_urls' => json_encode($images_url),
        ];
    }
}
