<?php

namespace Database\Factories;


use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airport>
 */
class AirportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Airport::class; 

    public function definition(): array
    {

        $airports = [
            ['name' => 'Los Angeles International Airport', 'code' => 'LAX', 'city' => 'Los Angeles', 'country' => 'United States'],
            ['name' => 'John F. Kennedy International Airport', 'code' => 'JFK', 'city' => 'New York', 'country' => 'United States'],
            ['name' => 'Heathrow Airport', 'code' => 'LHR', 'city' => 'London', 'country' => 'United Kingdom'],
            ['name' => 'Tokyo Haneda Airport', 'code' => 'HND', 'city' => 'Tokyo', 'country' => 'Japan'],
            ['name' => 'Dubai International Airport', 'code' => 'DXB', 'city' => 'Dubai', 'country' => 'United Arab Emirates'],
        ];

        $airport = $this->faker->randomElement($airports);

        return [
            'name' => $airport['name'], // Example: "Denver International Airport"
            'code' => $airport['code'], // Random 3-letter code
            'city' => $airport['city'], // Example: "Denver"
            'country' =>$airport['country'], // Example: "United States"
        ];
    }
}
