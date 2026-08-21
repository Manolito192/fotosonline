<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function myPurchases(Request $request)
    {
        $orders = $request->user()->orders()->with('items')->latest()->get();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Request $request, \App\Models\Order $order)
    {
        abort_unless($request->user()->id === $order->user_id, 403);

        return view('orders.show', ['order' => $order->load('items')]);
    }
}
