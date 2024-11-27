<?php

namespace Database\Factories;

use App\Models\Flight;
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
        // List of real cities
        $cities = [
            'New York', 'Los Angeles', 'Chicago', 'Houston', 'Miami',
            'San Francisco', 'Seattle', 'Denver', 'Atlanta', 'Dallas',
            'London', 'Paris', 'Tokyo', 'Dubai', 'Sydney', 'Cluj', 'Craiova',
            'Berlin', 'Madrid', 'Rome', 'Beijing', 'Singapore', 'Bucharest',
            'Mumbai', 'Constanta', 'Delhi', 'Seoul', 'Cape Town', 'Jakarta',
            'Hong Kong', 'Moscow', 'Chisinau', 'Iasi', 'Sao Paulo', 'Mexico City',
            'Singapore', 'Barcelona'
        ];

        // Select random cities for origin and destination, ensuring they are different
        $origin = $this->faker->randomElement($cities);
        do {
            $destination = $this->faker->randomElement($cities);
        } while ($origin === $destination);


        return [
            'origin' => $origin,
            'destination' => $destination,
            'departure_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'arrival_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'price' => $this->faker->randomFloat(2, 50, 500), // Prices between 50 and 500
        ];
    }
}
