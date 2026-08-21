<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Photo;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'photoCount' => Photo::count(),
            'publishedCount' => Photo::where('is_published', true)->count(),
            'orderCount' => Order::count(),
            'pendingOrders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'revenue' => Order::where('status', Order::STATUS_CONFIRMED)->sum('total'),
            'latestOrders' => Order::with('items')->latest()->take(5)->get(),
        ]);
    }
}
