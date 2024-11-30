<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

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

    $query = Flight::query();

    $query->join('airports as origin_airports', 'flights.origin', '=', 'origin_airports.code')
          ->join('airports as destination_airports', 'flights.destination', '=', 'destination_airports.code');

    // Filter by origin airport name
    $query->where('origin_airports.name', 'LIKE', '%' . $request->origin . '%');

    // If destination is provided, filter by destination airport name
    if ($request->filled('destination')) {
        $query->where('destination_airports.name', 'LIKE', '%' . $request->destination . '%');
    }

    // Fetch flights with their related airports
    $flights = $query->select('flights.*')->with(['originAirport', 'destinationAirport'])->orderBy('departure_time', 'asc')->get();

    return view('flights.index', compact('flights'));
}

}
