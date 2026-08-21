<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['photo', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()->route('admin.reviews.index')
            ->with('success', __('Review approved'));
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        return redirect()->route('admin.reviews.index')
            ->with('success', __('Review rejected'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', __('Review deleted'));
    }
}
