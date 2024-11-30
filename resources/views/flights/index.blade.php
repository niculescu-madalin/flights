<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <form method="POST" action="{{ route('flights.search') }}" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @csrf
                <div class="flex flex-row items-center gap-2">
                    <div class="col-md-4 grow-1">
                        <x-text-input id="origin" 
                                    type="text" 
                                    name="origin" 
                                    class="form-control" 
                                    placeholder="Origin" 
                                    required />
                    </div>
                    <div class="col-md-4 grow-1">
                        <x-text-input id="destination" 
                                    type="text" 
                                    name="destination" 
                                    class="form-control" 
                                    placeholder="Destination (optional)" />
                    </div>
                    <div class="col-md-4">
                    
                        <x-primary-button type="submit" class="btn btn-primary w-100">
                            Search
                        </x-primary-button>
                    </div>
                </div>
            </form> 
        </h2>
    </x-slot>

    <!-- Search Form -->


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-900 dark:text-gray-100">
                <!-- Search Results -->
                @if(isset($flights))
                    <h2 class="mb-4 text-2xl font-semibold dark:text-white">Available Flights</h2>
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
                                <div class="px-4 py-2 grid grid-cols-12 gap-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="col-span-4 flex flex-col gap-1 justify-center">
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
                                    </div>
                                    
                                    <div class="col-span-3 flex flex-col items-end justify-center">
                                        <div class="flex flex-row">
                                            <div class="px-8 rounded-l-lg border border-slate-700 py-0.5 text-lg font-semibold">
                                                <div>
                                                    {{ $d1->format('H:i:s')  }}
                                                </div>
                                                <div class="text-xs text-slate-300">
                                                    {{ $d1->format('Y-m-d')  }}
                                                </div>
                                            </div>
                                            <div class="px-8 rounded-r-lg border border-slate-700 py-0.5 text-lg font-semibold">
                                                <div class="">
                                                    {{ $d2->format('H:i:s') }}
                                                </div>
                                                <div class="text-xs text-slate-300">
                                                    {{ $d2->format('Y-m-d') }}
                                                </div>
                                            </div>
                                        </div>
                                        <span class="flex gap-0.5 w-full justify-center">
                                            <span class="text-sm font-semibold text-slate-400">{{ $interval->format('%d') }}</span>
                                            <span class="text-sm font-semibold text-slate-400">days</span>

                                            <span class="text-sm font-semibold text-slate-400">{{ $interval->format('%H') }}</span>
                                            <span class="text-sm font-semibold text-slate-400">hours</span>

                                            <span class="text-sm font-semibold text-slate-400">{{ $interval->format('%I') }}</span>
                                            <span class="text-sm font-semibold text-slate-400">min</span>
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
                                    </div>

                                    <span class="font-semibold flex flex-col items-end justify-center text-xl">
                                        <span class="">{{ $flight->price }} RON</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        </ul>
                    @endif
                @endif 
            </div>
        </div>
    </div>
</x-app-layout>
