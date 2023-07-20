<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Read>
 */
class ReadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_book' => Book::pluck('id')->random(),
            'timestamp' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'time_read' => $this->faker->numberBetween(0, 10),
            'stopped_page' => $this->faker->numberBetween(0, 100),
            'pages_read' => $this->faker->numberBetween(0, 100),
            'time_read_per_page' => $this->faker->numberBetween(0, 10),
            'comments' => $this->faker->text(),
        ];
    }
}
