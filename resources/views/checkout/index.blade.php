<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Checkout') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Customer information') }}</h3>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="customer_name" :value="__('Full name')" />
                                <x-text-input id="customer_name" name="customer_name" type="text"
                                              class="mt-1 block w-full" :value="old('customer_name', auth()->user()?->name)" required autofocus />
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="customer_email" :value="__('Email')" />
                                <x-text-input id="customer_email" name="customer_email" type="email"
                                              class="mt-1 block w-full" :value="old('customer_email', auth()->user()?->email)" required />
                                <x-input-error :messages="$errors->get('customer_email')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes') . ' (' . __('Optional') . ')'" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Payment method') }}</h3>
                        <div class="mt-4 flex items-center gap-4 rounded-xl border border-indigo-200 bg-indigo-50 p-4">
                            <input type="radio" checked disabled class="h-4 w-4 text-indigo-600">
                            <div>
                                <p class="font-medium text-gray-900">{{ __('Bank transfer') }}</p>
                                <p class="text-sm text-gray-600">{{ __('Bank transfer description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Order summary') }}</h3>
                        <ul class="mt-4 space-y-3">
                            @foreach ($photos as $photo)
                                <li class="flex items-center justify-between gap-4 text-sm">
                                    <span class="text-gray-700 truncate">{{ $photo->title }}</span>
                                    <span class="text-gray-900 font-medium shrink-0">{{ $photo->formatted_price }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                            <span class="font-semibold text-gray-900">{{ __('Total') }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ config('store.currency.symbol') . number_format($total, 2, '.', ',') }}</span>
                        </div>

                        <button type="submit" class="mt-6 w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                            {{ __('Place order') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
