@extends('layouts.app')

@section('title', $event->name  . ' • ' . $event->venue)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <!--<link rel="stylesheet" href="{ asset('css/pageNav.css') }}">-->
@endsection

@section('scripts')
    <script src="{{ asset('js/event.js') }}" defer></script>
    <script src="{{ asset('js/orgNav.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @if (session('status'))
        @include('widgets.popUpNotification', ['message' => session('status')])
    @endif

    <div class="container position-relative d-flex align-items-end w-100">
        <img src="{{ asset('assets/eventos/' . $event->photo) }}" id="bannerImg">
        <div id="bannerOverlay" class="position-absolute row">
            <div class="banner-content">
                <h1 id="bannerName" class="banner-title">{{ $event->name }}</h1>
                @if (!$event->is_public)
                    <img src="{{ asset('assets/icons/lock_close.svg') }}" class="banner-lock">
                @endif
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <img class="banner-dots" src="{{ asset('assets/icons/three-dots-vertical-white.svg') }}"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">

                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            @if (!Auth::user()->is_admin && !$event->organization->organizers->contains(Auth::user()))
                                <li><a class="dropdown-item" onclick="openReportEventModal({{ $event->id }})"
                                        style="cursor: pointer;">Denunciar</a></li>
                            @endif
                            @if (Auth::user()->is_admin || $event->organization->organizers->contains(Auth::user()))
                                <li><a class="dropdown-item"
                                        href="{{ route('edit-event', ['id' => $event->id]) }}">Editar</a></li>
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
                                    <button id="join-event" type="submit">Aderir ao evento</button>
                                </form>
                            </div>
                        @elseif (Auth::check() && Auth::user()->participatesInEvent($event))
                            <div class="col-12 text-center">
                                <form method="POST" action="{{ route('event.leave', $event->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button id="leave-event" type="submit">Sair do evento</button>
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
                                    <img class="profile-pic" src="{{ asset('assets/profile/' . $user->photo) }}">

                                    <form id="add-comment-form" method="POST" action="{{ route('comment.add') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" name="text" placeholder="Adicione um comentário"
                                            autocomplete="off" required>
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <label for="file-upload" class="icon-button">
                                            <img class="icon" src="{{ asset('assets/clip-icon.svg') }}">
                                            <input id="file-upload" type="file" name="file" style="display:none;">
                                        </label>
                                        <button type="submit" class="icon-button insert-comment">
                                            <img class="icon" src="{{ asset('assets/send-icon.svg') }}">
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
                                    <img class="icon" src="{{ asset('assets/close-icon.svg') }}">
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
                                    <img class="profile-pic"
                                        src="{{ asset('assets/profile/' . $comment->author->photo) }}">
                                </a>
                                <div class="comment-content">
                                    <div class="username-and-date">
                                        <span class="comment-author"
                                            onclick="window.location.href='{{ route('user', $comment->author->username) }}'"
                                            style="cursor: pointer"> {{ $comment->author->name }}</span>
                                        <span
                                            class="comment-date">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}</span>

                                        @if (Auth::check())
                                            <li class="nav-item dropdown">
                                                <img class="three-dots"
                                                    src="{{ asset('assets/three-dots-horizontal.svg') }}"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    style="display: none; cursor: pointer;">

                                                <ul class="dropdown-menu dropdown-menu-dark"
                                                    aria-labelledby="navbarDarkDropdownMenuLink">
                                                    @if (!Auth::user()->is_admin && Auth::user()->id != $comment->author->id)
                                                        <li><a class="dropdown-item"
                                                                onclick="openReportCommentModal({{ $comment->id }})"
                                                                style="cursor: pointer;">Denunciar</a></li>
                                                    @endif

                                                    @if (Auth::user()->id == $comment->author->id)
                                                        <li><a class="dropdown-item"
                                                                onclick="activateEditComment({{ $comment->id }})"
                                                                style="cursor: pointer;">Editar</a></li>
                                                    @endif

                                                    @if (Auth::user()->id == $comment->author->id ||
                                                            Auth::user()->is_admin ||
                                                            $event->organization->organizers->contains(Auth::user()))
                                                        <li><a class="dropdown-item"
                                                                onclick="deleteComment({{ $comment->id }})"
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
                                            <img src="{{ asset('assets/comments/' . $comment->file->file_name) }}">
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
                                                    <img src="{{ asset('assets/icons/vote-up.svg') }}" class="vote-icon">
                                                </div>
                                                <div class="down-btn">
                                                    <img src="{{ asset('assets/icons/vote-down.svg') }}"
                                                        class="vote-icon">
                                                </div>
                                            @endif
                                            @if ($comment->userVote(Auth::user()->id) == 1)
                                                <div class="up-btn" selected>
                                                    <img src="{{ asset('assets/icons/vote-up-selected.svg') }}"
                                                        class="vote-icon">
                                                </div>
                                                <div class="down-btn">
                                                    <img src="{{ asset('assets/icons/vote-down.svg') }}"
                                                        class="vote-icon">
                                                </div>
                                            @endif
                                            @if ($comment->userVote(Auth::user()->id) == -1)
                                                <div class="up-btn">
                                                    <img src="{{ asset('assets/icons/vote-up.svg') }}" class="vote-icon">
                                                </div>
                                                <div class="down-btn" selected>
                                                    <img src="{{ asset('assets/icons/vote-down-selected.svg') }}"
                                                        class="vote-icon">
                                                </div>
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/icons/vote-disallowed.svg') }}" class="vote-icon"
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

    <div class="modal fade" id="reportCommentModal" tabindex="-1" role="dialog"
        aria-labelledby="reportCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="reportCommentModalLabel">Denunciar Comentário</h5>

                    <form id="reportCommentForm">
                        @csrf
                        @method('POST')
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="inappropriate_content"
                                value="inappropriate_content" onchange="updateButtonColor('reportCommentModal')">
                            <label class="form-check-label" for="inappropriate_content">
                                Conteúdo inadequado ou não apropriado
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="violence_threats"
                                value="violence_threats" onchange="updateButtonColor('reportCommentModal')">
                            <label class="form-check-label" for="violence_threats">
                                Ameaças ou incitação à violência
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="incorrect_information"
                                value="incorrect_information" onchange="updateButtonColor('reportCommentModal')">
                            <label class="form-check-label" for="incorrect_information">
                                Informações incorretas ou enganosas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="harassment_bullying"
                                value="harassment_bullying" onchange="updateButtonColor('reportCommentModal')">
                            <label class="form-check-label" for="harassment_bullying">
                                Assédio ou bullying
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="commercial_spam"
                                value="commercial_spam" onchange="updateButtonColor('reportCommentModal')">
                            <label class="form-check-label" for="commercial_spam">
                                Conteúdo comercial ou spam
                            </label>
                        </div>
                    </form>

                    <div class="modal-footer" style="border-top: none;">
                        <button type="button" data-dismiss="modal"
                            style="color: white; border-radius: 0.5em; padding: 0.5em;">Cancelar</button>
                        <button type="button" id="denunciarButton" onclick="reportComment()"
                            style="color: #808080; border-radius: 0.5em; padding: 0.5em;" disabled>Denunciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportEventModal" tabindex="-1" role="dialog" aria-labelledby="reportEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="reportEventModalLabel">Denunciar Evento</h5>
                    <form id="reportEventForm">
                        @csrf
                        @method('POST')
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="suspect_fraud"
                                value="suspect_fraud" onchange="updateButtonColor('reportEventModal')">
                            <label class="form-check-label" for="suspect_fraud">
                                Suspeita de fraude ou golpe
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="inappropriate_content"
                                value="inappropriate_content" onchange="updateButtonColor('reportEventModal')">
                            <label class="form-check-label" for="inappropriate_content">
                                Conteúdo inadequado ou ofensivo
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="incorrect_information"
                                value="incorrect_information" onchange="updateButtonColor('reportEventModal')">
                            <label class="form-check-label" for="incorrect_information">
                                Informações incorretas sobre o evento
                            </label>
                        </div>
                    </form>

                    <div class="modal-footer" style="border-top: none;">
                        <button type="button" data-dismiss="modal"
                            style="color: white; border-radius: 0.5em; padding: 0.5em;">Cancelar</button>
                        <button type="button" id="denunciarButton" onclick="reportEvent({{ $event->id }})"
                            style="color: #808080; border-radius: 0.5em; padding: 0.5em;" disabled>Denunciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="participantsModal" tabindex="-1" role="dialog"
        aria-labelledby="participantsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title">Participantes</h5>
                    <div class="row">
                        <div class="part-col">
                            @foreach ($event->participants as $participant)
                                <div class="part-row"
                                    onclick="window.location.href='{{ route('user', $participant->username) }}'">
                                    <div class="">
                                        <img class="profile-pic"
                                            src="{{ asset('assets/profile/' . $participant->photo) }}">
                                    </div>
                                    <div class="">
                                        <h2>{{ $participant->name }}</h2>
                                        <h3>{{ $participant->username }}</h3>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="inviteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title">Convidar</h5>
                    <div class="row">
                        <div class="part-col">
                            <div class="part-row align-items-center">
                                <form id="inviteForm" action="{{ route('event.inviteUser') }}" method="POST">
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    @csrf
                                    @method('POST')
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <div class="form-group">
                                                <input type="email" name="email" placeholder="Email"
                                                    id="inviteInput" required />
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button id="submit-button" type="submit">Convidar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
@endsection
