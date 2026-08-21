<x-admin-layout>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $category ? __('Edit') . ': ' . $category->name_es : __('New category') }}
        </h1>
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">&larr; {{ __('Cancel') }}</a>
    </div>

    <form method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          class="mt-6 max-w-3xl space-y-6">
        @csrf
        @if ($category)
            @method('PUT')
        @endif

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name_es" :value="__('Name ES')" />
                    <x-text-input id="name_es" name="name_es" type="text" class="mt-1 block w-full" :value="old('name_es', $category?->name_es)" required />
                    <x-input-error :messages="$errors->get('name_es')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="name_en" :value="__('Name EN')" />
                    <x-text-input id="name_en" name="name_en" type="text" class="mt-1 block w-full" :value="old('name_en', $category?->name_en)" required />
                    <x-input-error :messages="$errors->get('name_en')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="slug" :value="__('Slug') . ' (' . __('Optional') . ')'" />
                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono" :value="old('slug', $category?->slug)" />
                <p class="mt-1 text-xs text-gray-400">{{ __('Auto-generated from the Spanish name if empty') }}</p>
            </div>

            <div>
                <x-input-label for="description_es" :value="__('Description ES')" />
                <textarea id="description_es" name="description_es" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description_es', $category?->description_es) }}</textarea>
                <x-input-error :messages="$errors->get('description_es')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description_en" :value="__('Description EN')" />
                <textarea id="description_en" name="description_en" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description_en', $category?->description_en) }}</textarea>
                <x-input-error :messages="$errors->get('description_en')" class="mt-2" />
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                {{ __('Save') }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</x-admin-layout>
