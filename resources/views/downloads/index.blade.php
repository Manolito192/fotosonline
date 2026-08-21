<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Download your photos') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Order number') }}</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                        {{ __('Confirmed') }}
                    </span>
                </div>

                <ul class="mt-6 divide-y divide-gray-200">
                    @foreach ($order->items as $item)
                        <li class="flex items-center gap-4 py-4">
                            @if ($item->image_url)
                                <div class="shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-gray-200">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->photo_title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $item->photo_title }}</p>
                            </div>
                            <a href="{{ route('downloads.item', [$order, $item]) }}?token={{ $order->download_token }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                </svg>
                                {{ __('Download') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-6 flex justify-center">
                <a href="{{ route('photos.index') }}" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                    {{ __('Continue shopping') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
