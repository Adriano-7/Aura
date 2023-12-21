@extends('layouts.app')

@section('title', 'Pesquisa')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/search.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection
    

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <div class="container" id="search-container">
    </div>

    <div id="bottom-search-bar">
        <div class="container">
            <form id="search-form" onsubmit="event.preventDefault(); search();">
                <div class="input-group">
                    <input type="date" class="form-control" id="dateFilter">

                    <select class="form-control" id="tagFilter">
                        <option value="" selected> Tag</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>

                    <input type="text" class="form-control" id="text-input"
                        placeholder="Pesquisa por artistas ou eventos">

                    <div class="input-group-append">
                        <button class="btn" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
