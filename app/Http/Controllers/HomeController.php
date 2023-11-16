<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function show(string $id): View
    {
        return view('pages.home');
    }

    /**
     * Shows all cards.
     */
    public function list()
    {
        return view('pages.home');
    }
}
