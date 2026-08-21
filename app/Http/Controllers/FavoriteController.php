<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Photo;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('photo.category')
            ->orderByDesc('favorites.created_at')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Photo $photo)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $favorite = Favorite::where('user_id', auth()->id())
            ->where('photo_id', $photo->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = __('Removed from favorites');
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'photo_id' => $photo->id,
            ]);
            $message = __('Added to favorites');
        }

        if ($request = request()) {
            if ($request->ajax()) {
                return response()->json(['success' => true, 'is_favorite' => ! $favorite, 'message' => $message]);
            }
        }

        return back()->with('success', $message);
    }

    public function store(Photo $photo)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $exists = Favorite::where('user_id', auth()->id())
            ->where('photo_id', $photo->id)
            ->exists();

        if (! $exists) {
            Favorite::create([
                'user_id' => auth()->id(),
                'photo_id' => $photo->id,
            ]);
        }

        return back()->with('success', __('Added to favorites'));
    }

    public function destroy(Photo $photo)
    {
        Favorite::where('user_id', auth()->id())
            ->where('photo_id', $photo->id)
            ->delete();

        return back()->with('success', __('Removed from favorites'));
    }
}
