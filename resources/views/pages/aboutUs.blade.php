@extends('layouts.app')

@section('title', 'Sobre Nós')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about-us.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

<style>
    body {
        height: 75vh;
    }
</style>

@section('content')
    <div class="container">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="d-flex flex-column align-items-center justify-content-center w-100">
                <h1 class="text-center">Sobre Nós</h1>
                <div class="card p-3 mt-3 w-75">
                    <p class="text-center">
                        A Aura é uma plataforma de gestão de eventos que torna fácil
                        conectar pessoas e organizar eventos de qualquer tamanho.
                        Oferece uma variedade de recursos para ajudar os usuários
                        a organizar eventos bem-sucedidos, incluindo ferramentas para
                        criar e gerenciar listas de convidados, enviar convites, entre outros.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
