@extends('layouts.app')

@section('title', 'Sobre Nós')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/static.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <div class="container" id="staticCont">
        <div class="row">
            <div class="col-12">
                <h1 id="staticTitle">Sobre Nós</h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p id="staticText"><strong>Aura</strong> é um projeto desenvolvido no âmbito da unidade curricular de Laboratório de Bases de Dados e Aplicações Web da licenciatura de Engenharia Informática da Universidade do Porto. O objetivo deste projeto é desenvolver uma plataforma que permita a gestão de eventos, onde os utilizadores podem criar eventos, subscrever-se a eventos, comentar eventos, entre outras funcionalidades.</p>
            </div>
        </div>
    </div>
    
@endsection
