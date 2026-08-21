<x-admin-layout>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Order') }} #{{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">&larr; {{ __('Cancel') }}</a>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('Order summary') }}</h2>
                </div>
                <ul class="divide-y divide-gray-200">
                    @foreach ($order->items as $item)
                        <li class="flex items-center gap-4 p-4">
                            @if ($item->image_url)
                                <div class="shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-gray-200">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->photo_title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->photo_title }}</p>
                            </div>
                            <p class="text-sm text-gray-500 shrink-0">{{ $item->formatted_price }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <span class="font-semibold text-gray-900">{{ __('Total') }}</span>
                    <span class="text-xl font-bold text-gray-900">{{ $order->formatted_total }}</span>
                </div>
            </div>

            @if ($order->notes)
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('Notes') }}</h2>
                    <p class="mt-2 text-sm text-gray-600 whitespace-pre-line">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Customer') }}</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">{{ __('Full name') }}</dt>
                        <dd class="font-medium text-gray-900">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('Email') }}</dt>
                        <dd class="font-medium text-gray-900">{{ $order->customer_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('Date') }}</dt>
                        <dd class="font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">{{ __('Status') }}</dt>
                        <dd>
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
                        </dd>
                    </div>
                </dl>
            </div>

            @if ($order->status === 'pending')
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 space-y-3">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('Actions') }}</h2>
                    <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                            {{ __('Confirm payment') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" onsubmit="return confirm('{{ __('Cancel order') }}?')">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                            {{ __('Cancel order') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
