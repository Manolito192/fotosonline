<x-admin-layout>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Photos') }}</h1>
        <a href="{{ route('admin.photos.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
            + {{ __('New photo') }}
        </a>
    </div>

    <div class="mt-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        @if ($photos->isEmpty())
            <p class="px-6 py-12 text-center text-gray-500">{{ __('There are no photos') }}</p>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Image') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($photos as $photo)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-200">
                                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $photo->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $photo->category?->localizedName() ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $photo->formatted_price }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $photo->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $photo->is_published ? __('Published') : __('Hidden') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-3">
                                <a href="{{ route('admin.photos.edit', $photo) }}" class="text-indigo-600 hover:text-indigo-500 font-medium">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" class="inline" onsubmit="return confirm('{{ __('Delete') }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-500 font-medium">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">
        {{ $photos->links() }}
    </div>
</x-admin-layout>
