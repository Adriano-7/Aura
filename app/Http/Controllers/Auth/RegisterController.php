<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display the register form.
     */
    public function showRegistrationForm() {
        if(Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Register the new user.
     */
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50',
            'username' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^[a-zA-Z][a-zA-Z0-9_.-]*$/'],            
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('home')->withSuccess('Registado com sucesso!');
    }
}
