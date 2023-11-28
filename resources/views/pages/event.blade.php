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
        <img src="{{ asset('storage/eventos/' . $event->photo) }}">
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
                        @if (Auth::check() && !Auth::user()->isAdmin())
                            @if ($user->participatesInEvent($event))
                                <button id="leave-event" onclick="leaveEvent(<?php echo json_encode($event->id); ?>)">Sair do evento</button>
                            @else
                                <button id="join-event" onclick="joinEvent(<?php echo json_encode($event->id); ?>)">Aderir ao evento</button>
                            @endif
                            <div id="span-container">
                                <button type="button" id="show-participants" class="btn" data-toggle="modal"
                                    data-target="#participantsModal">Ver participantes</button>

                                <div class="modal fade" id="participantsModal" tabindex="-1" role="dialog"
                                    aria-labelledby="participantsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                @foreach ($event->participants as $participant)
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <img
                                                                src="{{ asset('storage/profile/' . $participant->photo) }}">
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
            <div class="card">
                @if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->participatesInEvent($event))
                    <div id="add-comment-row" class="comment-row">
                        <img class="profile-pic" src="{{asset('storage/profile/' . $user->photo)}}">
                        <form id="add-comment" method="POST" action="{{route('comment.add')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="text" placeholder="Adicione um comentário">
                            <input type="hidden" name="event_id" value="{{$event->id}}">
                            <label for="file-upload" class="icon-button">
                                <img class="icon" src="{{asset('storage/clip-icon.svg')}}">
                                <input id="file-upload" type="file" name="file" style="display:none;">
                            </label>
                            <button type="submit" class="icon-button">
                                <img class="icon" src="{{asset('storage/send-icon.svg')}}">
                            </button>
                        </form>
                    </div>
                @endif

                @foreach($comments as $comment)
                    <div class="comment-row">
                        <img class="profile-pic" src="{{asset('storage/profile/' . $comment->author->photo)}}">
                        <div class="comment-content">
                            <div class="username-and-date">
                                <span class="comment-author">{{$comment->author->name}}</span>
                                <span class="comment-date">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}</span>
                                @if(Auth::check())
                                <!--
                                    @if(Auth::user()->id == $comment->author_id)
                                        <button class="icon-button">
                                            <img class="icon" src="{{asset('storage/edit-icon.svg')}}">
                                        </button>
                                    @endif
                                -->
                                    
                                    @if(Auth::user()->id == $comment->author->id || Auth::user()->isAdmin())
                                        <form action="{{ route('comment.delete', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-button">
                                                <img class="icon" src="{{asset('storage/delete-icon.svg')}}">
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                            <p class="comment-text">{{$comment->text}}</p>
                            @if($comment->file_id)
                                <div class="comment-file">
                                    <a href="{{ asset('storage/uploads/' . $comment->file->file_name) }}">
                                        <img src="{{ asset('storage/uploads/' . $comment->file->file_name) }}" style="max-height: 15em;">
                                    </a>   
                                </div>
                            @endif
                            <div class="votes-row">
                            @if(Auth::check() && $comment->vote(Auth::user()->id) == 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                </svg>  
                            @endif
                            @if(Auth::check() && $comment->vote(Auth::user()->id) == 1)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                </svg>
                            @endif
                                <span class="comment-votes">{{$comment->vote_balance}}</span>
                            </div>
                        </div>
                    </div>               
                @endforeach
                
            </div>
        </section>
    </div>        
@endsection
