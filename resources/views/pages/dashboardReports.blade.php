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
                    <a class="nav-link" href="{{route('dashboard.reports')}}">
                        <span class="active"> DENUNCIAS </span> </a>
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

    <div class="container dashboard-container">
        <h1 class="subtitle"> Comentários reportados</h1>
        <div class="dashboard-table limit-table">
            <div class="row dashboard-header">
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
                <div class="col-2 dashboard-profile d-flex align-items-center" onclick="window.location.href='{{ route('user', ['username' => $report->comment->author->username]) }}'" style="cursor:pointer">
                    <div class="pr-2">
                        <img src="{{asset('assets/profile/' . $report->comment->author->photo)}}">
                    </div>
                    <div>
                        <h1>{{$report->comment->author->name}}</h1>
                    </div>
                </div>
                <div class="col-4 dashboard-text-content">
                    <p>{{$report->getReasonText()}}</p>
                </div>
                <div class="col-4 dashboard-text-content">
                    <p>{{$report->comment->text}}</p>
                </div>
                <div class="col-2 dashboard-actions">
                    <div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{asset('assets/Three-Dots-Icon.svg')}}" alt="more">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                            <li><button class="dropdown-item delete-comment" data-comment-id="{{ $report->comment->id }}">Apagar comentário</button></li>
                            <li><button class="dropdown-item ignore-comment" data-report-id="{{ $report->id }}">Ignorar</button></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container dashboard-container">
        <h1 class="subtitle"> Eventos reportados</h1>
        <div class="dashboard-table limit-table">
            <div class="row dashboard-header">
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
                <div class="col-2 dashboard-profile d-flex align-items-center" style="cursor: pointer;"
                    onclick="window.location.href='{{ route('event', ['id' => $report->event->id]) }}'">
                    <div class="pr-2">
                        <img src="{{ asset('assets/eventos/' . $report->event->photo) }}">
                    </div>
                    <div>
                        <h1>{{ $report->event->name }}</h1>
                    </div>
                </div>
                <div class="col-8 dashboard-text-content" style="cursor: pointer;"
                    onclick="window.location.href='{{ route('event', ['id' => $report->event->id]) }}'">
                    <p>{{ $report->getReasonText() }}</p>
                </div>
                <div class="col-2 dashboard-actions">
                    <div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{ asset('assets/Three-Dots-Icon.svg') }}" alt="more">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                            <li><button class="dropdown-item delete-event" data-event-id="{{ $report->event->id }}">Eliminar Evento</button></li>
                            <li><button class="dropdown-item ignore-event" data-report-id="{{ $report->id }}">Ignorar</button></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
