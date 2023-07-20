<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::pluck('id')->random(),
            'title' => $this->faker->unique()->sentence(3),
            'author' => $this->faker->name(),
            'publisher_year' => $this->faker->year(),
            'genre' => $this->faker->word(),
            'pages' => $this->faker->numberBetween(100, 1000),
            'synopsis' => $this->faker->text(),
            'image' => $this->faker->imageUrl(),
            'how_many_times_read' => $this->faker->numberBetween(0, 5),
        ];
    }
}
