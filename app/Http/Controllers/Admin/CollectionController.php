<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('photos')->orderByDesc('created_at')->paginate(20);
        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        $photos = Photo::published()->orderBy('title')->get();
        return view('admin.collections.form', compact('photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug',
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'is_published' => 'boolean',
            'photo_ids' => 'array|min:1',
            'photo_ids.*' => 'exists:photos,id',
        ]);

        $collection = Collection::create([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'description' => $request->description,
            'discount_percent' => $request->discount_percent,
            'is_published' => $request->boolean('is_published'),
        ]);

        $collection->photos()->sync($request->photo_ids ?? []);

        return redirect()->route('admin.collections.index')
            ->with('success', __('Collection created successfully'));
    }

    public function edit(Collection $collection)
    {
        $photos = Photo::published()->orderBy('title')->get();
        $selectedPhotoIds = $collection->photos->pluck('id')->toArray();
        return view('admin.collections.form', compact('collection', 'photos', 'selectedPhotoIds'));
    }

    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'is_published' => 'boolean',
            'photo_ids' => 'array|min:1',
            'photo_ids.*' => 'exists:photos,id',
        ]);

        $collection->update([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'description' => $request->description,
            'discount_percent' => $request->discount_percent,
            'is_published' => $request->boolean('is_published'),
        ]);

        $collection->photos()->sync($request->photo_ids ?? []);

        return redirect()->route('admin.collections.index')
            ->with('success', __('Collection updated successfully'));
    }

    public function destroy(Collection $collection)
    {
        $collection->photos()->detach();
        $collection->delete();
        return redirect()->route('admin.collections.index')
            ->with('success', __('Collection deleted successfully'));
    }
}
