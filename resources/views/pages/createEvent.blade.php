@extends('layouts.app')

@section('title', 'Create Event')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/criar-evento.css') }}">
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/criar-evento.js') }}" defer></script>

@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
<html>
<body>

<div class="custom-container">
    <h2 class="text-center">Criar um Evento</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action='{{route("submit-event")}}' method="POST" enctype="multipart/form-data">
    @csrf 
        <!-- Event Name -->
        <div class="form-group">
            <label for="event_name">Nome *</label>
            <input type="text" id="event_name" name="event_name" required>
        </div>

        <!-- Date and Time -->
        <div class="form-row">
                <div class="form-group col-md-5" style='margin-right:2em'>
                    <label for="start_date">Data Início *</label>
                    <input type="date" id="start_date" name="start_date"
                        min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group col-md-5">
                    <label for="end_date">Data Fim *</label>
                    <input type="date" id="end_date" name="end_date"
                        min="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-5" style='margin-right:2em'>
                    <label for="start_time">Hora Início *</label>
                    <input type="time" id="start_time" name="start_time"
                        required>
                </div>
                <div class="form-group col-md-5">
                    <label for="end_time">Hora Fim *</label>
                    <input type="time" id="end_time" name="end_time"
                        required>
                </div>
            </div>

        <!-- Morada -->
        <div class="form-group">
            <label for="event_address">Morada</label>
            <input type="text" id="event_address" name="event_address">
        </div>

        <!-- Local -->            

        <div class="form-row">
            <div class="form-group col-md-5" style = 'margin-right:2em'>
                <label for="event_venue">Local *</label>
                <input type="text" id="event_venue" name="event_venue" required>
            </div>
            <div class="form-group col-md-5">
                <label for="event_city">Cidade *</label>
                <input type="text" id="event_city" name="event_city" >
            </div>
        </div>


        <!-- Organizacao -->
        <div class="form-group">
            <label for="organization">Organização *</label>
            <select id="organization" name="organization" required>
                @foreach($organizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Visibility -->
        <div class="form-group">
            <label for="event_visibility">Visibilidade *</label>
            <select id="event_visibility" name="event_visibility" required>
                <option value="public">Público</option>
                <option value="private">Privado</option>
            </select>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="event_description">Descrição *</label>
            <textarea id="event_description" name="event_description" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="event_picture" id="file-upload-button" class="btn btn-primary">Carregar Imagem do Evento</label>
            <input type="file" id="event_picture" name="event_picture" accept="image/*" class="form-control-file" style="display: none;">
            <span id="file-name">Nenhuma imagem carregada</span>
        </div>
        

        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary me-4">Criar Evento</button>
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('my-events') }}'">Cancelar</button>        </div>
    </form>
</div>
</body>
</html>
@endsection
