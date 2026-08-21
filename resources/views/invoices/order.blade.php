<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 40px; font-size: 12px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { font-size: 24px; margin: 0; }
        .header .info { text-align: right; }
        .header .info p { margin: 2px 0; }
        .section { margin-bottom: 30px; }
        .section h2 { font-size: 14px; text-transform: uppercase; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9fafb; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>{{ config('app.name') }}</h1>
            <p>{{ __('Invoice') }}</p>
        </div>
        <div class="info">
            <p><strong>{{ __('Order number') }}:</strong> {{ $order->order_number }}</p>
            <p><strong>{{ __('Date') }}:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>{{ __('Status') }}:</strong> {{ $order->status_label }}</p>
        </div>
    </div>

    @if ($order->customer_name)
    <div class="section">
        <h2>{{ __('Customer') }}</h2>
        <p>{{ $order->customer_name }}</p>
        <p>{{ $order->customer_email }}</p>
    </div>
    @endif

    <div class="section">
        <h2>{{ __('Items') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Item') }}</th>
                    <th class="text-right">{{ __('Unit price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->photo_title }}</td>
                    <td class="text-right">{{ config('store.currency.symbol') . number_format($item->price, 2, '.', ',') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>{{ __('Total') }}</td>
                    <td class="text-right">{{ $order->formatted_total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} &mdash; {{ __('All rights reserved') }}</p>
    </div>
</body>
</html>
