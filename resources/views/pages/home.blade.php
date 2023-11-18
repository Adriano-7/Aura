@extends('layouts.app')

@section('title', 'Aura')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
    @yield('navBar')
@endsection

@section('content')
    @include('widgets.eventRow', ['events' => $events])
    @yield('eventRow')
@endsection
