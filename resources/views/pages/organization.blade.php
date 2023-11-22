@extends('layouts.app')

@section('title', $organization->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organization.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/organization.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @if (session('status'))
        @include('widgets.popUpNotification', ['message' => session('status')])
    @endif

    @if (!$organization->approved)
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <div>Esta organização ainda não foi aprovada.</div>
            @if (Auth::check() && Auth::user()->isAdmin())
                <form id="approveForm" action="{{ route('notification.approveOrganization', ['id' => $organization->id]) }}"
                    method="POST" class="ml-auto">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn">Aprovar.</button>
                </form>
            @endif
        </div>
    @endif

    <div class="container position-relative d-flex align-items-end w-100">
        <img src="{{ asset('storage/organizations/' . $organization->photo) }}" id="bandBanner" class=""
            id="org-img">
        <h1 id="bandName" class="position-absolute text-white">{{ $organization->name }}</h1>
    </div>

    <nav id="orgNav" class="navbar">
        <div class="container">
            <div id="navbarNav">
                <ul class="navbar-nav">
                    @if (Auth::check() && (Auth::user()->isAdmin() || $organization->organizers->contains(Auth::user()->id)))
                        <li class="nav-item">
                            <a class="nav-link active" href="#membros">Membros</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " href="#eventos">Eventos</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link active" href="#eventos">Eventos</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="#sobre">Sobre</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if (Auth::check() && (Auth::user()->isAdmin() || $organization->organizers->contains(Auth::user()->id)))
        <div class="container members-container" id="membros">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                    <h1 id="results-title">Membros</h1>

                    <div class="dashboard-actions">
                        <button type="button" class="btn text-white" data-toggle="modal" data-target="#addMemberModal">
                            Adicionar Membro
                        </button>
                    </div>

                    <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog"
                        aria-labelledby="addMemberModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form id="addMemberForm" action="{{ route('organization.inviteUser') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email" name="email">
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="members-table">
                <div class="row members-header">
                    <div class="col-3">
                        <h1>Utilizador</h1>
                    </div>
                    <div class="col-6">
                        <h1>Email</h1>
                    </div>
                    <div class="col-2">
                        <h1>Remover da organização</h1>
                    </div>
                </div>

                @foreach ($organization->organizers as $member)
                    <div class="row report">
                        <div class="col-3 members-profile d-flex align-items-center">
                            <div class="pr-2">
                                <img src="{{ asset('storage/profile/' . $member->photo) }}">
                            </div>
                            <div>
                                <h1>{{ $member->name }}</h1>
                            </div>
                        </div>
                        <div class="col-6 members-text-content">
                            <p>{{ $member->email }}</p>
                        </div>
                        <div class="col-2 members-actions d-flex justify-content-center">
                            <div class="dropdown">
                                <button class="btn" type="button">
                                    <img src="{{ asset('storage/close-icon.svg') }}" alt="more">
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
        </div>
    @endif


    <div class="container" id="eventos">
        <div class="row">
            <div class="col-12 ">
                <h1 id="results-title">Eventos • {{ $organization->events->count() }} Resultados</h1>
            </div>
        </div>

        @if ($organization->events->count() == 0)
            <div class="card mx-auto">
                <div class="row ">
                    <div class="col-md-12">
                        <p>Não há eventos planeados no momento.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="card mx-auto">
                @foreach ($organization->events as $event)
                    @php
                        $start_date = \Carbon\Carbon::parse($event->start_date);
                        $moreThan24Hours = false;
                        if ($event->end_date) {
                            $end_date = \Carbon\Carbon::parse($event->end_date);
                            $moreThan24Hours = $start_date->diffInHours($end_date) > 24;
                        }
                    @endphp
                    <div class="row event">
                        <div class="col-md-3">
                            <h2>{{ $start_date->format('d M') }}</h2>
                            <h2>{{ $start_date->format('Y') }}</h2>
                        </div>
                        <div class="col-md-7" onclick="window.location.href = '/evento/{{ $event->id }}'"
                            style="cursor: pointer;">
                            <h3>{{ $start_date->formatLocalized('%a') }} • {{ $start_date->format('H:i') }}</h3>
                            <h2>{{ $event->name }}</h2>
                            <h3>{{ $event->city }} • {{ $event->venue }}</h3>
                        </div>
                        @if (Auth::check() && !Auth::user()->isAdmin())
                            <div class="col-md-2 ml-auto">
                                <button type="button" id="join-event">Aderir ao Evento</button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="container" id="sobre">
        <div class="row">
            <div class="col-12">
                <h1 id="results-title">Sobre</h1>
            </div>
        </div>

        <div class="card mx-auto">
            <div class="row ">
                <div class="col-md-12">
                    <p>{{ $organization->description }}</p>
                </div>
            </div>
        </div>
    </div>
    </main>
@endsection
