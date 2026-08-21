<x-app-layout>
<x-slot name="title">{{ __('Collections') }} - {{ config('app.name') }}</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Collections') }}</h1>

    @if ($collections->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">{{ __('There are no collections') }}</p>
            <a href="{{ route('photos.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">{{ __('Browse photos') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($collections as $collection)
                <a href="{{ route('collections.show', $collection) }}" class="group block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="grid grid-cols-2 h-48">
                        @foreach ($collection->photos->take(4) as $index => $photo)
                            <div class="overflow-hidden {{ $index === 0 ? 'col-span-2' : '' }}">
                                <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @endforeach
                    </div>
                    <div class="p-5">
                        <h2 class="text-xl font-bold text-gray-900 group-hover:text-blue-600">{{ $collection->title }}</h2>
                        @if ($collection->description)
                            <p class="mt-2 text-gray-600 text-sm line-clamp-2">{{ $collection->description }}</p>
                        @endif
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ $collection->photos_count }} {{ __('Photos') }}</span>
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">-{{ $collection->discount_percent }}%</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $collections->links() }}</div>
    @endif
</div>
</x-app-layout>
