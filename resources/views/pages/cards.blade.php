@extends('layouts.app')

@section('title', 'Cards')

@section('header')
    <h1><a href="{{ url('/cards') }}">Thingy!</a></h1>
    @if (Auth::check())
        <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
    @endif
@endsection

@section('content')

<section id="cards">
    @each('partials.card', $cards, 'card')
    <article class="card">
        <form class="new_card">
            <input type="text" name="name" placeholder="new card">
        </form>
    </article>
</section>

@endsection