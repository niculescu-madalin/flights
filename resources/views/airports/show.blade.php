<x-app-layout>
    <x-slot name="header">
        <div class="text-white">
        <h2 class="font-semibold text-xl leading-tight">
            {{ $airport->name }}
        </h2>
        <p>
            {{ $airport->city }} 🞄 {{ $airport->code}}
        </p>
        </div>
    </x-slot>
</x-app-layout>