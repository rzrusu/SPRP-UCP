<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReleaseNote>
 */
class ReleaseNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author' => $this->faker->name,
            'slug' => $this->faker->slug,
            'type' => $this->faker->randomElement(['game', 'ucp', 'release']),
            'inline' => $this->faker->boolean,
            'image' => $this->faker->imageUrl(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'added' => $this->faker->sentence,
            'changed' => $this->faker->sentence,
            'removed' => $this->faker->sentence,
            'fixed' => $this->faker->sentence,
            'published_at' => $this->faker->dateTime,
        ];
    }
}
