<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Mail\MailModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function post(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (User::where('email', $request->email)->first()) {
            $token = Str::random(64);

            DB::table('recover_password')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);

            $mailData = [
                'url' => env('APP_URL') . "/recuperar-password/" . $token,
            ];

            Mail::to($request->email)->send(new MailModel($mailData));
        }

        // success regardless of whether the email exists or not (security reasons)
        return back()->withSuccess('Email enviado com sucesso!');
    }
}
