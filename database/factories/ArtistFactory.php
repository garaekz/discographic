<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => $this->faker->imageUrl(),
            'region' => $this->faker->country,
            'bio' => $this->faker->paragraph,
            'links' => [
                [
                    'name' => 'spotify',
                    'url' => $this->faker->url,
                ],
                [
                    'name' => 'youtube',
                    'url' => $this->faker->url,
                ],
                [
                    'name' => 'website',
                    'url' => $this->faker->url,
                ],
            ],
        ];
    }
}
