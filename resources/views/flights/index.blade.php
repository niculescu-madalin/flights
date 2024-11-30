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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search Results -->
                    @if(isset($flights))
                        <h2 class="mb-4 text-2xl font-semibold dark:text-white">Available Flights</h2>
                        @if($flights->isEmpty())
                            <p>No flights found for the selected route.</p>
                        @else
                            <table class="table-auto border-collapse border border-slate-500">
                                <thead>
                                    <tr>
                                        <th class="p-2 border border-slate-600">Origin</th>
                                        <th class="p-2 border border-slate-600">Destination</th>
                                        <th class="p-2 border border-slate-600">Departure Time</th>
                                        <th class="p-2 border border-slate-600">Arrival Time</th>
                                        <th class="p-2 border border-slate-600">Duration</th>
                                        <th class="p-2 border border-slate-600">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flights as $flight)
                                        <tr>
                                            <td class="p-2 border border-slate-700">{{ $flight->origin }}</td>
                                            <td class="p-2 border border-slate-700">{{ $flight->destination }}</td>
                                            <td class="p-2 border border-slate-700">{{ $flight->departure_time }}</td>
                                            <td class="p-2 border border-slate-700">{{ $flight->arrival_time }}</td>
                                            @php
                                                $d1 = new DateTime($flight->departure_time);
                                                $d2 = new DateTime($flight->arrival_time);
                                                $interval = $d1->diff($d2);
                                            @endphp
                                            <td class="p-2 border border-slate-700">{{ $interval->format('%dd, %Hh, %Imin') }}</td>
                                            <td class="p-2 border border-slate-700">${{ $flight->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
