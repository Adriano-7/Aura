@extends('layouts.app')

@section('title', 'Meus Eventos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my-events.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <a href="{{ route('criar-evento') }}" class="btn btn-primary">Create Event</a>
    @foreach($events as $event)
        <div class="event">
            <h2>{{ $event->name }}</h2>
            <p>{{ $event->start_date }}</p>
            <!-- Add more event details here -->
        </div>
    @endforeach

@endsection
