<?php

namespace Database\Factories;

use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Flight::class;

    public function definition(): array
    {
        // Fetch all airport codes, fallback to static if none
        $airportCodes = Airport::pluck('code')->toArray();
        if (count($airportCodes) < 2) {
            $airportCodes = ['JFK', 'LHR', 'CDG', 'DXB', 'HND'];
        }

        // Ensure the origin and destination are different
        $origin = $this->faker->randomElement($airportCodes);
        do {
            $destination = $this->faker->randomElement($airportCodes);
        } while ($origin === $destination);

        $departure = $this->faker->dateTimeBetween('now', '+1 week');
        $arrival = (clone $departure)->modify('+'.rand(2,12).' hours');

        return [
            'origin' => $origin,
            'destination' => $destination,
            'departure_time' => $departure,
            'arrival_time' => $arrival,
            'price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
