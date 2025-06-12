@if(Auth::check() && Auth::user()->role === "admin" )
<x-app-layout>
    <x-slot name="header">
        <h2 class="grow font-semibold text-xl text-white leading-tight flex">
            Edit {{ $airport->name }}
        </h2>
    </x-slot name="header">

    <div x-data="{
        isOpen: false,
        results: [],
        searchTerm: ''
    }" class="relative" @click.away="isOpen = false">
        <label for="airport-search" class="block text-sm font-medium text-white mb-2">Search Airports</label>
        <input
            id="airport-search"
            type="text"
            class="border-2 border-slate-400 rounded-lg px-4 py-2 w-full focus:shadow-lg text-black"
            placeholder="Type airport name, city, or code..."
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
        >
        <div x-show="isOpen" class="absolute w-full mt-1 bg-white border rounded-lg shadow-lg z-50 max-h-60 overflow-auto">
            <template x-for="result in results" :key="result.id">
                <div class="block px-4 py-2 hover:bg-gray-200 cursor-pointer text-black">
                    <span x-text="result.name"></span>
                    <span class="text-xs text-gray-500"> (</span><span x-text="result.code"></span><span class="text-xs text-gray-500">, </span><span x-text="result.city"></span><span class="text-xs text-gray-500">)</span>
                </div>
            </template>
            <div x-show="results.length === 0 && searchTerm.length > 1" class="px-4 py-2 text-gray-500">No results found.</div>
        </div>
    </div>

    <form class="text-white px-40 mt-8" method="POST" action="/airports">
        @csrf
        @method('PATCH')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm/6 font-medium text-white">Name</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    autocomplete="name"
                                    value="{{ $airport->name }}"
                                    class="block flex-1 border-0 bg-transparent py-1.5 px-3 placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" 
                                    required>
                            </div>
                            @error('name')
                                <p class="ml-1 mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <label for="city" class="block text-sm/6 font-medium text-white">City</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input 
                                    type="text" 
                                    name="city" 
                                    id="city"
                                    value="{{ $airport->city }}"
                                    class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" 
                                    placeholder="" 
                                    required>
                            </div>
                            @error('city')
                                <p class="ml-1 mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> 
                    <div class="sm:col-span-4">
                        <label for="country" class="block text-sm/6 font-medium text-white">Country</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input 
                                    type="text" 
                                    name="country" 
                                    id="country"
                                    value="{{ $airport->country }}"
                                    class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" 
                                    placeholder="" 
                                    required>
                            </div>
                            @error('country')
                                <p class="ml-1 mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>  
                    <div class="sm:col-span-4">
                        <label for="code" class="block text-sm/6 font-medium text-white">Code</label>
                        <div class="mt-2">
                            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input 
                                    type="text" 
                                    name="code" 
                                    id="code" 
                                    value="{{ $airport->code }}"
                                    class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" 
                                    placeholder="" 
                                    required>
                            </div>
                            @error('code')
                                <p class="ml-1 mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a 
                href={{ url()->previous() }}
                class="text-sm/6 font-semibold text-white">
                Cancel
            </a>
            <button type="submit" class="rounded-md bg-slate-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
        </div>
    </form>
</x-app-layout>

@else
  @php
    return abort(403, "Unauthorized action.");
  @endphp
@endif