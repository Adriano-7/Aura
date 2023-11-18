@extends('layouts.app')

@section('title', 'Evento • ' . $event->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
@endsection
