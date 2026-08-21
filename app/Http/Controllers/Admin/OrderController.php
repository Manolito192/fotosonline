<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items', 'user')->latest();

        if ($request->filled('status') && in_array($request->input('status'), [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_CANCELLED])) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.orders.index', ['orders' => $query->paginate(15)]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', ['order' => $order->load('items', 'user')]);
    }

    public function confirm(Order $order)
    {
        abort_if($order->status === Order::STATUS_CONFIRMED, 422);

        $order->update(['status' => Order::STATUS_CONFIRMED]);

        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderConfirmed($order));
        } elseif ($order->customer_email) {
            $notifiable = new \App\Notifications\OrderConfirmed($order);
            \Illuminate\Support\Facades\Notification::route('mail', $order->customer_email)
                ->notify($notifiable);
        }

        return redirect()->back()->with('success', __('Order confirmed successfully'));
    }

    public function cancel(Order $order)
    {
        abort_if($order->status === Order::STATUS_CANCELLED, 422);

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return redirect()->back()->with('success', __('Order cancelled successfully'));
    }
}
