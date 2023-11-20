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
@section('content')
    <a href="{{ route('criar-evento') }}" class="btn btn-primary">Create Event</a>
@endsection
@endsection
