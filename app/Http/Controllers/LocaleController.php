<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        if (in_array($locale, ['es', 'en'])) {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
