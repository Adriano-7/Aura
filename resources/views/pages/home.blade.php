@extends('layouts.app')

@section('title', 'Home')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('header')
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"> <img src="{{ asset('storage/AuraLogo.svg') }}"> </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
                aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExample05">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">DASHBOARD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/notifications') }}">NOTIFICAÇÕES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">MEUS EVENTOS</a>
                    </li>
                </ul>

                @if (Auth::check())
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown ">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img src="{{ asset('storage/profile/' . $user->photo)  }}"  class="rounded-circle">
                            <span class="navbar-text dropdown-toggle">{{ $user->name }}</span>
                        </a>
            
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Definições</a></li>
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="{{ url('/logout') }}">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            @else
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">LOGIN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/register') }}">REGISTAR</a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>
@endsection

@section('content')
    @include('widgets.eventRow', ['events' => $events])
    @yield('eventRow')
@endsection
