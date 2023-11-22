<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Tag;

class SearchController extends Controller{
    public function show(): View{
        return view('pages.search', [
            'user' => Auth::user(),
            'tags' => Tag::all()
        ]);
    }
}