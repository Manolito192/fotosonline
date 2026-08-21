<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(Request $request, Order $order)
    {
        $this->authorizeAccess($request, $order);

        if (! $order->isConfirmed()) {
            return redirect()->route('home')->with('error', __('Your order has not been confirmed yet'));
        }

        return view('downloads.index', ['order' => $order->load('items')]);
    }

    public function item(Request $request, Order $order, OrderItem $item)
    {
        $this->authorizeAccess($request, $order);

        abort_unless($item->order_id === $order->id, 403);
        abort_unless($order->isConfirmed(), 403);
        abort_unless(Storage::disk('local')->exists($item->original_path), 404);

        $fileName = \Illuminate\Support\Str::slug($item->photo_title).'.'.pathinfo($item->original_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($item->original_path, $fileName);
    }

    private function authorizeAccess(Request $request, Order $order): void
    {
        $token = $request->query('token');

        if ($token && hash_equals($order->download_token, $token)) {
            return;
        }

        if ($request->user() && $request->user()->id === $order->user_id) {
            return;
        }

        abort(403);
    }
}
