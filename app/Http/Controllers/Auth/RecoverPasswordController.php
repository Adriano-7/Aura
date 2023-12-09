<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;

class RecoverPasswordController extends Controller
{

    /**
     * Display the recover password form.
     */
    public function showRecoverPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.recoverPassword');
    }
}
