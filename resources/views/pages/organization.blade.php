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
    <div class="container position-relative d-flex align-items-end w-100">
        <img src="{{ asset('storage/organizations/' . $organization->photo) }}" id="bandBanner" class="d-block w-100">
        <h1 id="bandName" class="position-absolute text-white">{{ $organization->name }}</h1>
    </div>
    <script>
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            var banner = document.getElementById('bandBanner');
            var navbar = document.getElementById('orgNav');
            if (window.pageYOffset > banner.offsetHeight) {
                navbar.classList.add("fixed-top");
            } else {
                navbar.classList.remove("fixed-top");
            }
        }
    </script>

    <nav id="orgNav" class="navbar">
        <div class="container">
            <div id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#section1">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#section2">Sobre</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" id="section1">
        <div class="row">
            <div class="col-12">
                <h1 id="results-title">Eventos • {{ $organization->events->count() }} Resultados</h1>
            </div>
        </div>

        @if ($organization->events->count() == 0)
        <div class="card mx-auto">
            <div class="row ">
                <div class="col-md-12">
                    <p>Não há eventos planeados no momento.</p>
                </div>
            </div>
        </div>
        @else
            <div class="card mx-auto">
                @foreach ($organization->events as $event)
                    @php
                        $start_date = \Carbon\Carbon::parse($event->start_date);
                        $moreThan24Hours = false;
                        if ($event->end_date) {
                            $end_date = \Carbon\Carbon::parse($event->end_date);
                            $moreThan24Hours = $start_date->diffInHours($end_date) > 24;
                        }
                    @endphp
                    <div class="row event">
                        <div class="col-md-3">
                            <h2>{{ $start_date->format('d M') }}</h2>
                            <h2>{{ $start_date->format('Y') }}</h2>
                        </div>
                        <div class="col-md-7" onclick="window.location.href = '/evento/{{ $event->id }}'"
                            style="cursor: pointer;">
                            <h3>{{ $start_date->formatLocalized('%a') }} • {{ $start_date->format('H:i') }}</h3>
                            <h2>{{ $event->venue }}</h2>
                            <h3>{{ $event->city }}</h3>
                        </div>
                        <div class="col-md-2 ml-auto">
                            <button type="button" id="join-event">Aderir ao Evento</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
    <div class="container" id="section2">
        <div class="row">
            <div class="col-12">
                <h1 id="results-title">Sobre</h1>
            </div>
        </div>

        <div class="card mx-auto">
            <div class="row ">
                <div class="col-md-12">
                    <p>{{ $organization->description }}</p>
                </div>
            </div>
        </div>
    </div>
    </main>
@endsection
