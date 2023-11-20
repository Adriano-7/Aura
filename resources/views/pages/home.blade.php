@extends('layouts.app')

@section('title', 'Aura')

@section('styles')
    <!-- Remove after -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    @if (!Auth::check())
        @include('widgets.welcomeBanner')
    @else
        @include('widgets.greetingsBanner')
    @endif

    @include('widgets.eventRow', ['events' => $events])
    @include('widgets.organizationRow', ['organizations' => $organizations])

@endsection

<!-- TODO: remove this -->
<div>
    <h1 style="color: white">Comentários</h1>
    <p style="color: white"> Comentários do evento 2 </p>
    @foreach ($comments as $comment)
        <div class="to-delete">
            <p style="color: white"> {{ $comment->date }} </p>
            <p style="color: white"> {{ $comment->text }} </p>
            <button type="button" class="delete-button btn btn-danger" data-id="{{ $comment->id }}" }}">Apagar</button>
        </div>
    @endforeach
</div>
