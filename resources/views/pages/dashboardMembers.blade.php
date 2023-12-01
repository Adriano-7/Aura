@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <div class="container dashboard-container">
        <img src="{{ asset('assets/WelcomeBanner.png') }}" alt="GreetingsBanner" id="DashboardBanner">

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
                            <img src="{{ asset('assets/profile/' . $member->photo) }}">
                        </div>
                        <div>
                            <h1>{{ $member->name }}</h1>
                        </div>
                    </div>
                    <div class="col-3 dashboard-text-content">
                        @if ($member->is_admin)
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
                                <img src="{{ asset('assets/Three-Dots-Icon.svg') }}" alt="more">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                                <li><button class="dropdown-item delete-user" data-user-id="{{ $member->id }}">Apagar</button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
