<x-admin-layout>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $photo ? __('Edit') . ': ' . $photo->title : __('New photo') }}
        </h1>
        <a href="{{ route('admin.photos.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">&larr; {{ __('Cancel') }}</a>
    </div>

    <form method="POST" action="{{ $photo ? route('admin.photos.update', $photo) : route('admin.photos.store') }}"
          class="mt-6 max-w-3xl space-y-6" enctype="multipart/form-data">
        @csrf
        @if ($photo)
            @method('PUT')
        @endif

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 space-y-4">
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $photo?->title)" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="slug" :value="__('Slug')" />
                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono" :value="old('slug', $photo?->slug)" required />
                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="category_id" :value="__('Category')" />
                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">—</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $photo?->category_id) == $category->id)>
                            {{ $category->name_es }} / {{ $category->name_en }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $photo?->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="price" :value="__('Price ($)')" />
                <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $photo?->price)" required />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="image" :value="$photo ? __('Replace image') : __('Upload image')" />
                @if ($photo)
                    <div class="mt-2 mb-3 w-32 h-32 rounded-xl overflow-hidden bg-gray-200">
                        <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <input id="image" name="image" type="file" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-400">JPG, PNG, WEBP o GIF &middot; máx. 20 MB</p>
            </div>

            <div>
                <x-input-label for="meta_title" :value="__('SEO title')" />
                <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $photo?->meta_title)" placeholder="{{ __('Optional: used for page title and search engines') }}" />
                <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="meta_description" :value="__('SEO description')" />
                <textarea id="meta_description" name="meta_description" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="{{ __('Optional: meta description for search engines (max 160 chars)') }}">{{ old('meta_description', $photo?->meta_description) }}</textarea>
                <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
            </div>

            @if ($tags->isNotEmpty())
            <div>
                <x-input-label :value="__('Tags')" />
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($tags as $tag)
                        <label class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm border cursor-pointer transition {{ in_array($tag->id, old('tag_ids', $selectedTagIds ?? [])) ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300' }}">
                            <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" class="sr-only" {{ in_array($tag->id, old('tag_ids', $selectedTagIds ?? [])) ? 'checked' : '' }}>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-3">
                <input id="is_published" name="is_published" type="checkbox" value="1"
                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                       @checked(old('is_published', $photo?->is_published ?? true))>
                <label for="is_published" class="text-sm font-medium text-gray-700">{{ __('Published') }}</label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                {{ __('Save') }}
            </button>
            <a href="{{ route('admin.photos.index') }}" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</x-admin-layout>
