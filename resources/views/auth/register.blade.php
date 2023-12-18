@extends('layouts.app')

@section('title', 'Register')

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
                            <a href="/"><img src="{{ asset('assets/AuraLogo.svg') }}" alt="Logo"
                                    style="width: 7rem;" class="pt-5 mt-xl-4"></a>
                        </div>
                        <div id = "sample-text">
                            <h1>Vamos criar a tua conta!</h1>
                            <h3>Junta-te à nossa comunidade!</h3>

                            @if ($errors->has('username'))
                                <span class="error">
                                    {{ $errors->first('username') }}
                                </span>

                            @if ($errors->has('email'))
                                <span class="error">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif

                            @if ($errors->has('password'))
                                <span class="error">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center px-5 ms-xl-4 mt-xl-n5">
                            <form method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}
                                <input type="text" name="name" placeholder="Primeiro e último nome" required autocomplete="off"/>
                                <input type="text" name="username" placeholder="Nome de utilizador" required autocomplete="off"/>
                                <input type="email" name="email" placeholder="Email" required autocomplete="off"/>
                                <input type="password" name="password" placeholder="Palavra passe" required autocomplete="off"/>
                                <input type="password" name="password_confirmation" placeholder="Confirme a palavra passe"required autocomplete="off"r/>
                                <button id="submit-button" type="submit">Registar</button>
                                <p>Já tem conta? <a href="{{ route('login') }}" id="registo-mensagem">Inicie sessão!</a></p>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6 px-0 d-none d-sm-block">
                        <img src="{{ asset('assets/LoginBanner.jpg') }}" alt="Register image" class="w-100 vh-100"
                            style="object-fit: cover; object-position: center;">
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
