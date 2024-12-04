@if(Auth::check() && Auth::user()->role === "admin" )
<x-app-layout>
    <x-slot name="header">
        <h2 class="grow font-semibold text-xl text-white leading-tight flex">
            Add a new airport
        </h2>
    </x-slot name="header">

<form class="text-white px-40" method="POST" action="/airports">
    @csrf
    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-12">
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <label for="name" class="block text-sm/6 font-medium text-white">Name</label>
            <div class="mt-2">
              <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <input type="text" name="name" id="name" autocomplete="name" class="block flex-1 border-0 bg-transparent py-1.5 px-3 placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" required>
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
                <input type="text" name="city" id="city" class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" placeholder="" required>
              </div>
              @error('city')
                  <p class="ml-1 mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
              @enderror
            </div>
          </div> 
          
          <div class="sm:col-span-4">
            <label for="country" class="block text-sm/6 font-medium text-white">City</label>
            <div class="mt-2">
              <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <input type="text" name="country" id="country" class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" placeholder="" required>
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
                <input type="text" name="code" id="code" class="block flex-1 border-0 bg-transparent py-1.5 px-3 text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm/6" placeholder="" required>
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