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
    <div class="containder reported-comments">
        <img src="{{ asset('storage/WelcomeBanner.png') }}" alt="GreetingsBanner" id="DashboardBanner">

        <div class="navbar-collapse" id="dash-nav">
            <ul class="navbar-nav flex-row">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('dashboard.reports')}}">
                        <span class="active"> DENUNCIAS </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('dashboard.members')}}">
                        <span class=""> MEMBROS </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('dashboard.organizations')}}">
                        <span class=""> ORGANIZAÇÕES </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container reported-comments">
        <h1 class="subtitle"> Comentários reportados</h1>
        <div class="report-table">
            <div class="row report-header">
                <div class="col-2">
                    <h1>Utilizador Reportado</h1>
                </div>
                <div class="col-4">
                    <h1>Motivo</h1>
                </div>
                <div class="col-4">
                    <h1>Comentário</h1>
                </div>
                <div class="col-2">
                    <h1>Ações</h1>
                </div>
            </div>

            @foreach ($reportComments as $report)
            <div class="row report">
                <div class="col-2 report-profile d-flex align-items-center">
                    <div class="pr-2">
                        <img src="{{asset('storage/profile/' . $report->comment->author->photo)}}">
                    </div>
                    <div>
                        <h1>{{$report->comment->author->name}}</h1>
                    </div>
                </div>
                <div class="col-4 report-comment">
                    <p>{{$report->reason->text}}</p>
                </div>
                <div class="col-4 report-comment">
                    <p>{{$report->comment->text}}</p>
                </div>
                <div class="col-2 report-actions">
                    <div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{asset('storage/Three-Dots-Icon.svg')}}" alt="more">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">Apagar</a></li>
                            <li><a class="dropdown-item" href="#">Bloquear</a></li>
                            <li><a class="dropdown-item" href="#">Ignorar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container reported-comments">
        <h1 class="subtitle"> Eventos reportados</h1>
        <div class="report-table">
            <div class="row report-header">
                <div class="col-2">
                    <h1>Evento Reportado</h1>
                </div>
                <div class="col-8">
                    <h1>Motivo</h1>
                </div>
                <div class="col-2">
                    <h1>Ações</h1>
                </div>
            </div>

            @foreach ($reportEvents as $report)
            <div class="row report">
                <div class="col-2 report-profile d-flex align-items-center">
                    <div class="pr-2">
                        <img src="{{asset('storage/eventos/' . $report->event->photo)}}">
                    </div>
                    <div>
                        <h1>{{$report->event->name}}</h1>
                    </div>
                </div>
                <div class="col-8 report-comment">
                    <p>{{$report->reason->text}}</p>
                </div>
                <div class="col-2 report-actions">
                    <div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{asset('storage/Three-Dots-Icon.svg')}}" alt="more">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">Apagar</a></li>
                            <li><a class="dropdown-item" href="#">Bloquear</a></li>
                            <li><a class="dropdown-item" href="#">Ignorar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

@endsection
