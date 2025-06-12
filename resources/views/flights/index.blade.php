<x-app-layout>
    <!-- Search Form -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <form 
            method="POST" 
            action="{{ route('flights.search') }}" 
            class="">
            @csrf
            <div class="flex flex-row items-center gap-2">
                <!-- Origin Airport Autocomplete with original style -->
                <div x-data="{ isOpen: false, results: [], searchTerm: '' }" class="relative w-full" @click.away="isOpen = false">
                    <x-text-input
                        id="origin"
                        name="origin"
                        type="text"
                        class="w-full form-control p-4"
                        placeholder="Origin"
                        autocomplete="off"
                        x-model="searchTerm"
                        @input.debounce.300ms="
                            if (searchTerm.length > 1) {
                                fetch(`/airport-suggest?q=${encodeURIComponent(searchTerm)}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        results = data;
                                        isOpen = true;
                                    });
                            } else {
                                results = [];
                                isOpen = false;
                            }
                        "
                        @focus="searchTerm.length > 1 && (isOpen = true)"
                    />
                    <div x-show="isOpen" class="absolute w-full mt-1 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-50 max-h-60 overflow-auto text-white">
                        <template x-for="result in results" :key="result.id">
                            <div @mousedown.prevent="searchTerm = result.code; isOpen = false; $el.blur();" class="px-4 py-2 cursor-pointer hover:bg-blue-900 hover:text-blue-200">
                                <span x-text="result.name"></span>
                                <span class="text-xs text-gray-400" x-text="`(${result.code}) - ${result.city}, ${result.country}`"></span>
                            </div>
                        </template>
                        <div x-show="results.length === 0 && searchTerm.length > 1" class="px-4 py-2 text-gray-400">No results</div>
                    </div>
                </div>
                <!-- Destination Airport Autocomplete with original style -->
                <div x-data="{ isOpen: false, results: [], searchTerm: '' }" class="relative w-full" @click.away="isOpen = false">
                    <x-text-input
                        id="destination"
                        name="destination"
                        type="text"
                        class="w-full form-control p-4"
                        placeholder="Destination (optional)"
                        autocomplete="off"
                        x-model="searchTerm"
                        @input.debounce.300ms="
                            if (searchTerm.length > 1) {
                                fetch(`/airport-suggest?q=${encodeURIComponent(searchTerm)}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        results = data;
                                        isOpen = true;
                                    });
                            } else {
                                results = [];
                                isOpen = false;
                            }
                        "
                        @focus="searchTerm.length > 1 && (isOpen = true)"
                    />
                    <div x-show="isOpen" class="absolute w-full mt-1 bg-gray-900 border border-gray-700 rounded-lg shadow-lg z-50 max-h-60 overflow-auto text-white">
                        <template x-for="result in results" :key="result.id">
                            <div @mousedown.prevent="searchTerm = result.code; isOpen = false; $el.blur();" class="px-4 py-2 cursor-pointer hover:bg-blue-900 hover:text-blue-200">
                                <span x-text="result.name"></span>
                                <span class="text-xs text-gray-400" x-text="`(${result.code}) - ${result.city}, ${result.country}`"></span>
                            </div>
                        </template>
                        <div x-show="results.length === 0 && searchTerm.length > 1" class="px-4 py-2 text-gray-400">No results</div>
                    </div>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">   
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
                            <details class="group py-1 text-lg">
                            <summary class="cursor-pointer list-none px-4 py-4 grid grid-cols-12 gap-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="col-span-4 flex flex-col gap-2 justify-center">
                                    <span>
                                        <span class="text-xl flex gap-4 items-center font-semibold"> 
                                            {{ $flight->originAirport->city }}
                                        </span>
                                        <a class="hover:text-slate-200 font-semibold text-sm text-slate-400 flex flex-row items-center gap-1" href="/airports/{{ $flight->originAirport->id }}">
                                            <span class=" ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 512 512"><path fill="currentColor" d="M186.62 464H160a16 16 0 0 1-14.57-22.6l64.46-142.25L113.1 297l-35.3 42.77C71.07 348.23 65.7 352 52 352H34.08a17.66 17.66 0 0 1-14.7-7.06c-2.38-3.21-4.72-8.65-2.44-16.41l19.82-71c.15-.53.33-1.06.53-1.58a.4.4 0 0 0 0-.15a15 15 0 0 1-.53-1.59l-19.84-71.45c-2.15-7.61.2-12.93 2.56-16.06a16.83 16.83 0 0 1 13.6-6.7H52c10.23 0 20.16 4.59 26 12l34.57 42.05l97.32-1.44l-64.44-142A16 16 0 0 1 160 48h26.91a25 25 0 0 1 19.35 9.8l125.05 152l57.77-1.52c4.23-.23 15.95-.31 18.66-.31C463 208 496 225.94 496 256c0 9.46-3.78 27-29.07 38.16c-14.93 6.6-34.85 9.94-59.21 9.94c-2.68 0-14.37-.08-18.66-.31l-57.76-1.54l-125.36 152a25 25 0 0 1-19.32 9.75"/></svg>
                                            </span>
                                            <span class=" ">
                                                {{ $flight->originAirport->code }}
                                            </span>
                                            <span class="text-slate-500 hover:text-slate-200">
                                                {{ $flight->originAirport->name }}
                                            </span>
                                        </a>
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
                                
                                <div class="col-span-3 flex flex-col items-end justify-center">
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
                                        <a class="font-semibold text-sm text-slate-400 hover:text-slate-200 flex flex-row items-center gap-1" href="/airports/{{ $flight->destinationAirport->id }}">
                                            <span class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 512 512"><path fill="currentColor" d="M186.62 464H160a16 16 0 0 1-14.57-22.6l64.46-142.25L113.1 297l-35.3 42.77C71.07 348.23 65.7 352 52 352H34.08a17.66 17.66 0 0 1-14.7-7.06c-2.38-3.21-4.72-8.65-2.44-16.41l19.82-71c.15-.53.33-1.06.53-1.58a.4.4 0 0 0 0-.15a15 15 0 0 1-.53-1.59l-19.84-71.45c-2.15-7.61.2-12.93 2.56-16.06a16.83 16.83 0 0 1 13.6-6.7H52c10.23 0 20.16 4.59 26 12l34.57 42.05l97.32-1.44l-64.44-142A16 16 0 0 1 160 48h26.91a25 25 0 0 1 19.35 9.8l125.05 152l57.77-1.52c4.23-.23 15.95-.31 18.66-.31C463 208 496 225.94 496 256c0 9.46-3.78 27-29.07 38.16c-14.93 6.6-34.85 9.94-59.21 9.94c-2.68 0-14.37-.08-18.66-.31l-57.76-1.54l-125.36 152a25 25 0 0 1-19.32 9.75"/></svg>
                                            </span>
                                            <span class=""> 
                                                {{ $flight->destinationAirport->code }}
                                            </span>
                                            <span class="hover:text-slate-200 text-slate-500">
                                                {{ $flight->destinationAirport->name }}
                                            </span>
                                        </a>
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
                                <span class="col-span-1 font-semibold flex flex-col items-center justify-center text-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>   
                                </span>    
                            </summary>
                                <div class="mt-1 px-4 py-4 bg-white dark:bg-gray-600 overflow-hidden shadow-sm rounded-t-lg">
                                
                                </div>
                                <div class="flex items-center justify-between px-4 py-4 bg-white dark:bg-gray-700 overflow-hidden shadow-sm rounded-b-lg">
                                    <div>
                                        <span class="font-bold">Total: </span> {{ $flight->price }} RON
                                    </div>
                                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Buy</button>
                                </div>
                            </details>
                        @endforeach       
                    </div>
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

    <script type="module" src="/build/assets/airport-autocomplete.js"></script>
    <!-- from cdn -->
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/collapse.js"></script>
</x-app-layout>
