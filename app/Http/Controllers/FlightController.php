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
        // Validate only the origin as required
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'nullable|string', // Destination is optional
        ]);

        // Start building the query
        $query = Flight::where('origin', $request->origin);

        // Add a filter for destination if provided
        if ($request->filled('destination')) {
            $query->where('destination', $request->destination);
        }

        // Get the results
        $flights = $query->orderBy('departure_time', 'asc')->get();

        return view('flights.index', compact('flights'));
    }
}
