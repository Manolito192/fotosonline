<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = app(CartController::class);
        $photos = $cart->cartPhotos();

        if ($photos->isEmpty()) {
            return redirect()->route('photos.index');
        }

        $subtotal = (float) $photos->sum('price');
        $coupon = $this->appliedCoupon();
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total = max(0, $subtotal - $discount);

        return view('checkout.index', compact('photos', 'subtotal', 'coupon', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $cart = app(CartController::class);
        $photos = $cart->cartPhotos();

        if ($photos->isEmpty()) {
            return redirect()->route('photos.index');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subtotal = (float) $photos->sum('price');
        $coupon = $this->appliedCoupon();
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total = max(0, $subtotal - $discount);

        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'user_id' => $request->user()?->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'notes' => $validated['notes'] ?? null,
            'coupon_id' => $coupon?->id,
            'discount' => $discount,
            'total' => $total,
            'status' => Order::STATUS_PENDING,
            'download_token' => \Illuminate\Support\Str::random(40),
        ]);

        foreach ($photos as $photo) {
            OrderItem::create([
                'order_id' => $order->id,
                'photo_id' => $photo->id,
                'photo_title' => $photo->title,
                'photo_slug' => $photo->slug,
                'image_path' => $photo->image_path,
                'original_path' => $photo->original_path,
                'price' => $photo->price,
            ]);
        }

        if ($coupon) {
            $coupon->increment('used_count');
        }

        $admin = \App\Models\User::where('is_admin', true)->first();
        if ($admin) {
            $admin->notify(new \App\Notifications\OrderPlaced($order));
        }

        $cart->clear();

        return redirect()->route('checkout.confirmation', $order)->with('just_placed', true);
    }

    public function confirmation(Order $order)
    {
        $order->load('items');

        $subtotal = (float) $order->items->sum('price');
        $discount = (float) $order->discount;
        $total = (float) $order->total;

        return view('checkout.confirmation', compact('order', 'subtotal', 'discount', 'total'));
    }

    private function appliedCoupon(): ?Coupon
    {
        $couponId = session()->get('coupon_id');
        if (! $couponId) {
            return null;
        }
        $coupon = Coupon::find($couponId);
        if (! $coupon || ! $coupon->isValid()) {
            session()->forget('coupon_id');
            return null;
        }
        return $coupon;
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.date('Ymd').'-'.strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
