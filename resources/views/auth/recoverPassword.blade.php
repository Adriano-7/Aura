@extends('layouts.app')

@section('title', 'Recuperar password')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login_register.css') }}">
@endsection

@section('content')
    @if (session('success'))
        @include('widgets.popUpNotification', ['message' => session('success')])
    @endif

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
                            <h1>Recupere a sua password</h1>
                            <h3>Insira o seu email para recuperar a sua password</h3>
                        </div>

                        <div class="d-flex h-custom-2 px-5 ms-xl-4 mt-xl-n5">
                            <form method="POST" action="{{ route('recoverPassword') }}">
                                @csrf
                                <input type="email" name="email" placeholder="Email"
                                    required />

                                <button id="submit-button" type="submit">Recuperar password</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-6 px-0 d-none d-sm-block">
                        <img src="{{ asset('assets/LoginBanner.jpg') }}" alt="Recover password image" class="w-100 vh-100"
                            style="object-fit: cover; object-position: center;">
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
