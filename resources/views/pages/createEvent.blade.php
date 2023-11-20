@extends('layouts.app')

@section('title', 'Create Event')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/criar-evento.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
<html>
<body>

<div class="custom-container">
    <h2 class="text-center">Create an Event</h2>

    <form action="/submit_event" method="POST">

        <!-- Event Name -->
        <div class="form-group">
            <label for="event_name">Nome</label>
            <input type="text" id="event_name" name="event_name" required>
        </div>

        <!-- Date and Time -->
        <div class="form-group">
            <label for="event_date">Data do Evento</label>
            <input type="date" id="event_date" name="event_date" required>
        </input>

        <div class="form-group">
            <label for="event_time">Hora do Evento</label>
            <input type="time" id="event_time" name="event_time" required>
        </div>

        <!-- Location -->
        <div class="form-group">
            <label for="event_location">Localização</label>
            <input type="text" id="event_location" name="event_location" required>
        </div>

        <!-- Organizacao -->
        <div class="form-group">
            <label for="organization">Organização</label>
            <select id="organization" name="organization" required>
                @foreach($organizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Visibility -->
        <div class="form-group">
            <label for="event_visibility">Visibilidade</label>
            <select id="event_visibility" name="event_visibility" required>
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="event_description">Descrição</label>
            <textarea id="event_description" name="event_description" rows="4" required></textarea>
        </div>

        

        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Create Event</button>
            <button type="button" class="btn btn-secondary ml-4" style="margin-left: 4em">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>
@endsection
