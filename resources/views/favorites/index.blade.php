<x-app-layout>
<x-slot name="title">{{ __('Favorites') }} - {{ config('app.name') }}</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Favorites') }}</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif

    @if ($favorites->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">{{ __('You have no favorites yet') }}</p>
            <a href="{{ route('photos.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">{{ __('Browse photos') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($favorites as $favorite)
                @php $photo = $favorite->photo; @endphp
                <div class="bg-white rounded-lg shadow overflow-hidden group relative">
                    <a href="{{ route('photos.show', $photo) }}">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                    </a>
                    <div class="p-4">
                        <a href="{{ route('photos.show', $photo) }}" class="font-semibold text-gray-900 hover:text-blue-600">{{ $photo->title }}</a>
                        <p class="text-sm text-gray-500 mt-1">{{ $photo->category->name ?? '' }}</p>
                        <p class="text-lg font-bold text-gray-900 mt-2">{{ config('store.currency.symbol') . number_format($photo->price, 2, '.', ',') }}</p>
                    </div>
                    <form action="{{ route('favorites.toggle', $photo) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        <button class="bg-white/80 hover:bg-white rounded-full p-2 shadow text-red-500 hover:text-red-600 transition" title="{{ __('Remove from favorites') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $favorites->links() }}</div>
    @endif
</div>
</x-app-layout>
