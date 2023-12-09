@extends('layouts.app')

@section('title', 'Notifications')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
