<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class StaticPageController extends Controller{
    public function showAboutUs() {
        return view('pages.aboutUs', [
            'user' => Auth::user(),
        ]);
    }
}
