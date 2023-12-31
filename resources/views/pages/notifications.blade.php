@extends('layouts.app')

@section('title', 'Notificações' . ($user->notifications->count() > 0 ? ' (' . $user->notifications->count() . ')' : ''))

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/notifications.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @include('widgets.notifications', ['notifications' => $user->notifications])

    @if (session('status'))
        @include('widgets.popUpNotification', ['message' => session('status')])
    @endif
    
@endsection
