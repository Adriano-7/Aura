@extends('layouts.app')

@section('title', 'Home')

@section('header')
    <h1><a href="{{ url('/home') }}">Aura</a></h1>
    @if (Auth::check())
        <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
    @endif
@endsection

@section('content')

<h1> WELCOME </h1>

@endsection