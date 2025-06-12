<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $airports = Airport::all();
        return view('airports.index', compact('airports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('airports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:airports,code',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        Airport::create($request->all());

        return redirect()->route('airports.index')->with('success', 'Airport created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Airport $airport)
    {
        return view('airports.show', ['airport' => $airport]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Airport $airport)
    {
        return view('airports.edit', compact('airport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Airport $airport)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:airports,code,' . $airport->id,
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $airport->update($request->all());

        return redirect()->route('airports.index')->with('success', 'Airport updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Airport $airport)
    {
        $airport->delete();

        return redirect()->route('airports.index')->with('success', 'Airport deleted successfully.');
    }

    /**
     * Return airport suggestions for autocomplete.
     */
    public function suggest(Request $request)
    {
        $query = $request->get('q', '');
        $airports = Airport::where('name', 'LIKE', "%{$query}%")
            ->orWhere('city', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->orWhere('id', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'city', 'code', 'country']);
        return response()->json($airports);
    }
}
