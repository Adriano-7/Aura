@extends('layouts.app')

@section('title', 'Log In')


@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login_register.css') }}">
@endsection

@section('content')
    <section class="text-center text-lg-start">
        <section class="vh-100">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6 text-black">
                        <div class="px-5 ms-xl-4">
                            <a href="/"><img src="{{ asset('assets/AuraLogo.svg') }}" alt="Aura Logo"
                                    style="width: 7rem;" class="pt-5 mt-xl-4"></a>
                        </div>

                        <div id = "sample-text">
                            <h1>Iniciar sessão</h1>
                            @if ($errors->has('login_error'))
                                <span class="error">
                                    {{ $errors->first('login_error') }}
                                </span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center px-5 ms-xl-4 mt-xl-n5">
                            <form method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <input type="text" name="email_or_username" placeholder="Email ou nome de utilizador" required />
                                <input type="password" name="password" placeholder="Palavra passe" required/>
                                <button id="submit-button" type="submit">Iniciar sessão</button>
                                <p>Ainda não tem conta? <a href="{{ route('register') }}" id="registo-mensagem">Registe-se!</a></p>
                                <p>Esqueceu-se da sua palavra passe? <a href="{{ route('recoverPassword') }}"> Recupere-a aqui!</a></p>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6 px-0 d-none d-sm-block">
                        <img src="{{ asset('assets/LoginBanner.jpg') }}" alt="Login image" class="w-100 vh-100"
                            style="object-fit: cover; object-position: center;">
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
