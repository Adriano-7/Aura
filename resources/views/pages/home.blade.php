@extends('layouts.app')

@section('title', 'Aura')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @if ($errors->has('token'))
        @include('widgets.popUpNotification', ['message' => 'Ocorreu um erro ao recuperar a password: ' . $errors->first('token')])
    @endif

    @if (session('success'))
        @include('widgets.popUpNotification', ['message' => session('success')])
    @endif

    @if (!Auth::check())
        @include('widgets.welcomeBanner')
    @else
        @include('widgets.greetingsBanner')
    @endif

    @include('widgets.eventRow', ['events' => $events])
    @include('widgets.organizationRow', ['organizations' => $organizations])
@endsection
