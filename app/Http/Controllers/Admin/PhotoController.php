<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::with('category')->latest();

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%');
        }

        return view('admin.photos.index', ['photos' => $query->paginate(15)]);
    }

    public function create()
    {
        return view('admin.photos.form', [
            'photo' => null,
            'categories' => Category::orderBy('name_es')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePhoto($request);

        $paths = $this->storeImages($request);
        $exif = $this->extractExif($request->file('image'));

        $photo = Photo::create([
            'category_id' => $validated['category_id'] ?: null,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image_path' => $paths['preview'],
            'original_path' => $paths['private'],
            'is_published' => $request->boolean('is_published'),
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'exif_data' => $exif,
        ]);

        if ($request->has('tag_ids')) {
            $photo->tags()->sync($request->tag_ids);
        }

        return redirect()->route('admin.photos.index')->with('success', __('Photo created successfully'));
    }

    public function edit(Photo $photo)
    {
        return view('admin.photos.form', [
            'photo' => $photo,
            'categories' => Category::orderBy('name_es')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => $photo->tags->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Photo $photo)
    {
        $validated = $this->validatePhoto($request, $photo);

        $paths = $this->storeImages($request, $photo);
        $exif = $request->hasFile('image') ? $this->extractExif($request->file('image')) : $photo->exif_data;

        $photo->update([
            'category_id' => $validated['category_id'] ?: null,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'is_published' => $request->boolean('is_published'),
            'image_path' => $paths['preview'],
            'original_path' => $paths['private'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'exif_data' => $exif,
        ]);

        $photo->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('admin.photos.index')->with('success', __('Photo updated successfully'));
    }

    public function destroy(Photo $photo)
    {
        Storage::disk('public')->delete($photo->image_path);
        Storage::disk('local')->delete($photo->original_path);

        $photo->tags()->detach();
        $photo->delete();

        return redirect()->route('admin.photos.index')->with('success', __('Photo deleted successfully'));
    }

    private function validatePhoto(Request $request, ?Photo $photo = null): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:photos,slug'.($photo ? ','.$photo->id : '')],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'image' => $photo ? ['nullable', 'image', 'max:20480'] : ['required', 'image', 'max:20480'],
            'is_published' => ['nullable'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);
    }

    private function storeImages(Request $request, ?Photo $photo = null): array
    {
        if (! $request->hasFile('image')) {
            return [
                'watermarked' => $photo->image_path,
                'private' => $photo->original_path,
            ];
        }

        $file = $request->file('image');
        $name = Str::slug($request->input('title')).'-'.Str::random(8);
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $extension = 'jpg';
        }

        $privateName = $name.'.'.$extension;
        $privatePath = 'photos/originals/'.$privateName;
        $file->storeAs('photos/originals', $privateName, 'local');

        $manager = new ImageManager(new Driver());

        // Public preview (1920px)
        $image = $manager->decode($file->getPathname());
        $image->scaleDown(width: 1920);
        $encoded = $image->encodeUsingFileExtension('jpg', quality: 85);
        Storage::disk('public')->put('photos/'.$name.'.jpg', $encoded->toString());

        if ($photo) {
            Storage::disk('public')->delete($photo->image_path);
            Storage::disk('local')->delete($photo->original_path);
        }

        return [
            'preview' => 'photos/'.$name.'.jpg',
            'private' => $privatePath,
        ];
    }

    private function extractExif($file): ?array
    {
        if (! $file) {
            return null;
        }

        $tmpPath = $file->getRealPath();
        $exif = @exif_read_data($tmpPath);

        if (! $exif || ! is_array($exif)) {
            return null;
        }

        $relevant = [];

        if (isset($exif['Make'])) $relevant['camera_make'] = $exif['Make'];
        if (isset($exif['Model'])) $relevant['camera_model'] = $exif['Model'];
        if (isset($exif['FocalLength'])) $relevant['focal_length'] = $exif['FocalLength'];
        if (isset($exif['FNumber'])) $relevant['aperture'] = $exif['FNumber'];
        if (isset($exif['ExposureTime'])) $relevant['shutter_speed'] = $exif['ExposureTime'];
        if (isset($exif['ISOSpeedRatings'])) $relevant['iso'] = $exif['ISOSpeedRatings'];
        if (isset($exif['DateTimeOriginal'])) $relevant['date_taken'] = $exif['DateTimeOriginal'];
        if (isset($exif['ImageWidth'])) $relevant['width'] = (int) $exif['ImageWidth'];
        if (isset($exif['ImageLength'])) $relevant['height'] = (int) $exif['ImageLength'];

        return ! empty($relevant) ? $relevant : null;
    }
}
