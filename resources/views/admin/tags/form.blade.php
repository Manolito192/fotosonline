<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($tag) ? __('Edit tag') : __('New tag') }}
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
        <form action="{{ isset($tag) ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" method="POST">
            @csrf
            @if (isset($tag)) @method('PUT') @endif

            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" :value="old('name', $tag->name ?? '')" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="slug" :value="__('Slug')" />
                <x-text-input id="slug" name="slug" :value="old('slug', $tag->slug ?? '')" class="mt-1 block w-full" />
                <p class="mt-1 text-sm text-gray-500">{{ __('Auto-generated from the name if empty') }}</p>
                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                <a href="{{ route('admin.tags.index') }}" class="text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-admin-layout>
