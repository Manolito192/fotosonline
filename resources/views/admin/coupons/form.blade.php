<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($coupon) ? __('Edit coupon') : __('New coupon') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
            @csrf
            @if (isset($coupon)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="code" :value="__('Coupon code')" />
                    <x-text-input id="code" name="code" :value="old('code', $coupon->code ?? '')" class="mt-1 block w-full" required style="text-transform: uppercase" />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="type" :value="__('Discount type')" />
                    <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }} (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>{{ __('Fixed amount') }} ({{ config('store.currency.symbol') }})</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="value" :value="__('Value')" />
                    <x-text-input id="value" name="value" type="number" step="0.01" :value="old('value', $coupon->value ?? '')" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('value')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="min_order" :value="__('Minimum order')" />
                    <x-text-input id="min_order" name="min_order" type="number" step="0.01" :value="old('min_order', $coupon->min_order ?? '0')" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="max_uses" :value="__('Max uses (blank = unlimited)')" />
                    <x-text-input id="max_uses" name="max_uses" type="number" :value="old('max_uses', $coupon->max_uses ?? '')" class="mt-1 block w-full" />
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ __('Active') }}</span>
                    </label>
                </div>
                <div>
                    <x-input-label for="starts_at" :value="__('Starts at')" />
                    <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', isset($coupon) ? $coupon->starts_at?->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
                <div>
                    <x-input-label for="expires_at" :value="__('Expires at')" />
                    <input id="expires_at" name="expires_at" type="datetime-local" value="{{ old('expires_at', isset($coupon) ? $coupon->expires_at?->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                <a href="{{ route('admin.coupons.index') }}" class="text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-admin-layout>
