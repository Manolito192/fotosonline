<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Coupon;
use App\Models\Photo;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $photos = $this->cartPhotos();
        $subtotal = (float) $photos->sum('price');
        $coupon = $this->appliedCoupon();
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total = max(0, $subtotal - $discount);

        return view('cart.index', compact('photos', 'subtotal', 'coupon', 'discount', 'total'));
    }

    public function add(Request $request, Photo $photo)
    {
        abort_unless($photo->is_published, 404);

        $cart = session()->get('cart', []);
        if (! in_array($photo->id, $cart)) {
            $cart[] = $photo->id;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', __('Photo added to your cart'));
    }

    public function addCollection(Request $request, Collection $collection)
    {
        abort_unless($collection->is_published, 404);

        $cart = session()->get('cart', []);
        foreach ($collection->photos as $photo) {
            if ($photo->is_published && ! in_array($photo->id, $cart)) {
                $cart[] = $photo->id;
            }
        }
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', __('Collection added to your cart'));
    }

    public function remove(Photo $photo)
    {
        $cart = session()->get('cart', []);
        $cart = array_values(array_diff($cart, [$photo->id]));
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return back()->withErrors(['coupon_code' => __('Invalid or expired coupon')]);
        }

        $subtotal = (float) $this->cartPhotos()->sum('price');

        if ($subtotal < $coupon->min_order) {
            return back()->withErrors([
                'coupon_code' => __('Minimum order for this coupon is :amount', ['amount' => config('store.currency.symbol') . number_format($coupon->min_order, 2, '.', ',')]),
            ]);
        }

        session()->put('coupon_id', $coupon->id);

        return back()->with('success', __('Coupon applied successfully'));
    }

    public function removeCoupon()
    {
        session()->forget('coupon_id');
        return back()->with('success', __('Coupon removed'));
    }

    public function count(): int
    {
        return count(session()->get('cart', []));
    }

    public function clear(): void
    {
        session()->forget('cart');
        session()->forget('coupon_id');
    }

    public function cartPhotos()
    {
        $ids = array_values(array_unique(session()->get('cart', [])));

        if (empty($ids)) {
            return collect();
        }

        $photos = Photo::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(fn ($id) => $photos->get($id))->filter();
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
}
