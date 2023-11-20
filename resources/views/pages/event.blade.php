@extends('layouts.app')

@section('title', $event->name . ' • ' . $event->venue)
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @php
        $start_date = \Carbon\Carbon::parse($event->start_date);
        $moreThan24Hours = false;
        if ($event->end_date) {
            $end_date = \Carbon\Carbon::parse($event->end_date);
            $moreThan24Hours = $start_date->diffInHours($end_date) > 24;
        }
    @endphp

    <section id="event-header">
        <img src="{{ asset('storage/eventos/' . $event->photo) }}">
        <h1>{{$event->name}}</h1>
    </section>

    <div id="event-fields">
        <section id="details" class="event-field">
            <h2>Detalhes</h2>
            <div class="card">
                <div id="details-card-content">
                    <div id="first-column">
                        <span id="date">{{ $event->start_date->format('d M Y') }}</span>
                        @if($moreThan24Hours)
                            <br>
                            <span id="date">{{ $event->end_date->format('d M Y') }}</span>´
                        @endif
                    </div>
                    <div id="second-column">
                        <div id="weekday-and-time">
                            <span id="weekday">{{ \Carbon\Carbon::parse($event->start_date)->formatLocalized('%a') }}</span>
                            <span id="time">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                            @if($event->end_date)
                                <span id="time"> - </span>
                                @if($moreThan24Hours)
                                    <span id="weekday">{{ \Carbon\Carbon::parse($event->end_date)->formatLocalized('%a') }}</span>
                                @endif
                                <span id="time">{{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}</span>
                            @endif
                        </div>
                        <span id="venue">{{$event->venue}}</span>
                        <span id="city">{{$event->city}}</span>
                    </div>
                    <div id="third-column">
                        <span id="numParticipants"> {{$event->getParticipants()->count()}} participantes</span>
                        @if(Auth::check() && !Auth::user()->isAdmin())
                            @if($user->participatesInEvent($event))
                                <button id="leave-event">Sair do evento</button>
                            @else
                                <button id="join-event">Aderir ao evento</button>
                            @endif
                            <div id="span-container">
                                <span id="show-participants">Ver participantes</span>
                                <span id="invite">Convidar</span>
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
            <h2>Comentários</h2>
            <div class="card">
                @if(Auth::check() && !Auth::user()->isAdmin())
                    <div id="add-comment-row" class="comment-row">
                        <img class="profile-pic" src="{{asset('storage/profile/' . $user->photo)}}">
                        <input type="text" placeholder="Adicione um comentário">
                        <img class="icon" src="{{asset('storage/clip-icon.svg')}}">
                        <img class="icon" src="{{asset('storage/send-icon.svg')}}">
                    </div>
                @endif
            </div>
        </section>
    </div>        
@endsection
