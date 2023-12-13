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

    public function showResetPasswordForm(Request $request) {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $data = DB::table('recover_password')->where('token', $request->route('token'))->first();

        if (!$data) {
            return redirect()->route('home')->withErrors(['token' => 'Token inválido!']);
        }

        if (strtotime($data->created_at) < strtotime('-1 day')) {
            return redirect()->route('home')->withErrors(['token' => 'Token expirado!']);
        }

        return view('auth.resetPassword', ['email' => $data->email]);
    }

    public function reset(Request $request) {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $data = DB::table('recover_password')->where('email', $request->email)->first();

        DB::table('users')->where('email', $request->email)->update([
            'password' => bcrypt($request->password),
        ]);

        DB::table('recover_password')->where('email', $request->email)->delete();

        return redirect()->route('home')->withSuccess('Password alterada com sucesso!');
    }
}
