<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure airports exist before creating flights
        if (\App\Models\Airport::count() < 2) {
            \App\Models\Airport::factory()->count(5)->create();
        }
        Flight::factory()->count(50)->create();
    }
}
