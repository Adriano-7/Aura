@extends('layouts.app')

@section('title', 'Meus Eventos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my-events.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/my-events.js') }}"></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <div class="container-fluid px-sm-0  mt-5">
        <div class="row justify-content-center">
            <div class="col-10 col-sm-10 col-md-8 col-lg-8 col-xl-7">
                <div class="row options">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        @if ($user->organizations->count() > 0)
                            <div class="title" id="title-text">Eventos que Organizo </div>
                        @else
                            <div class="title" id="title-text">Eventos em que Participo </div>
                        @endif
                    </div>

                    <div class="col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end">
                        @if (auth()->user()->organizations->count() > 0)
                            <a href="{{ route('criar-evento') }}" class="btn btn-primary mb-3 me-4 ">Criar Evento</a>
                        @endif

                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-expanded="false">
                                Organizo
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" data-value="organizo">Organizo</a></li>
                                <li><a class="dropdown-item" data-value="participo">Participo</a></li>
                            </ul>
                        </div>
                    </div>

                    @if ($user->organizations->count() > 0)
                        @include('widgets.myEventsCards', ['events' => $orgEvents, 'type' => 'organize'])
                    @endif

                    @include('widgets.myEventsCards', ['events' => $partEvents, 'type' => 'participate'])
                </div>
            </div>
        </div>
    </div>

@endsection
