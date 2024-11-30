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
         // Fetch all airport codes
         $airportCodes = Airport::pluck('code')->toArray();

         // Ensure the origin and destination are different
         $origin = $this->faker->randomElement($airportCodes);
         do {
             $destination = $this->faker->randomElement($airportCodes);
         } while ($origin === $destination);
 
         return [
             'origin' => $origin,
             'destination' => $destination,
             'departure_time' => $this->faker->dateTimeBetween('now', '+1 week'),
             'arrival_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
             'price' => $this->faker->randomFloat(2, 50, 500),
         ];
    }
}
