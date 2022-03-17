<?php

namespace Database\Factories;

use App\Models\User;
use Bluemmb\Faker\PicsumPhotosProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->faker->addProvider(new PicsumPhotosProvider($this->faker));
        return [
            'user_id'=> User::factory()->create()->id,
            'name' => $this->faker->sentence(4),
            'image_url' => $this->faker->imageUrl(),
            'description' => $this->faker->text(200),
            'start_date' => $start = $this->faker->dateTime(),
            'end_date' => $this->faker->dateTimeBetween($start,$start->format('Y-m-d H:i:s').' +2 days'),
        ];
    }
}
