<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('flights.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Flight $flight)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flight $flight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flight $flight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flight $flight)
    {
        //
    }

    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'nullable|string',
        ]);

        $origin = $request->origin;
        $destination = $request->destination;

        $query = Flight::query();

        $query->join('airports as origin_airports', 'flights.origin', '=', 'origin_airports.code')
              ->join('airports as destination_airports', 'flights.destination', '=', 'destination_airports.code');
    
        // Search by origin airport name or city
        $query->where(function ($subQuery) use ($origin) {
            $subQuery->whereRaw('LOWER(REPLACE(origin_airports.name, "  ", " ")) LIKE ?', ['%' . strtolower($origin) . '%'])
                     ->orWhereRaw('LOWER(REPLACE(origin_airports.city, "  ", " ")) LIKE ?', ['%' . strtolower($origin) . '%']);
        });
    
        // If destination is provided, search by destination airport name or city
        if ($destination) {
            $query->where(function ($subQuery) use ($destination) {
                $subQuery->whereRaw('LOWER(REPLACE(destination_airports.name, "  ", " ")) LIKE ?', ['%' . strtolower($destination) . '%'])
                         ->orWhereRaw('LOWER(REPLACE(destination_airports.city, "  ", " ")) LIKE ?', ['%' . strtolower($destination) . '%']);
            });
        }
    
        $flights = $query->select('flights.*')
            ->with(['originAirport', 'destinationAirport'])
            ->orderBy('departure_time', 'asc')
            ->get();
    
        // Query for connecting flights
        $firstLegs = DB::table('flights')
        ->join('airports as origin_airports', 'flights.origin', '=', 'origin_airports.code')
        ->join('airports as destination_airports', 'flights.destination', '=', 'destination_airports.code')
        ->where(function ($query) use ($origin) {
            $query->where('origin_airports.name', 'LIKE', '%' . $origin . '%')
                  ->orWhere('origin_airports.city', 'LIKE', '%' . $origin . '%');
        })
        ->where(function ($query) use ($destination) {
            $query->where('destination_airports.name', 'NOT LIKE', '%' . $destination . '%')
                  ->where('destination_airports.city', 'NOT LIKE', '%' . $destination . '%');
        })
        ->select(
            'flights.*',
            'origin_airports.name as origin_name',
            'origin_airports.city as origin_city',
            'destination_airports.name as stopover_name',
            'destination_airports.city as stopover_city'
        )
        ->get();
        
        $connectingFlights = [];
        foreach ($firstLegs as $firstLeg) {
        $secondLegs = DB::table('flights')
            ->join('airports as origin_airports', 'flights.origin', '=', 'origin_airports.code')
            ->join('airports as destination_airports', 'flights.destination', '=', 'destination_airports.code')
            ->where('flights.origin', '=', $firstLeg->destination) // Second leg starts where first leg ends
            ->where(function ($query) use ($destination) {
                $query->where('destination_airports.name', 'LIKE', '%' . $destination . '%')
                      ->orWhere('destination_airports.city', 'LIKE', '%' . $destination . '%');
            })
            ->select(
                'flights.*',
                'origin_airports.name as stopover_name',
                'origin_airports.city as stopover_city',
                'destination_airports.name as destination_name',
                'destination_airports.city as destination_city'
            )
            ->get();
            
            foreach ($secondLegs as $secondLeg) {
                $connectingFlights[] = [
                    'first_leg' => $firstLeg,
                    'second_leg' => $secondLeg,
                    'total_price' => $firstLeg->price + $secondLeg->price,
                ];
            }
        }


    return view('flights.index', compact('flights', 'connectingFlights'));
    }
}
