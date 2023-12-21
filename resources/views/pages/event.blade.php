@extends('layouts.app')

@section('title', $event->name . ' • ' . $event->venue)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<!--<link rel="stylesheet" href="{ asset('css/pageNav.css') }}">-->
@endsection

@section('scripts')
<script src="{{ asset('js/event.js') }}" defer></script>
<script src="{{ asset('js/poll.js') }}" defer></script>
<script src="{{ asset('js/orgNav.js') }}" defer></script>
@endsection

@section('header')
@include('widgets.navBar')
@include('widgets.pollModal')
@endsection

@section('content')
@if (session('status'))
@include('widgets.popUpNotification', ['message' => session('status')])
@endif

<div class="container position-relative d-flex align-items-end w-100">
    <img src="{{ asset('assets/eventos/' . $event->photo) }}" id="bannerImg" alt="Banner do evento">
    <div id="bannerOverlay" class="position-absolute row">
        <div class="banner-content">
            <h1 id="bannerName" class="banner-title">{{ $event->name }}</h1>
            @if (!$event->is_public)
            <img src="{{ asset('assets/icons/lock_close.svg') }}" class="banner-lock" alt="Evento privado">
            @endif
            @if (Auth::check())
            <li class="nav-item dropdown">
                <img class="banner-dots" src="{{ asset('assets/icons/three-dots-vertical-white.svg') }}"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;" alt="Opções">

                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                    @if (!Auth::user()->is_admin && !$event->organization->organizers->contains(Auth::user()))
                    <li><a class="dropdown-item" onclick="openReportEventModal({{ $event->id }})"
                            style="cursor: pointer;">Denunciar</a></li>
                    @endif
                    @if (Auth::user()->is_admin || $event->organization->organizers->contains(Auth::user()))
                    <li><a class="dropdown-item" href="{{ route('edit-event', ['id' => $event->id]) }}">Editar</a></li>
                    <li><a class="dropdown-item" onclick="deleteEvent({{ $event->id }})">Apagar</a></li>
                    @endif
                </ul>
            </li>
            @endif
        </div>
    </div>
</div>

<nav id="pageNav" class="navbar">
    <div class="container">
        <div id="navbarNav">
            <ul class="navbar-nav">
                <li data-section="detalhes" class="nav-item">
                    <a class="nav-link active" href="#detalhes">Detalhes</a>
                </li>
                <li data-section="sobre" class="nav-item">
                    <a class="nav-link" href="#sobre">Sobre</a>
                </li>
                <li data-section="comentarios" class="nav-item">
                    <a class="nav-link" href="#comentarios">Comentários</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container navSect" id="detalhes">
    <div class="row">
        <div class="col-12 ">
            <h1 id="section-title">Detalhes</h1>
        </div>
    </div>

    @php
    $start_date = \Carbon\Carbon::parse($event->start_date);
    $moreThan24Hours = false;
    if ($event->end_date) {
    $end_date = \Carbon\Carbon::parse($event->end_date);
    $moreThan24Hours = $start_date->diffInHours($end_date) > 24;
    }

    @endphp

    <div class="card mx-auto">
        <div class="row event">
            <div class="col-md-3 text-center">

                @if ($moreThan24Hours)
                <h2>{{ $start_date->format('d M Y') }}</h2>
                <h2>{{ $end_date->format('d M Y') }}</h2>
                @else
                <h2>{{ $start_date->format('d M') }}</h2>
                <h2>{{ $start_date->format('Y') }}</h2>
                @endif
            </div>
            <div class="col-md-6 text-center">
                @if ($moreThan24Hours)
                <h3>{{ $start_date->formatLocalized('%a') }} • {{ $start_date->format('H:i') }} -
                    {{ $end_date->formatLocalized('%a') }} • {{ $end_date->format('H:i') }}</h3>
                @else
                <h3>{{ $start_date->formatLocalized('%a') }} • {{ $start_date->format('H:i') }} -
                    {{ $end_date->format('H:i') }}</h3>
                @endif

                <h2>{{ $event->name }}</h2>
                <h3>{{ $event->city }} • {{ $event->venue }}</h3>
            </div>
            <div class="col-md-3 ml-auto" id="event-buttons">
                <div class="row">
                    <div class="col-12 text-center">
                        <p id="numParticipants" style="font-size:0.7rem"> {{ $event->participants->count() }}
                            participantes</p>
                    </div>
                    @if (Auth::check() && !Auth::user()->is_admin && !Auth::user()->participatesInEvent($event))
                    <div class="col-12 text-center">
                        <form method="POST" action="{{ route('event.join', $event->id) }}">
                            @csrf
                            <button class="join-event" type="submit">Aderir ao evento</button>
                        </form>
                    </div>
                    @elseif (Auth::check() && Auth::user()->participatesInEvent($event))
                    <div class="col-12 text-center">
                        <form method="POST" action="{{ route('event.leave', $event->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="leave-event" type="submit">Sair do evento</button>
                        </form>
                    </div>
                    @endif
                    <div class="col-12 text-center" style="margin-top: 0.5em;">
                        <button type="button" id="show-participants" class="event-btn" data-toggle="modal"
                            data-target="#participantsModal">Ver participantes </button>
                        @if (Auth::check() && !Auth::user()->is_admin)
                        <button type="button" class="event-btn" data-toggle="modal"
                            data-target="#inviteModal">Convidar</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(($event->polls->isNotEmpty() || Auth::check() && Auth::user()->organizations->contains($event->organization_id))
&&
Auth::check() && (Auth::user()->participatesInEvent($event) ||
Auth::user()->organizations->contains($event->organization_id) || Auth::user()->is_admin))
<div class="container navSect" id="polls">
    <div class="row">
        <div class="row">
            <div class="col-12" id="pollDiv">
                <h1 id="section-title">Sondagens</h1>
                @if(Auth::check() && Auth::user()->organizations->contains($event->organization_id))
                <button type="button" class="btn" data-toggle="modal" data-target="#createPollModal">
                    <i class="bi bi-plus"
                        style="color: white; font-weight: bold; font-size:1.5em; margin-left: 10px;"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @include('widgets.poll')
</div>
@endif

<div class="container navSect" id="sobre">
    <div class="row">
        <div class="col-12">
            <h1 id="section-title">Sobre</h1>
        </div>
    </div>

    <div class="card mx-auto">
        <div class="row">
            <div class="col-md-12">
                <p>{{ $event->description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container navSect" id="comentarios">
    <div class="row">
        <div class="col-12">
            <h1 id="section-title">Comentários • {{ $comments->count() }}</h1>
        </div>
    </div>

    <div class="card mx-auto" style="padding: 1em;">
        <div class="row" id="comment-box">
            <div class="comment-row">
                <div class="container">
                    @if (Auth::check() && !Auth::user()->is_admin && Auth::user()->participatesInEvent($event))
                    <div class="row" id="add-comment-row">
                        <div class="col-12 d-flex align-items-center comment-row">
                            <img class="profile-pic" src="{{ asset('assets/profile/' . $user->photo) }}" alt="profile picture">

                            <form id="add-comment-form" method="POST" action="{{ route('comment.add') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="text" placeholder="Adicione um comentário" autocomplete="off"
                                    required>
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <label for="file-upload" class="icon-button">
                                    <img class="icon" src="{{ asset('assets/clip-icon.svg') }}" alt="Anexar ficheiro">
                                    <input id="file-upload" type="file" name="file" style="display:none;">
                                </label>
                                <button type="submit" class="icon-button insert-comment">
                                    <img class="icon" src="{{ asset('assets/send-icon.svg') }}" alt="Enviar comentário">
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <div class="row" style="display: none;" id="file-upload-section">
                        <div class="col-12 d-flex align-items-center">
                            <span class="file-info" id="file-info">
                                <p id="file-name"></p>
                            </span>
                            <button type="button" class="icon-button remove-file" id="remove-file">
                                <img class="icon" src="{{ asset('assets/close-icon.svg') }}" alt="Remover ficheiro">
                            </button>
                        </div>
                    </div>

                    @if ($comments->count() == 0)
                    <div class="row">
                        <div class="col-12">
                            <p>Este evento ainda não tem comentários.</p>
                        </div>
                    </div>
                    @endif

                    @foreach ($comments as $comment)
                    <div class="comment-row comment" id="{{ 'comment-' . $comment->id }}">
                        <a href="{{ route('user', $comment->author->username) }}">
                            <img class="profile-pic" src="{{ asset('assets/profile/' . $comment->author->photo) }}" alt="profile picture">
                        </a>
                        <div class="comment-content">
                            <div class="username-and-date">
                                <span class="comment-author"
                                    onclick="window.location.href='{{ route('user', $comment->author->username) }}'"
                                    style="cursor: pointer"> {{ $comment->author->name }}</span>
                                <span class="comment-date">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans()
                                    }}</span>

                                @if (Auth::check())
                                <li class="nav-item dropdown">
                                    <img class="three-dots" src="{{ asset('assets/three-dots-horizontal.svg') }}"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="display: none; cursor: pointer;" alt="Opções">

                                    <ul class="dropdown-menu dropdown-menu-dark"
                                        aria-labelledby="navbarDarkDropdownMenuLink">
                                        @if (!Auth::user()->is_admin && Auth::user()->id != $comment->author->id)
                                        <li><a class="dropdown-item"
                                                onclick="openReportCommentModal({{ $comment->id }})"
                                                style="cursor: pointer;">Denunciar</a></li>
                                        @endif

                                        @if (Auth::user()->id == $comment->author->id)
                                        <li><a class="dropdown-item" onclick="activateEditComment({{ $comment->id }})"
                                                style="cursor: pointer;">Editar</a></li>
                                        @endif

                                        @if (Auth::user()->id == $comment->author->id ||
                                        Auth::user()->is_admin ||
                                        $event->organization->organizers->contains(Auth::user()))
                                        <li><a class="dropdown-item" onclick="deleteComment({{ $comment->id }})"
                                                style="cursor: pointer;">Apagar</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                            </div>
                            <p class="comment-text">{{ $comment->text }}</p>
                            @if($comment->file_id)
                            <div class="comment-file">
                                <a href="{{ asset('assets/comments/' . $comment->file->file_name) }}">
                                    <img src="{{ asset('assets/comments/' . $comment->file->file_name) }}" alt="file">
                                </a>
                            </div>
                            @endif
                            <div class="votes-row">
                                @if (Auth::check() &&
                                !Auth::user()->is_admin &&
                                Auth::user()->id != $comment->author->id &&
                                Auth::user()->participatesInEvent($event))
                                @if ($comment->userVote(Auth::user()->id) == 0)
                                <div class="up-btn">
                                    <img src="{{ asset('assets/icons/vote-up.svg') }}" class="vote-icon" alt="UpVote não selecionado">
                                </div>
                                <div class="down-btn">
                                    <img src="{{ asset('assets/icons/vote-down.svg') }}" class="vote-icon" alt="DownVote não selecionado">
                                </div>
                                @endif
                                @if ($comment->userVote(Auth::user()->id) == 1)
                                <div class="up-btn" selected>
                                    <img src="{{ asset('assets/icons/vote-up-selected.svg') }}" class="vote-icon" alt="UpVote selecionado">
                                </div>
                                <div class="down-btn">
                                    <img src="{{ asset('assets/icons/vote-down.svg') }}" class="vote-icon" alt="DownVote não selecionado">
                                </div>
                                @endif
                                @if ($comment->userVote(Auth::user()->id) == -1)
                                <div class="up-btn">
                                    <img src="{{ asset('assets/icons/vote-up.svg') }}" class="vote-icon" alt="UpVote não selecionado">
                                </div>
                                <div class="down-btn" selected>
                                    <img src="{{ asset('assets/icons/vote-down-selected.svg') }}" class="vote-icon" alt="DownVote selectionado">
                                </div>
                                @endif
                                @else
                                <img src="{{ asset('assets/icons/vote-disallowed.svg') }}" class="vote-icon" alt="Votação não permitida"
                                    style="margin-right:0.5em">
                                @endif
                                <span class="comment-votes" inert>{{ $comment->vote_balance }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@include('widgets.reportCommentModal')
@include('widgets.reportEventModal')
@include('widgets.pollModal')
@include('widgets.participantsModal')
@include('widgets.inviteModal')
</div>
@endsection
