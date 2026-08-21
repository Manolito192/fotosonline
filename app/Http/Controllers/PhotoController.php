<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::with(['category', 'tags'])->where('is_published', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->input('category')));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $request->input('tag')));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%')
                  ->orWhereHas('tags', fn ($tq) => $tq->where('name', 'like', '%'.$search.'%'));
            });
        }

        match ($request->input('sort')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->withCount('favorites')->orderByDesc('favorites_count'),
            default => $query->latest(),
        };

        $photos = $query->paginate(12)->withQueryString();

        return view('photos.index', [
            'photos' => $photos,
            'categories' => \App\Models\Category::orderBy('name_es')->get(),
        ]);
    }

    public function show(Photo $photo)
    {
        abort_unless($photo->is_published, 404);

        \App\Http\Controllers\RecentlyViewedController::record($photo->id);

        $photo->load('tags', 'category');

        $related = Photo::where('id', '!=', $photo->id)
            ->where('is_published', true)
            ->where('category_id', $photo->category_id)
            ->limit(4)
            ->get();

        return view('photos.show', ['photo' => $photo, 'related' => $related]);
    }
}
