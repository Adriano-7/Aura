@extends('layouts.app')

@section('title', 'Notifications')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 id="results-title">Eventos • 2 Resultados</h1>
            </div>
        </div>

        <div class="card">
            <div class="row search-result">
                <div class="col-md-2">
                    <h2>30 Abril</h2>
                    <h2>2023</h2>
                </div>
                <div class="col-md-8">
                    <h3>Seg • 20:30</h3>
                    <h2>Altice Arena</h2>
                    <h3>Lisboa</h3>
                </div>
                <div class="col-md-2 ml-auto">
                    <button type="button" id="join-event">Aderir ao Evento</button>
                </div>
            </div>
            <div class="row search-result">
                <div class="col-md-2">
                    <h2>30 Abril</h2>
                    <h2>2023</h2>
                </div>
                <div class="col-md-8">
                    <h3>Seg • 20:30</h3>
                    <h2>Altice Arena</h2>
                    <h3>Lisboa</h3>
                </div>
                <div class="col-md-2 ml-auto">
                    <button type="button" id="join-event">Aderir ao Evento</button>
                </div>
            </div>
        </div>
    </div>

    <div id="bottom-search-bar">
        <div class="container">
            <form id="search-form">
                <div class="input-group">
                    <input type="date" class="form-control" id="dateFilter">

                    <select class="form-control" id="tagFilter">
                        <option value="" selected disabled> Tag</option>
                        <option value="tag1">Tag 1</option>
                        <option value="tag2">Tag 2</option>
                    </select>

                    <input type="text" class="form-control" id="text-input"
                        placeholder="Pesquisa por artistas ou eventos">

                    <div class="input-group-append">
                        <button class="btn" type="button">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
