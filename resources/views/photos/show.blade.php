<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-gray-500 mb-6">
                <a href="{{ route('photos.index') }}" class="hover:text-indigo-600">{{ __('Gallery') }}</a>
                @if ($photo->category)
                    <span class="mx-2">/</span>
                    <a href="{{ route('photos.index', ['category' => $photo->category->slug]) }}" class="hover:text-indigo-600">
                        {{ $photo->category->name() }}
                    </a>
                @endif
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ $photo->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="rounded-2xl overflow-hidden bg-gray-200">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-auto object-contain">
                </div>

                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $photo->title }}</h1>

                    @if ($photo->category)
                        <p class="mt-2 text-sm text-indigo-600 font-medium">{{ $photo->category->name() }}</p>
                    @endif

                    <p class="mt-4 text-2xl font-semibold text-gray-900">{{ $photo->formatted_price }}</p>

                    @if ($photo->description)
                        <p class="mt-4 text-gray-600 leading-relaxed whitespace-pre-line">{{ $photo->description }}</p>
                    @endif

                    <div class="mt-8">
                        <form method="POST" action="{{ route('cart.add', $photo) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition w-full sm:w-auto">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.3 4.6A1 1 0 006 19h12M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                                {{ __('Add to cart') }}
                            </button>
                        </form>
                        <p class="mt-3 text-xs text-gray-400">
                            {{ __('Digital download after payment confirmation') }}
                        </p>
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Related photos') }}</h2>
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($related as $rel)
                            <a href="{{ route('photos.show', $rel) }}" class="group">
                                <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-200">
                                    <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                                </div>
                                <p class="mt-2 text-sm text-gray-900 truncate">{{ $rel->title }}</p>
                                <p class="text-sm text-gray-500">{{ $rel->formatted_price }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Tags --}}
            @if ($photo->tags->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($photo->tags as $tag)
                        <a href="{{ route('photos.index', ['tag' => $tag->slug]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full transition">{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Favorite Button --}}
            @auth
                <div class="mt-4">
                    <form action="{{ route('favorites.toggle', $photo) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 text-sm {{ auth()->user()->favorites()->where('photo_id', $photo->id)->exists() ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }} transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ auth()->user()->favorites()->where('photo_id', $photo->id)->exists() ? 'fill-current' : '' }}" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            {{ auth()->user()->favorites()->where('photo_id', $photo->id)->exists() ? __('In your favorites') : __('Add to favorites') }}
                        </button>
                    </form>
                </div>
            @endauth

            {{-- EXIF Info --}}
            @if ($photo->exif_data && count($photo->exif_data) > 0)
                <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('EXIF data') }}</h3>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                        @if (isset($photo->exif_data['camera_make']) || isset($photo->exif_data['camera_model']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Camera') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ trim(($photo->exif_data['camera_make'] ?? '') . ' ' . ($photo->exif_data['camera_model'] ?? '')) }}</dd>
                        @endif
                        @if (isset($photo->exif_data['focal_length']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Focal length') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $photo->exif_data['focal_length'] }}</dd>
                        @endif
                        @if (isset($photo->exif_data['aperture']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Aperture') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">f/{{ $photo->exif_data['aperture'] }}</dd>
                        @endif
                        @if (isset($photo->exif_data['shutter_speed']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Shutter speed') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $photo->exif_data['shutter_speed'] }}s</dd>
                        @endif
                        @if (isset($photo->exif_data['iso']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('ISO') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $photo->exif_data['iso'] }}</dd>
                        @endif
                        @if (isset($photo->exif_data['date_taken']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Date taken') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $photo->exif_data['date_taken'] }}</dd>
                        @endif
                        @if (isset($photo->exif_data['width']) && isset($photo->exif_data['height']))
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Dimensions') }}</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $photo->exif_data['width'] }} × {{ $photo->exif_data['height'] }}</dd>
                        @endif
                    </dl>
                </div>
            @endif

            {{-- Social Sharing --}}
            <div class="mt-6 flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ __('Share') }}:</span>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($photo->title . ' - ' . config('app.name')) }}&url={{ urlencode(route('photos.show', $photo)) }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-blue-400 transition" title="Twitter/X">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('photos.show', $photo)) }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-blue-600 transition" title="Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(route('photos.show', $photo)) }}&media={{ urlencode($photo->image_url) }}&description={{ urlencode($photo->title) }}" target="_blank" rel="noopener" class="text-gray-400 hover:text-red-600 transition" title="Pinterest">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>
                </a>
            </div>

            {{-- Reviews Section --}}
            <div class="mt-12 border-t pt-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Reviews') }}</h2>
                    @if ($photo->average_rating)
                        <div class="flex items-center gap-2">
                            <div class="text-yellow-500 text-lg">
                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= round($photo->average_rating) ? '★' : '☆' }}
                                @endfor
                            </div>
                            <span class="text-gray-600 text-sm">({{ $photo->average_rating }} / {{ $photo->reviews_count }} {{ __('reviews') }})</span>
                        </div>
                    @endif
                </div>

                {{-- Approved Reviews --}}
                <div class="space-y-6 mb-8">
                    @forelse ($photo->reviews()->approved()->with('user')->latest()->get() as $review)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-semibold text-gray-900">{{ $review->user->name ?? __('Guest') }}</span>
                                    <span class="text-yellow-500 ml-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if ($review->comment)
                                <p class="mt-2 text-gray-700 text-sm">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500">{{ __('No reviews yet') }}</p>
                    @endforelse
                </div>

                {{-- Review Form --}}
                @auth
                    <div class="bg-white border rounded-lg p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">{{ __('Write a review') }}</h3>
                        @if (session('success'))
                            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-sm">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('reviews.store', $photo) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Rating') }}</label>
                                <div class="flex gap-1" id="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" onclick="document.getElementById('rating-input').value={{ $i }}; updateStars({{ $i }})" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition" data-value="{{ $i }}">★</button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-input" value="5" required>
                                <script>
                                    function updateStars(value) {
                                        document.querySelectorAll('.star-btn').forEach((btn, i) => {
                                            btn.classList.toggle('text-yellow-400', i < value);
                                            btn.classList.toggle('text-gray-300', i >= value);
                                        });
                                    }
                                    updateStars(5);
                                </script>
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Comment') }} ({{ __('optional') }})</label>
                                <textarea id="comment" name="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" maxlength="1000">{{ old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">{{ __('Submit review') }}</button>
                        </form>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">{{ __('Login') }}</a> {{ __('to write a review') }}.
                    </p>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
