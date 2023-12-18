@extends('layouts.app')

@section('title', $user->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

<?php
function adjustBrightness($hex, $steps) {
    $steps = max(-255, min(255, $steps));

    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color);
        $color   = max(0,min(255,$color + $steps));
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }
    return $return;
}

$color1 = adjustBrightness($userProfile->background_color, -120);
$color2 = adjustBrightness($userProfile->background_color, +50);
?>

@section('content')
    <section id="content">
        <section id="profile-header">
            <div id="background-banner" style="background-image: linear-gradient(to bottom right, {{$color1}}, {{$color2}})"></div>            
            <img src="{{ asset('assets/profile/' . $userProfile->photo) }}" id="profile-pic">
        </section>
        <section id="profile-fields">
            <div style="display: flex; flex-direction: row; align-items: center;">
                <span id="complete-name">{{$userProfile->name}}</span>
                @if($userProfile == $user)
                    <li class="nav-item dropdown options" style="list-style-type: none;">
                        <img class="three-dots" src="{{asset('assets/Three-Dots-Icon.svg')}}" alt="OPTIONS"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">

                        <ul class="dropdown-menu dropdown-menu-dark"
                            aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item">Editar</a></li>
                            <li><a class="dropdown-item">Apagar conta</a></li>
                        </ul>
                    </li>
                @endif
                @if($userProfile->is_admin)
                    <span class="badge" inert>Admin</span>
                @endif
            </div>
            <span id="username">{{$userProfile->username}}</span><span id="email">{{$userProfile->email}}</span>
        </section>
    </section>
@endsection


