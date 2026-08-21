<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Gallery') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <form method="GET" action="{{ route('photos.index') }}" class="flex-1 max-w-md flex gap-2">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search photos') }}"
                           class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        {{ __('Search') }}
                    </button>
                </form>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('photos.index', array_merge(request()->query(), ['category' => null])) }}"
                   class="px-3 py-1.5 text-sm font-medium rounded-full transition {{ ! request('category') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-gray-100' }}">
                    {{ __('All') }}
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('photos.index', array_merge(request()->query(), ['category' => $category->slug])) }}"
                       class="px-3 py-1.5 text-sm font-medium rounded-full transition {{ request('category') === $category->slug ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-gray-100' }}">
                        {{ $category->name() }}
                    </a>
                @endforeach
            </div>

            {{-- Tag Filters --}}
            @php
                $allTags = \App\Models\Tag::has('photos')->withCount('photos')->orderBy('name')->get();
                $currentTag = request('tag');
            @endphp
            @if ($allTags->isNotEmpty())
                <div class="mb-6 flex flex-wrap gap-2">
                    <a href="{{ route('photos.index') }}" class="px-3 py-1 rounded-full text-sm font-medium transition {{ !$currentTag ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('All') }}</a>
                    @foreach ($allTags as $tag)
                        <a href="{{ route('photos.index', array_merge(request()->query(), ['tag' => $tag->slug])) }}" class="px-3 py-1 rounded-full text-sm font-medium transition {{ $currentTag === $tag->slug ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $tag->name }} <span class="text-xs opacity-70">({{ $tag->photos_count }})</span></a>
                    @endforeach
                </div>
            @endif

            {{-- Sort --}}
            <div class="mb-6 flex items-center gap-2">
                <label class="text-sm text-gray-600">{{ __('Sort by') }}:</label>
                <select onchange="window.location.href=this.value" class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="{{ route('photos.index', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                    <option value="{{ route('photos.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ __('Price') }}: {{ __('Low to high') }}</option>
                    <option value="{{ route('photos.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('Price') }}: {{ __('High to low') }}</option>
                    <option value="{{ route('photos.index', array_merge(request()->query(), ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('Most popular') }}</option>
                </select>
            </div>

            @if ($photos->isEmpty())
                <p class="mt-12 text-center text-gray-500">{{ __('No results found') }}</p>
            @else
                <div x-data="infiniteScroll()" class="mt-6 photo-grid grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($photos as $photo)
                        <a href="{{ route('photos.show', $photo) }}" class="photo-card group relative">
                            <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-200">
                                <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                     loading="lazy">
                                @auth
                                    <form action="{{ route('favorites.toggle', $photo) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                        @csrf
                                        <button class="bg-white/80 hover:bg-white rounded-full p-1.5 shadow {{ auth()->user()->favorites()->where('photo_id', $photo->id)->exists() ? 'text-red-500' : 'text-gray-400' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-900 truncate">{{ $photo->title }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">{{ $photo->formatted_price }}</p>
                                @if ($photo->category)
                                    <p class="text-xs text-indigo-600">{{ $photo->category->name() }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 pagination-links">
                    {{ $photos->links() }}
                </div>
            @endif

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('infiniteScroll', () => ({
                        page: {{ $photos->currentPage() }},
                        loading: false,
                        hasMore: {{ $photos->hasMorePages() ? 'true' : 'false' }},
                        loadMore() {
                            if (this.loading || !this.hasMore) return;
                            this.loading = true;
                            this.page++;
                            const url = new URL(window.location.href);
                            url.searchParams.set('page', this.page);
                            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                                .then(r => r.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const newCards = doc.querySelectorAll('.photo-card');
                                    const grid = document.querySelector('.photo-grid');
                                    newCards.forEach(card => grid.appendChild(card));
                                    this.loading = false;
                                    this.hasMore = doc.querySelector('.photo-card') !== null && newCards.length > 0;
                                    const pagination = document.querySelector('.pagination-links');
                                    if (pagination) {
                                        const newPagination = doc.querySelector('.pagination-links');
                                        if (newPagination) pagination.innerHTML = newPagination.innerHTML;
                                    }
                                })
                                .catch(() => { this.loading = false; });
                        }
                    }));
                });
            </script>

            @if ($photos->hasMorePages())
                <div class="mt-8 text-center" x-data="infiniteScroll()">
                    <button @click="loadMore()" x-show="hasMore" x-cloak
                            class="bg-gray-900 hover:bg-gray-800 text-white px-8 py-3 rounded-lg text-sm font-medium transition"
                            :disabled="loading">
                        <span x-show="!loading">{{ __('Load more') }}</span>
                        <span x-show="loading">{{ __('Loading...') }}</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
