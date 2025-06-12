<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Transaction History
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                @if($transactions->isEmpty())
                    <p class="text-gray-400">No transactions found.</p>
                @else
                    <div class="flex flex-col gap-2">
                        @foreach($transactions as $transaction)
                            <div class="group bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col sm:flex-row sm:items-center justify-between hover:bg-gray-700 transition-colors">
                                <div class="flex flex-col gap-1">
                                    <div class="text-lg font-semibold flex items-center gap-2">
                                        <span>{{ $transaction->created_at->format('Y-m-d H:i') }}</span>
                                        <span class="text-xs font-normal text-gray-400">{{ $transaction->flight ? ($transaction->flight->origin . ' → ' . $transaction->flight->destination) : 'N/A' }}</span>
                                    </div>
                                    <div class="text-base font-medium">
                                        {{ $transaction->amount }} {{ strtoupper($transaction->currency) }}
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $transaction->status === 'succeeded' ? 'bg-green-600 text-white' : ($transaction->status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-red-600 text-white') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
