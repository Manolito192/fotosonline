<x-app-layout>
<x-slot name="title">{{ $collection->title }} - {{ config('app.name') }}</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('collections.index') }}" class="text-blue-600 hover:underline text-sm">&larr; {{ __('Back to collections') }}</a>

    <div class="mt-4 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $collection->title }}</h1>
        @if ($collection->description)
            <p class="mt-2 text-gray-600">{{ $collection->description }}</p>
        @endif
        <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
            <span>{{ $collection->photos->count() }} {{ __('Photos') }}</span>
            <span class="bg-green-100 text-green-800 font-semibold px-2.5 py-0.5 rounded-full">-{{ $collection->discount_percent }}% {{ __('discount') }}</span>
            <span>{{ __('Collection total') }}: <s class="text-gray-400">{{ config('store.currency.symbol') . number_format($collection->original_total, 2, '.', ',') }}</s>
                <span class="text-green-600 font-bold">{{ config('store.currency.symbol') . number_format($collection->discounted_total, 2, '.', ',') }}</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($collection->photos as $photo)
            <a href="{{ route('photos.show', $photo) }}" class="group block bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900">{{ $photo->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $photo->category->name ?? '' }}</p>
                    <p class="text-lg font-bold text-gray-900 mt-2">{{ config('store.currency.symbol') . number_format($photo->price, 2, '.', ',') }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8 p-6 bg-gray-50 rounded-lg text-center">
        <form action="{{ route('collections.addToCart', $collection) }}" method="POST" class="inline">
            @csrf
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition">
                {{ __('Add collection to cart') }} — {{ config('store.currency.symbol') . number_format($collection->discounted_total, 2, '.', ',') }}
            </button>
        </form>
    </div>
</div>
</x-app-layout>
