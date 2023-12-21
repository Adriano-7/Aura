@extends('layouts.app')

@section('title', 'Contactos')

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
                <h1 id="staticTitle">Contactos</h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p id="staticText">
                    Para qualquer questão ou sugestão, por favor contacte-nos através do email <a href="mailto:auraeventslbaw@gmail.com">auraeventslbaw@gmail.com</a>.
                </p>
            </div>
        </div>
    </div>
    
@endsection