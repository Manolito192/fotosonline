<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ __('Thank you for your order') }}</h1>
                <p class="mt-2 text-gray-500">
                    {{ __('Order number') }}: <span class="font-semibold text-gray-900">{{ $order->order_number }}</span>
                </p>
            </div>

            <div class="mt-8 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Order pending confirmation') }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('Bank transfer description') }}</p>

                <dl class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">{{ __('Bank holder') }}</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ config('store.bank.holder') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">{{ __('Bank account') }}</dt>
                        <dd class="text-sm font-mono font-medium text-gray-900">{{ config('store.bank.iban') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">{{ __('Bank reference') }}</dt>
                        <dd class="text-sm font-mono font-medium text-gray-900">{{ config('store.bank.reference_prefix') }} {{ $order->order_number }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">{{ __('Total to transfer') }}</dt>
                        <dd class="text-lg font-bold text-gray-900">{{ $order->formatted_total }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-6 bg-indigo-50 border border-indigo-100 rounded-2xl p-6">
                <p class="text-sm text-indigo-800">
                    {{ __('We will confirm your payment soon') }}
                </p>
            </div>

            <div class="mt-8 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
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
                <div class="p-6 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>{{ __('Subtotal') }}</span>
                            <span>{{ config('store.currency.symbol') . number_format($subtotal, 2, '.', ',') }}</span>
                        </div>
                        @if ($discount > 0)
                            <div class="flex justify-between text-sm text-green-600 font-medium">
                                <span>{{ __('Discount') }}</span>
                                <span>-{{ config('store.currency.symbol') . number_format($discount, 2, '.', ',') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between pt-2 border-t">
                            <span class="font-semibold text-gray-900">{{ __('Total') }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <a href="{{ route('photos.index') }}" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                    {{ __('Continue shopping') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
