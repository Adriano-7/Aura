@extends('layouts.app')

@section('title', 'Home')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('header')

<nav class="navbar">
    <a class="navbar-logo" href="#"><img src="{{asset('images/AuraLogo.svg')}}" alt="Logo"></a>

    <ul class="navbar-pages">
        <li class="nav-item">
            <a class="nav-link selected" href="#">DASHBOARD</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">NOTIFICAÇÕES</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">MEUS EVENTOS</a>
        </li>
    </ul>

    <nav class="navbar navbar-dark  navbar-expand-sm">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-4" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-list-4">
          <ul class="navbar-nav">
              <li class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{asset('images/profile.png')}}" class="rounded-circle" id="profile-photo">
                <span class="navbar-text">{{ Auth::user()->name }}</span>
              </a>
              <div class="dropdown-menu " aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="#">Definições</a>
                <a class="dropdown-item" href="#">Perfil</a>
                <a class="dropdown-item" href="{{ url('/logout') }}">Log Out</a>
              </div>
            </li>   
          </ul>
        </div>
      </nav>
</nav>


@endsection

@section('content')
@endsection