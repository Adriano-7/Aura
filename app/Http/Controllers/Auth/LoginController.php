<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;

class LoginController extends Controller
{

    /**
     * Display a login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');

    }

    /* 
    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <input type="email" name="email-or-userame" placeholder="Email ou nome de utilizador" required />
        <input type="password" name="password" placeholder="Palavra passe" required />
        <button id="submit-button" type="submit">Iniciar sessão</button>
        <p>Ainda não tem conta? <a href="{{ route('register') }}" id="registo-mensagem">Registe-se!</a></p>
    </form>
    */

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $email_or_username = $request->input('email_or_username');
        $fieldType = (strpos($email_or_username, '@') !== false) ? 'email' : 'username';  

        $credentials = [
            $fieldType => $email_or_username,
            'password' => $request->input('password'),
        ];

        $request->validate([
            'email_or_username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('home')->withSuccess('Autenticado com sucesso.');
        }

        return back()->withErrors([
            'login_error' => 'Credenciais inválidas.',
        ])->withInput();
    }

    /**
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->withSuccess('Terminou a sessão com sucesso.');
    }
}
