<x-app-layout>
<x-slot name="title">{{ __('Recently viewed') }} - {{ config('app.name') }}</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Recently viewed') }}</h1>

    @if ($recentPhotos->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">{{ __('No recently viewed photos') }}</p>
            <a href="{{ route('photos.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">{{ __('Browse photos') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($recentPhotos as $recent)
                @php $photo = $recent->photo; @endphp
                @if ($photo)
                    <a href="{{ route('photos.show', $photo) }}" class="group block bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">{{ $photo->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $photo->category->name ?? '' }}</p>
                            <p class="text-lg font-bold text-gray-900 mt-2">{{ config('store.currency.symbol') . number_format($photo->price, 2, '.', ',') }}</p>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    @endif
</div>
</x-app-layout>
