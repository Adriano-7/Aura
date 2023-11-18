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
    @if (!Auth::check())
        @include('widgets.welcomeBanner')
    @endif

    @include('widgets.eventRow', ['events' => $events])
    @include('widgets.organizationRow', ['organizations' => $organizations])

@endsection
