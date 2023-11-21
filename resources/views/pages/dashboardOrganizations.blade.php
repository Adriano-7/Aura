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
                    <a class="nav-link" href="{{ route('dashboard.reports') }}">
                        <span class=""> DENUNCIAS </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.members') }}">
                        <span class=""> MEMBROS </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.organizations') }}">
                        <span class="active"> ORGANIZAÇÕES </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container reported-comments">
        <h1 class="subtitle"> Pedidos de Registo</h1>
        <div class="report-table">
            @foreach ($organizationRequests as $request)
                <div class="row report">
                    <div class="col-3 report-profile d-flex align-items-center">
                        <div class="pr-2">
                            <img src="{{ asset('storage/profile/' . $request->userEmitter->photo) }}">
                        </div>
                        <div>
                            <h1>{{ $request->userEmitter->name }}</h1>
                            <h2>{{ $request->getNiceDate() }}</h2>
                        </div>
                    </div>
                    <div class="col-7 report-comment">
                        <p> Solicitou o registo da organização “{{$request->organization->name}}”.</p>
                    </div>
                    <div class="col-2 report-actions">
                        <div class="dropdown">
                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('storage/Three-Dots-Icon.svg') }}" alt="more">
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
    </div>
    </div>


    <div class="container reported-comments">
        <h1 class="subtitle"> Organizações</h1>
        <div class="report-table">
            <div class="row report-header">
                <div class="col-3">
                    <h1>Organização</h1>
                </div>
                <div class="col-2">
                    <h1>Estado</h1>
                </div>
                <div class="col-3">
                    <h1>Descrição</h1>
                </div>
                <div class="col-2">
                    <h1>Membros</h1>
                </div>
                <div class="col-2">
                    <h1>Ações</h1>
                </div>
            </div>

            @foreach ($organizations as $organization)
                <div class="row report">
                    <div class="col-3 report-profile d-flex align-items-center">
                        <div class="pr-2">
                            <img src="{{ asset('storage/organizations/' . $organization->photo) }}">
                        </div>
                        <div>
                            <h1>{{ $organization->name }}</h1>
                        </div>
                    </div>

                    <div class="col-2 report-comment">
                        @if ($organization->approved)
                            <p>Aprovada</p>
                        @else
                            <p>Pendente</p>
                        @endif
                    </div>

                    <div class="col-3 report-comment overflow-hidden">
                        <button type="button" class="btn text-white" data-toggle="modal"
                            data-target="#descriptionModal{{ $organization->id }}">
                            Ver descrição
                        </button>
                        <div class="modal fade" id="descriptionModal{{ $organization->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="descriptionModalLabel{{ $organization->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        {{ $organization->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-2 report-actions">
                        <button type="button" class="btn text-white" data-toggle="modal"
                            data-target="#membersModal{{ $organization->id }}">
                            Ver membros
                        </button>

                        <div class="modal fade" id="membersModal{{ $organization->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="membersModalLabel{{ $organization->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        @foreach ($organization->organizers as $organizer)
                                            <div class="row">
                                                <div class="col-2">
                                                    <img src="{{ asset('storage/profile/' . $organizer->photo) }}"
                                                        style="width: 50px; height: 50px; border-radius: 50%;">
                                                </div>
                                                <div class="col-10">
                                                    <h1>{{ $organizer->name }}</h1>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-2 report-actions">
                        <div class="dropdown">
                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('storage/Three-Dots-Icon.svg') }}" alt="more">
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
    </div>
    </div>

@endsection
