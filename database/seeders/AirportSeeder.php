<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use a fixed set of airports to guarantee codes for flights
        $airports = [
            ['name' => 'Los Angeles International Airport', 'code' => 'LAX', 'city' => 'Los Angeles', 'country' => 'United States'],
            ['name' => 'John F. Kennedy International Airport', 'code' => 'JFK', 'city' => 'New York', 'country' => 'United States'],
            ['name' => 'Heathrow Airport', 'code' => 'LHR', 'city' => 'London', 'country' => 'United Kingdom'],
            ['name' => 'Tokyo Haneda Airport', 'code' => 'HND', 'city' => 'Tokyo', 'country' => 'Japan'],
            ['name' => 'Dubai International Airport', 'code' => 'DXB', 'city' => 'Dubai', 'country' => 'United Arab Emirates'],
            ['name' => 'Paris Charles de Gaulle Airport', 'code' => 'CDG', 'city' => 'Paris', 'country' => 'France'],
        ];
        foreach ($airports as $airport) {
            \App\Models\Airport::firstOrCreate(['code' => $airport['code']], $airport);
        }
    }
}
