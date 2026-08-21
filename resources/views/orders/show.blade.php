<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Order') }} #{{ $order->order_number }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                    <p class="text-sm text-gray-500">{{ __('Date') }}</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                    <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                    @php
                        $badge = match ($order->status) {
                            'confirmed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-yellow-100 text-yellow-700',
                        };
                    @endphp
                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('Order summary') }}</h2>
                </div>
                <ul class="divide-y divide-gray-200">
                    @foreach ($order->items as $item)
                        <li class="flex items-center gap-4 p-4">
                            @if ($item->image_url)
                                <div class="shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-gray-200">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->photo_title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->photo_title }}</p>
                            </div>
                            <p class="text-sm text-gray-500 shrink-0">{{ $item->formatted_price }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="p-6 border-t border-gray-200 flex items-center justify-between">
                    <span class="font-semibold text-gray-900">{{ __('Total') }}</span>
                    <span class="text-xl font-bold text-gray-900">{{ $order->formatted_total }}</span>
                </div>
            </div>

            @if ($order->isConfirmed())
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('downloads.index', $order) }}?token={{ $order->download_token }}"
                       class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                        </svg>
                        {{ __('Download all') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
