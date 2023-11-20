@extends('layouts.app')

@section('title', $organization->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organization.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
@endsection
