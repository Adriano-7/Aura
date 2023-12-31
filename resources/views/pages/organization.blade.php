@extends('layouts.app')

@section('title', $organization->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organization.css') }}">
    <link rel="stylesheet" href="{{ asset('css/org-eventos.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/organization.js') }}" defer></script>
    <script src="{{ asset('js/orgNav.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @if (session('status'))
        @include('widgets.popUpNotification', ['message' => session('status')])
    @endif

    @include('widgets.org-eventos.banner', ['photo' => $organization->photo, 'name' => $organization->name])

    @if (!$organization->approved)
        @include('widgets.org-eventos.approveWarning')
    @endif

    @if (Auth::check() && (Auth::user()->is_admin || $organization->organizers->contains(Auth::user()->id)))
        @include('widgets.org-eventos.pageNav', ['elements' => ['Membros', 'Eventos', 'Sobre']], ['href' => ['#membros', '#eventos',  '#sobre']])
        @include('widgets.org-eventos.manageOrg')
    @else
        @include('widgets.org-eventos.pageNav', ['elements' => ['Eventos', 'Sobre']], ['href' => ['#eventos',  '#sobre']])
    @endif

    @include('widgets.org-eventos.eventsTable', ['title' => 'Eventos • ' . $events->count() . ' Resultados', 'events' => $events, 'isOrg' => true])
    @include('widgets.org-eventos.textSection', ['id' => 'sobre', 'title' => 'Sobre', 'text' => $organization->description])

@endsection
