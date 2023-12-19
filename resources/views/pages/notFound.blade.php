@extends('layouts.app')

@section('title', '404')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/not-found.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <h1>{{$text}}<h1>
    <img src="{{asset('assets/404.svg')}}">
@endsection