<x-app-layout>
<x-slot name="title">{{ __('Cart') }} - {{ config('app.name') }}</x-slot>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Cart') }}</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif

    @if ($photos->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">{{ __('Your cart is empty') }}</p>
            <a href="{{ route('photos.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">{{ __('Browse photos') }}</a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach ($photos as $photo)
                    <li class="flex items-center gap-4 p-4">
                        <a href="{{ route('photos.show', $photo) }}" class="shrink-0">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-20 h-20 object-cover rounded-lg">
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('photos.show', $photo) }}" class="font-semibold text-gray-900 hover:text-blue-600 truncate block">{{ $photo->title }}</a>
                            <p class="text-sm text-gray-500">{{ $photo->category->name ?? '' }}</p>
                        </div>
                        <p class="font-bold text-gray-900 shrink-0">{{ config('store.currency.symbol') . number_format($photo->price, 2, '.', ',') }}</p>
                        <form action="{{ route('cart.remove', $photo) }}" method="POST" class="shrink-0">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-sm" title="{{ __('Remove') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Coupon --}}
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-gray-900 mb-3">{{ __('Have a coupon?') }}</h2>
            @if ($coupon)
                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3">
                    <span class="text-green-800 font-medium text-sm">{{ __('Coupon') }}: {{ $coupon->code }} (-{{ $coupon->type === 'percentage' ? $coupon->value . '%' : config('store.currency.symbol') . number_format($coupon->value, 2, '.', ',') }})</span>
                    <form action="{{ route('cart.removeCoupon') }}" method="POST">
                        @csrf
                        <button class="text-red-500 hover:text-red-700 text-sm">{{ __('Remove') }}</button>
                    </form>
                </div>
            @else
                <form action="{{ route('cart.applyCoupon') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="coupon_code" placeholder="{{ __('Enter coupon code') }}" class="flex-1 border-gray-300 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium">{{ __('Apply') }}</button>
                </form>
                @error('coupon_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Totals --}}
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ __('Subtotal') }}</span>
                    <span>{{ config('store.currency.symbol') . number_format($subtotal, 2, '.', ',') }}</span>
                </div>
                @if ($discount > 0)
                    <div class="flex justify-between text-sm text-green-600 font-medium">
                        <span>{{ __('Discount') }} ({{ $coupon->code }})</span>
                        <span>-{{ config('store.currency.symbol') . number_format($discount, 2, '.', ',') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                    <span>{{ __('Total') }}</span>
                    <span>{{ config('store.currency.symbol') . number_format($total, 2, '.', ',') }}</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('checkout') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">{{ __('Checkout') }}</a>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('photos.index') }}" class="text-blue-600 hover:underline text-sm">{{ __('Continue shopping') }}</a>
            </div>
        </div>
    @endif
</div>
</x-app-layout>
