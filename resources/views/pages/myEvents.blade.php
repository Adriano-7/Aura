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

@include('widgets.eventTable', ['events' => $events])
@endsection
