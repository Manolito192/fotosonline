<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Photo;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'categories' => Category::withCount('photos')->orderBy('name_es')->take(4)->get(),
            'recentPhotos' => Photo::with('category')->where('is_published', true)->latest()->take(8)->get(),
        ]);
    }
}
