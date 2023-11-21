@extends('layouts.app')
@section('title', 'Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('header')
    @include('widgets.navBar')
@endsection


@section('content')
    <div class="container dashboard-container">
        <img src="{{ asset('storage/WelcomeBanner.png') }}" alt="GreetingsBanner" id="DashboardBanner">

        <div class="navbar-collapse" id="dash-nav">
            <ul class="navbar-nav flex-row">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.reports') }}">
                        <span class=""> DENUNCIAS </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.members') }}">
                        <span class="active"> MEMBROS </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.organizations') }}">
                        <span class=""> ORGANIZAÇÕES </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container dashboard-container">
        <h1 class="subtitle"> Membros</h1>
        <div class="dashboard-table">
            <div class="row dashboard-header">
                <div class="col-3">
                    <h1>Utilizador</h1>
                </div>
                <div class="col-3">
                    <h1>Cargo</h1>
                </div>
                <div class="col-4">
                    <h1>Email</h1>
                </div>
                <div class="col-2">
                    <h1>Ações</h1>
                </div>
            </div>

            @foreach ($members as $member)
                <div class="row report">
                    <div class="col-3 dashboard-profile d-flex align-items-center">
                        <div class="pr-2">
                            <img src="{{ asset('storage/profile/' . $member->photo) }}">
                        </div>
                        <div>
                            <h1>{{ $member->name }}</h1>
                        </div>
                    </div>
                    <div class="col-3 dashboard-text-content">
                        @if ($member->isAdmin())
                            <p>Administrador</p>
                        @else
                            <p>Membro</p>
                        @endif
                    </div>
                    <div class="col-4 dashboard-text-content">
                        <p>{{ $member->email }}</p>
                    </div>
                    <div class="col-2 dashboard-actions">
                        <div class="dropdown">
                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('storage/Three-Dots-Icon.svg') }}" alt="more">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">Apagar</a></li>
                                @if (!$member->isAdmin())
                                    <li><a class="dropdown-item" href="#">Tornar Admin</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
    </div>

@endsection
