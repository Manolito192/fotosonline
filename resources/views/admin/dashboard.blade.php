<x-admin-layout>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Dashboard') }}</h1>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <p class="text-sm text-gray-500">{{ __('Total photos') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $photoCount }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <p class="text-sm text-gray-500">{{ __('Published photos') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $publishedCount }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <p class="text-sm text-gray-500">{{ __('Total orders') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $orderCount }}</p>
            <p class="mt-1 text-xs text-yellow-600">{{ $pendingOrders }} {{ __('Pending orders') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <p class="text-sm text-gray-500">{{ __('Revenue') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ config('store.currency.symbol') . number_format($revenue, 2, '.', ',') }}</p>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Latest orders') }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ __('View all') }}</a>
        </div>
        @if ($latestOrders->isEmpty())
            <p class="px-6 py-8 text-center text-gray-500">{{ __('No orders yet') }}</p>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Order number') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Customer') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($latestOrders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->formatted_total }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match ($order->status) {
                                        'confirmed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        default => 'bg-yellow-100 text-yellow-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-500 font-medium">{{ __('View order') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin-layout>
