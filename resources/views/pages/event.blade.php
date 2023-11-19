@extends('layouts.app')

@section('title', $event->name . ' • ' . $event->venue)
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
@endsection
