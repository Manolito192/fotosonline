<x-app-layout>
    <div class="bg-gradient-to-b from-indigo-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">
                {{ __('Photos for sale') }}
            </h1>
            <p class="mt-4 text-lg text-indigo-200 max-w-2xl mx-auto">
                {{ __('Browse our collection') }}
            </p>
            <a href="{{ route('photos.index') }}" class="mt-8 inline-flex items-center px-6 py-3 text-sm font-medium text-indigo-700 bg-white hover:bg-indigo-50 rounded-lg shadow transition">
                {{ __('Browse photos') }} &rarr;
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Featured categories') }}</h2>
            <a href="{{ route('photos.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                {{ __('View all') }} &rarr;
            </a>
        </div>

        @if ($categories->isEmpty())
            <p class="mt-6 text-gray-500">{{ __('No results found') }}</p>
        @else
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($categories as $category)
                    <a href="{{ route('photos.index', ['category' => $category->slug]) }}"
                       class="group relative rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200 hover:shadow-md transition">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition">
                                {{ $category->localizedName() }}
                            </h3>
                            @if ($category->localizedDescription())
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $category->localizedDescription() }}</p>
                            @endif
                            <p class="mt-4 text-sm font-medium text-indigo-600">
                                {{ trans_choice('{0} No photos|[1] :count photo|[2,*] :count photos', $category->photos_count, ['count' => $category->photos_count]) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Recent photos') }}</h2>
            <a href="{{ route('photos.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                {{ __('View all') }} &rarr;
            </a>
        </div>

        @if ($recentPhotos->isEmpty())
            <p class="mt-6 text-gray-500">{{ __('There are no photos') }}</p>
        @else
            <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($recentPhotos as $photo)
                    <a href="{{ route('photos.show', $photo) }}" class="group">
                        <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-200">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 loading="lazy">
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900 truncate">{{ $photo->title }}</p>
                        <p class="text-sm text-gray-500">{{ $photo->formatted_price }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
