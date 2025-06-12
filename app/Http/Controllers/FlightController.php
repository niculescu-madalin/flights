<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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
    
        // Search by origin airport name, city, or code
        $query->where(function ($subQuery) use ($origin) {
            $subQuery->whereRaw('LOWER(REPLACE(origin_airports.name, "  ", " ")) LIKE ?', ['%' . strtolower($origin) . '%'])
                     ->orWhereRaw('LOWER(REPLACE(origin_airports.city, "  ", " ")) LIKE ?', ['%' . strtolower($origin) . '%'])
                     ->orWhereRaw('LOWER(origin_airports.code) = ?', [strtolower($origin)]);
        });
    
        // If destination is provided, search by destination airport name, city, or code
        if ($destination) {
            $query->where(function ($subQuery) use ($destination) {
                $subQuery->whereRaw('LOWER(REPLACE(destination_airports.name, "  ", " ")) LIKE ?', ['%' . strtolower($destination) . '%'])
                         ->orWhereRaw('LOWER(REPLACE(destination_airports.city, "  ", " ")) LIKE ?', ['%' . strtolower($destination) . '%'])
                         ->orWhereRaw('LOWER(destination_airports.code) = ?', [strtolower($destination)]);
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
                  ->orWhere('origin_airports.city', 'LIKE', '%' . $origin . '%')
                  ->orWhereRaw('LOWER(origin_airports.code) = ?', [strtolower($origin)]);
        })
        ->where(function ($query) use ($destination) {
            $query->where('destination_airports.name', 'NOT LIKE', '%' . $destination . '%')
                  ->where('destination_airports.city', 'NOT LIKE', '%' . $destination . '%')
                  ->whereRaw('LOWER(destination_airports.code) != ?', [strtolower($destination)]);
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

    public function pay(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $flight = \App\Models\Flight::findOrFail($request->flight_id);
        $amount = $request->amount;

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'ron',
                    'product_data' => [
                        'name' => 'Flight from ' . $flight->origin . ' to ' . $flight->destination,
                    ],
                    'unit_amount' => intval($amount * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url' => url('/flights/pay/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/flights?canceled=1'),
        ]);

        // Optionally, you can store a pending transaction here
        if (Auth::check()) {
            Transaction::create([
                'user_id' => Auth::id(),
                'flight_id' => $flight->id,
                'stripe_payment_id' => $session->id,
                'amount' => $amount,
                'currency' => 'ron',
                'status' => 'pending',
            ]);
        }

        return redirect($session->url);
    }

    public function paySuccess(Request $request)
    {
        $session_id = $request->get('session_id');
        if (!$session_id) {
            return redirect('/flights')->with('error', 'No session ID.');
        }
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $transaction = Transaction::where('stripe_payment_id', $session_id)->first();
        if ($transaction) {
            $transaction->status = $session->payment_status === 'paid' ? 'succeeded' : $session->payment_status;
            $transaction->save();
        }
        return redirect('/profile/transactions')->with('success', 'Payment successful!');
    }
}
