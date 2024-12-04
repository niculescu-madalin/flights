<x-app-layout>
    <!-- Search Form -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <form 
            method="POST" 
            action="{{ route('flights.search') }}" 
            class="">
            @csrf
            <div class="flex flex-row items-center gap-2">
                <div class="col-md-4 grow-1 w-full flex flex-row items-center text-slate-400">
                    <x-text-input id="origin" 
                                type="text" 
                                name="origin" 
                                class="w-full form-control p-4" 
                                placeholder="Origin" 
                                required />
                </div>
                <div class="col-md-4 grow-1 w-full">
                    <x-text-input id="destination" 
                                type="text" 
                                name="destination" 
                                class="form-control w-full p-4" 
                                placeholder="Destination (optional)" />
                </div>
                <div class="col-md-4 h-full">
                    <x-primary-button 
                    type="submit" 
                    class="btn btn-primary w-100 h-full py-4">
                        Search
                    </x-primary-button>
                </div>
            </div>
        </form> 
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-900 dark:text-gray-100">
                <!-- Search Results -->
                @if(isset($flights))
                    <h2 class="mb-4 text-2xl font-semibold dark:text-white">Direct Flights</h2>
                    @if($flights->isEmpty())
                        <p>No flights found for the selected route.</p>
                    @else
                        <div class="flex flex-col gap-1 ">
                            @foreach($flights as $flight)
                                @php
                                    $d1 = new DateTime($flight->departure_time);
                                    $d2 = new DateTime($flight->arrival_time);
                                    $interval = $d1->diff($d2);
                                @endphp
                                <div class="px-4 py-4 grid grid-cols-12 gap-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="col-span-4 flex flex-col gap-2 justify-center">
                                        <span>
                                            <span class="text-xl flex gap-4 items-center font-semibold"> 
                                                {{ $flight->originAirport->city }}
                                            </span>
                                            <span class="flex flex-row items-center gap-1">
                                                <span class="font-semibold text-sm text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 512 512"><path fill="currentColor" d="M186.62 464H160a16 16 0 0 1-14.57-22.6l64.46-142.25L113.1 297l-35.3 42.77C71.07 348.23 65.7 352 52 352H34.08a17.66 17.66 0 0 1-14.7-7.06c-2.38-3.21-4.72-8.65-2.44-16.41l19.82-71c.15-.53.33-1.06.53-1.58a.4.4 0 0 0 0-.15a15 15 0 0 1-.53-1.59l-19.84-71.45c-2.15-7.61.2-12.93 2.56-16.06a16.83 16.83 0 0 1 13.6-6.7H52c10.23 0 20.16 4.59 26 12l34.57 42.05l97.32-1.44l-64.44-142A16 16 0 0 1 160 48h26.91a25 25 0 0 1 19.35 9.8l125.05 152l57.77-1.52c4.23-.23 15.95-.31 18.66-.31C463 208 496 225.94 496 256c0 9.46-3.78 27-29.07 38.16c-14.93 6.6-34.85 9.94-59.21 9.94c-2.68 0-14.37-.08-18.66-.31l-57.76-1.54l-125.36 152a25 25 0 0 1-19.32 9.75"/></svg>
                                                </span>
                                                <span class="font-semibold text-sm text-slate-400">
                                                    {{ $flight->originAirport->code }}
                                                </span>
                                                <span class="font-semibold text-sm text-slate-500">
                                                    {{ $flight->originAirport->name }}
                                                </span>
                                            </span>
                                        </span>
                                        <span class="flex flex-row">
                                            <div class="font-semibold bg-slate-400 w-fit py-0.5 px-2 text-slate-800 rounded-l">
                                                {{ $d1->format('H:i') }}
                                            </div>
                                            <div class="font-semibold border border-slate-400 w-fit py-0.5 px-2 text-slate-400 rounded-r">
                                                {{  $d1->format('d F Y') }}
                                            </div>
                                        </span>
                                    </div>
                                    
                                    <div class="col-span-2 flex flex-col items-end justify-center">
                                        <span class="text-base font-semibold text-slate-200 flex gap-0.5 w-full justify-center">
                                            <span>{{ $interval->format('%d') }}</span>
                                            <span>days</span>

                                            <span>{{ $interval->format('%H') }}</span>
                                            <span>hours</span>

                                            <span>{{ $interval->format('%I') }}</span>
                                            <span>min</span>
                                        </span>
                                    </div>

                                    <div class="col-span-4 items-end flex flex-col gap-1 justify-center">
                                        <span class="flex flex-col items-end">
                                            <span class="text-xl flex gap-4 items-center font-semibold"> 
                                                {{ $flight->destinationAirport->city }}
                                            </span>
                                            <span class="flex flex-row items-center gap-1">
                                                <span class="font-semibold text-sm text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 512 512"><path fill="currentColor" d="M186.62 464H160a16 16 0 0 1-14.57-22.6l64.46-142.25L113.1 297l-35.3 42.77C71.07 348.23 65.7 352 52 352H34.08a17.66 17.66 0 0 1-14.7-7.06c-2.38-3.21-4.72-8.65-2.44-16.41l19.82-71c.15-.53.33-1.06.53-1.58a.4.4 0 0 0 0-.15a15 15 0 0 1-.53-1.59l-19.84-71.45c-2.15-7.61.2-12.93 2.56-16.06a16.83 16.83 0 0 1 13.6-6.7H52c10.23 0 20.16 4.59 26 12l34.57 42.05l97.32-1.44l-64.44-142A16 16 0 0 1 160 48h26.91a25 25 0 0 1 19.35 9.8l125.05 152l57.77-1.52c4.23-.23 15.95-.31 18.66-.31C463 208 496 225.94 496 256c0 9.46-3.78 27-29.07 38.16c-14.93 6.6-34.85 9.94-59.21 9.94c-2.68 0-14.37-.08-18.66-.31l-57.76-1.54l-125.36 152a25 25 0 0 1-19.32 9.75"/></svg>
                                                </span>
                                                <span class="font-semibold text-sm text-slate-400"> 
                                                    {{ $flight->destinationAirport->code }}
                                                </span>
                                                <span class="font-semibold text-sm text-slate-500">
                                                    {{ $flight->destinationAirport->name }}
                                                </span>
                                            </span>
                                        </span>
                                        <span class="flex flex-row">
                                            <div class="font-semibold bg-slate-400 w-fit py-0.5 px-2 text-slate-800 rounded-l">
                                                {{ $d2->format('H:i') }}
                                            </div>
                                            <div class="font-semibold border border-slate-400 w-fit py-0.5 px-2 text-slate-400 rounded-r">
                                                {{  $d2->format('d F Y') }}
                                            </div>
                                        </span>
                                    </div>

                                    <span class="col-span-2 font-semibold flex flex-col items-center justify-center text-xl">
                                        <span class="">{{ $flight->price }} RON</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        </ul>
                    @endif
                @endif
                @if(isset($connectingFlights))
                    <h2 class="mt-4 mb-4 text-2xl font-semibold dark:text-white">Connecting Flights</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>First Leg: Origin</th>
                                <th>First Leg: Stopover</th>
                                <th>Second Leg: Destination</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($connectingFlights as $flightPair)
                                <tr>
                                    <td>{{ $flightPair['first_leg']->origin_name }}</td>
                                    <td>{{ $flightPair['first_leg']->stopover_name }}</td>
                                    <td>{{ $flightPair['second_leg']->destination_name }}</td>
                                    <td>{{ $flightPair['first_leg']->departure_time }} - {{ $flightPair['second_leg']->arrival_time }}</td>
                                    <td>${{ $flightPair['total_price'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
