@extends('layouts.app')

@section('title', $user->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/profile.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <section id="profile-header">
        <div id="background-banner" style="background-image: linear-gradient(to bottom right, {{$color1}}, {{$color2}})"></div>            
        <img src="{{ asset('assets/profile/' . $userProfile->photo) }}" id="profile-pic">
    </section>
    <section id="profile-fields">
        <div style="display: flex; flex-direction: row; align-items: center;">
            <span id="complete-name">{{$userProfile->name}}</span>
            @if($userProfile->id == $user->id)
                <li class="nav-item dropdown options" style="list-style-type: none;">
                    <img class="three-dots" src="{{asset('assets/Three-Dots-Icon.svg')}}" alt="OPTIONS"
                        data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" style="margin-left: 0.5em">

                    <ul class="dropdown-menu dropdown-menu-dark"
                        aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="dropdown-item">Editar perfil</a></li>                        
                        <li><a class="dropdown-item" onclick="deleteAccount({{$user->id}})">Eliminar conta</a></li>
                    </ul>
                </li>
            @endif
            @if($userProfile->is_admin)
                <span class="badge" inert>Admin</span>
            @endif
        </div>
        <span id="username">{{$userProfile->username}}</span><span id="email">{{$userProfile->email}}</span>
    </section>
    <section id="organizations-events">
        @if($organizations->count() > 0)
            <div class="section-card">
                @include('widgets.organizationRow', ['organizations' => $organizations, 'text' => 'Organizações  • ' . $organizations->count()])</div>
        @endif
        @if($events->count() > 0)
            <div class="section-card">
                @include('widgets.eventRow', ['events' => $events, 'text' => 'Eventos em que participa • ' . $events->count()])
            </div>
        @endif
    </section>
    @include('widgets.footer')
@endsection
