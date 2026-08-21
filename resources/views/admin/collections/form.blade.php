<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($collection) ? __('Edit collection') : __('New collection') }}
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
        <form action="{{ isset($collection) ? route('admin.collections.update', $collection) : route('admin.collections.store') }}" method="POST">
            @csrf
            @if (isset($collection)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" :value="old('title', $collection->title ?? '')" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" name="slug" :value="old('slug', $collection->slug ?? '')" class="mt-1 block w-full" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $collection->description ?? '') }}</textarea>
                </div>
                <div>
                    <x-input-label for="discount_percent" :value="__('Discount (%)')" />
                    <x-text-input id="discount_percent" name="discount_percent" type="number" step="0.01" min="0" max="100" :value="old('discount_percent', $collection->discount_percent ?? '0')" class="mt-1 block w-full" />
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" class="rounded" {{ old('is_published', $collection->is_published ?? false) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ __('Published') }}</span>
                    </label>
                </div>
            </div>

            <div class="mt-6">
                <x-input-label :value="__('Select photos')" />
                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 max-h-80 overflow-y-auto border rounded-lg p-3">
                    @foreach ($photos as $photo)
                        <label class="flex items-start gap-2 p-2 rounded hover:bg-gray-50 cursor-pointer border {{ in_array($photo->id, old('photo_ids', $selectedPhotoIds ?? [])) ? 'border-blue-500 bg-blue-50' : 'border-transparent' }}">
                            <input type="checkbox" name="photo_ids[]" value="{{ $photo->id }}" class="mt-1 rounded" {{ in_array($photo->id, old('photo_ids', $selectedPhotoIds ?? [])) ? 'checked' : '' }}>
                            <div>
                                <img src="{{ $photo->image_url }}" class="w-full h-20 object-cover rounded" alt="">
                                <p class="text-xs mt-1 truncate max-w-[100px]">{{ $photo->title }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('photo_ids')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                <a href="{{ route('admin.collections.index') }}" class="text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-admin-layout>
