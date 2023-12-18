@extends('layouts.app')

@section('title', $event->name . ' • ' . $event->venue)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/event.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @if (session('status'))
        @include('widgets.popUpNotification', ['message' => session('status')])
    @endif

    @php
        $start_date = \Carbon\Carbon::parse($event->start_date);
        $moreThan24Hours = false;
        if ($event->end_date) {
            $end_date = \Carbon\Carbon::parse($event->end_date);
            $moreThan24Hours = $start_date->diffInHours($end_date) > 24;
        }

        $comments = \App\Models\Comment::event_comments($event->id);
    @endphp

    <section id="event-header">
        <img src="{{ asset('assets/eventos/' . $event->photo) }}">
        <h1>{{ $event->name }}</h1>
    </section>

    <div id="event-fields">
        <section id="details" class="event-field">
            <h2>Detalhes</h2>
            <div class="card">
                <div id="details-card-content">
                    <div id="first-column">
                        <span id="date">{{ $event->start_date->format('d M Y') }}</span>
                        @if ($moreThan24Hours)
                            <br>
                            <span id="date">{{ $event->end_date->format('d M Y') }}</span>
                        @endif
                    </div>
                    <div id="second-column">
                        <div id="weekday-and-time">
                            <span id="weekday">{{ \Carbon\Carbon::parse($event->start_date)->formatLocalized('%a') }}</span>
                            <span id="time">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                            @if ($event->end_date)
                                <span id="time"> - </span>
                                @if ($moreThan24Hours)
                                    <span
                                        id="weekday">{{ \Carbon\Carbon::parse($event->end_date)->formatLocalized('%a') }}</span>
                                @endif
                                <span id="time">{{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}</span>
                            @endif
                        </div>
                        <span id="venue">{{ $event->venue }}</span>
                        <span id="city">{{ $event->city }}</span>
                    </div>
                    <div id="third-column">
                        <span id="numParticipants"> {{ $event->participants->count() }} participantes</span>
                        @if (Auth::check() && !Auth::user()->is_admin)
                            @if ($user->participatesInEvent($event))
                                <form method="POST" action="{{ route('event.leave', $event->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button id="leave-event" type="submit">Sair do evento</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('event.join', $event->id) }}">
                                    @csrf
                                    <button id="join-event" type="submit">Aderir ao evento</button>
                                </form>
                            @endif
                            <div id="span-container">
                                <button type="button" id="show-participants" class="btn" data-toggle="modal"
                                    data-target="#participantsModal">Ver participantes </button>

                                <div class="modal fade" id="participantsModal" tabindex="-1" role="dialog"
                                    aria-labelledby="participantsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                @foreach ($event->participants as $participant)
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <img
                                                                src="{{ asset('assets/profile/' . $participant->photo) }}">
                                                        </div>
                                                        <div class="col-10">
                                                            <h1>{{ $participant->name }}</h1>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn" data-toggle="modal"
                                    data-target="#inviteModal">Convidar</button>

                                <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog"
                                    aria-labelledby="inviteModal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <form id="inviteForm" action="{{ route('event.inviteUser') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <input type="email" class="form-control" id="email"
                                                                    name="email" placeholder="Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="d-flex justify-content-center">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <section id="about" class="event-field">
            <h2>Sobre</h2>
            <div class="card">
                <p id="about-text">{{$event->description}}</p>
            </div>
        </section>
        <section id="comments" class="event-field">
                <h2>Comentários ({{$comments->count()}})</h2>
            </div>
            <div class="card" id="comments-card">
                @if(Auth::check() && !Auth::user()->is_admin && Auth::user()->participatesInEvent($event))
                    <div id="add-comment-row" class="comment-row">
                        <img class="profile-pic" src="{{asset('assets/profile/' . $user->photo)}}">
                        <form id="add-comment-form" method="POST" action="{{route('comment.add')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="text" placeholder="Adicione um comentário" autocomplete="off" required>
                            <input type="hidden" name="event_id" value="{{$event->id}}">
                            <label for="file-upload" class="icon-button">
                                <img class="icon" src="{{asset('assets/clip-icon.svg')}}">
                                <input id="file-upload" type="file" name="file" style="display:none;">
                            </label>
                            <button type="submit" class="icon-button insert-comment">
                                <img class="icon" src="{{asset('assets/send-icon.svg')}}">
                            </button>
                        </form>
                    </div>
                @endif

                @foreach($comments as $comment)
                    <div class="comment-row" id="{{'comment-' . $comment->id}}">
                        <img class="profile-pic" src="{{asset('assets/profile/' . $comment->author->photo)}}">
                        <div class="comment-content">
                            <div class="username-and-date">
                                <span class="comment-author">{{$comment->author->name}}</span>
                                <span class="comment-date">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}</span>
                                @if(Auth::check())
                                    @if(Auth::user()->id == $comment->user_id)
                                        <button class="icon-button edit-comment-btn" id="{{'editButton-' . $comment->id}}">
                                            <img class="icon" src="{{asset('assets/edit-icon.svg')}}">
                                        </button>
                                    @endif
                                    @if(Auth::user()->id == $comment->author->id || Auth::user()->is_admin)
                                        <button class="icon-button delete-comment-btn">
                                            <img class="icon" src="{{asset('assets/delete-icon.svg')}}">
                                        </button>
                                    @endif
                                @endif
                            </div>
                            <p class="comment-text">{{$comment->text}}</p>
                            @if($comment->file_id)
                                <div class="comment-file">
                                    <a href="{{ asset('assets/uploads/' . $comment->file->file_name) }}">
                                        <img src="{{ asset('assets/uploads/' . $comment->file->file_name) }}" style="max-height: 15em;">
                                    </a>   
                                </div>
                            @endif
                            <div class="votes-row">
                                @if(Auth::check() && !Auth::user()->is_admin && Auth::user()->id != $comment->author->id && Auth::user()->participatesInEvent($event))
                                    @if($comment->userVote(Auth::user()->id) == 0)
                                        <div class="up-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                            </svg>
                                        </div>
                                        <div class="down-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-down-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    @if($comment->userVote(Auth::user()->id) == 1)
                                        <div class="up-btn" selected>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle-fill" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                                <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                            </svg>
                                        </div>
                                        <div class="down-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-down-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    @if($comment->userVote(Auth::user()->id) == -1)
                                        <div class="up-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                            </svg>
                                        </div>
                                        <div class="down-btn" selected>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                            </svg>
                                        </div>
                                    @endif
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-chevron-expand" viewBox="0 0 16 16" style="margin-right:0.5em">
                                        <path fill-rule="evenodd" d="M3.646 9.146a.5.5 0 0 1 .708 0L8 12.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zm0-2.292a.5.5 0 0 0 .708 0L8 3.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708z"/>
                                    </svg>
                                @endif
                                <span class="comment-votes" inert>{{$comment->vote_balance}}</span>
                            </div>
                        </div>
                    </div>               
                @endforeach
            </div>
        </section>
    </div>        
@endsection
