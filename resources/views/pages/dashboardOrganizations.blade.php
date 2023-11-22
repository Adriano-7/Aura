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
    @if (session('status'))
        @include('widgets.popUpNotification', ['message' => session('status')])
    @endif

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

    <div class="container dashboard-container">
        <h1 class="subtitle"> Pedidos de Registo</h1>
        <div class="dashboard-table limit-table">
            @foreach ($organizationRequests as $request)
                <div class="row report">
                    <div class="col-3 dashboard-profile d-flex align-items-center" style="cursor: pointer;"
                    onclick="window.location.href='{{ route('organization.show', ['id' => $request->organization->id]) }}'">
                        <div class="pr-2">
                            <img src="{{ asset('storage/profile/' . $request->userEmitter->photo) }}">
                        </div>
                        <div>
                            <h1>{{ $request->userEmitter->name }}</h1>
                            <h2>{{ $request->getNiceDate() }}</h2>
                        </div>
                    </div>
                    <div class="col-7 dashboard-text-content" style="cursor: pointer;"
                    onclick="window.location.href='{{ route('organization.show', ['id' => $request->organization->id]) }}'">
                        <p> Solicitou o registo da organização “{{ $request->organization->name }}”.</p>
                    </div>
                    <div class="col-2 dashboard-actions">
                        <div class="dropdown">
                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('storage/Three-Dots-Icon.svg') }}" alt="more">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <form id="approveForm" action="{{ route('notification.approveOrganization', ['id' => $request->organization->id]) }}"
                                        method="POST" class="ml-auto">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="dropdown-item">Aprovar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
    </div>


    <div class="container dashboard-container">
        <h1 class="subtitle"> Organizações</h1>
        <div class="dashboard-table limit-table">
            <div class="row dashboard-header">
                <div class="col-3">
                    <h1>Organização</h1>
                </div>
                <div class="col-3">
                    <h1>Estado</h1>
                </div>
                <div class="col-3">
                    <h1>Membros</h1>
                </div>
                <div class="col-3">
                    <h1>Ações</h1>
                </div>
            </div>

            @foreach ($organizations as $organization)
                <div class="row report">
                    <div class="col-3 dashboard-profile d-flex align-items-center" style="cursor: pointer;"
                        onclick="window.location.href='{{ route('organization.show', ['id' => $organization->id]) }}'">
                        <div class="pr-2">
                            <img src="{{ asset('storage/organizations/' . $organization->photo) }}">
                        </div>
                        <div>
                            <h1>{{ $organization->name }}</h1>
                        </div>
                    </div>

                    <div class="col-3 dashboard-text-content">
                        @if ($organization->approved)
                            <p>Aprovada</p>
                        @else
                            <p>Pendente</p>
                        @endif
                    </div>

                    <div class="col-3 dashboard-actions">
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

                    <div class="col-2 dashboard-actions">
                        <div class="dropdown">
                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('storage/Three-Dots-Icon.svg') }}" alt="more">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton">
                                <li><button class="dropdown-item delete-org" data-org-id="{{ $organization->id }}">Apagar</button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
