@extends('layouts.app')

@section('title', $user->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <section id="content">
        <section id="profile-header">
            <div id="background-banner" style="background-color: {{ $user->background_color }}"></div>            
            <img src="{{ asset('assets/profile/' . $userProfile->photo) }}" id="profile-pic">
        </section>
        <section id="profile-fields">
            <span id="complete-name">{{$userProfile->name}}</span>
            @if($userProfile->is_admin)
                <span class="badge">Admin</span>
            @endif<br>
            <span id="username">{{$userProfile->username}}</span><span id="email">{{$userProfile->email}}</span>
        </section>
    </section>
@endsection


