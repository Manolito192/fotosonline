<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existing = Review::where('user_id', auth()->id())
            ->where('photo_id', $photo->id)
            ->first();

        if ($existing) {
            $existing->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false,
            ]);
        } else {
            Review::create([
                'user_id' => auth()->id(),
                'photo_id' => $photo->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false,
            ]);
        }

        return back()->with('success', __('Review submitted. It will be visible after approval.'));
    }

    public function destroy(Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $photo = $review->photo;
        $review->delete();
        return redirect()->route('photos.show', $photo->slug)
            ->with('success', __('Review deleted'));
    }
}
