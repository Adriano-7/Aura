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
                            <h3>Recupere a sua password!</h3>

                            @if ($errors->has('password'))
                                <span class="error">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center px-5 ms-xl-4 mt-xl-n5">
                            <form method="POST">
                                @csrf
                                <input type="email" name="email" value="{{ $email }}" hidden />

                                <input type="password" name="password" placeholder="Nova palavra passe" required />

                                <input type="password" name="password_confirmation" placeholder="Confirme a palavra passe"
                                    required />

                                <button id="submit-button" type="submit">Recuperar</button>
                                
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
