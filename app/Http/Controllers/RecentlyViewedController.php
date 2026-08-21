<?php

namespace App\Http\Controllers;

use App\Models\RecentlyViewed;

class RecentlyViewedController extends Controller
{
    public function index()
    {
        $query = RecentlyViewed::with('photo.category')
            ->orderByDesc('recently_viewed.created_at');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', session()->getId());
        }

        $recentPhotos = $query->distinct('photo_id')
            ->limit(20)
            ->get();

        return view('recently-viewed.index', compact('recentPhotos'));
    }

    public static function record($photoId): void
    {
        $data = [
            'photo_id' => $photoId,
            'session_id' => session()->getId(),
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        RecentlyViewed::updateOrCreate(
            auth()->check()
                ? ['user_id' => auth()->id(), 'photo_id' => $photoId]
                : ['session_id' => session()->getId(), 'photo_id' => $photoId],
            $data
        );
    }
}
