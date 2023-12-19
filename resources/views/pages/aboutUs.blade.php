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
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        Dolor magna eget est lorem ipsum. In massa tempor nec feugiat nisl pretium.
                        Lacus vestibulum sed arcu non odio. Ipsum dolor sit amet consectetur adipiscing.
                        Turpis cursus in hac habitasse platea dictumst quisque sagittis purus.
                        Odio euismod lacinia at quis risus. Tortor consequat id porta nibh venenatis.
                        Consequat id porta nibh venenatis cras sed felis. Eget duis at tellus at urna condimentum.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
