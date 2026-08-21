<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::published()
            ->withCount('photos')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        if (! $collection->is_published) {
            abort(404);
        }

        $collection->load('photos.category');
        return view('collections.show', compact('collection'));
    }
}
