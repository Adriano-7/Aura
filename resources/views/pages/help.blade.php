@extends('layouts.app')

@section('title', 'Ajuda')

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
                <h1 id="staticTitle">Ajuda</h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <p id="staticText">
                    <strong>1. Como posso criar um evento?</strong>
                    <br>
                    <br>
                    Para criar um evento, é preciso pertencer a uma organização. Após isso, basta ir à meus eventos e clicar em criar evento.
                    <br>
                    <br>
                    <strong>2. Como posso aderir a um evento?</strong>
                    <br>
                    <br>
                    Para aderir a um evento, basta ir à página do evento e clicar em aderir.
                    <br>
                    <br>
                    <strong>3. Que benefícios tenho ao criar um perfil?</strong>
                    <br>
                    <br>
                    Ao criar um perfil, pode criar eventos, aderir a eventos, comentar eventos, entre outras funcionalidades.
                    <br>
                    <br>
                    <strong>4. Se tiver alguma dúvida, como posso contactar a Aura?</strong>
                    <br>
                    <br>
                    Pode contactar a Aura através do email <a href="mailto:auraeventslbaw@gmail.com">auraeventslbaw@gmail.com</a>
                    <br>
                    <br>
                    <strong>5. Como posso apagar a minha conta?</strong>
                    <br>
                    <br>
                    Para apagar a sua conta, basta ir ao seu perfil e clicar em apagar conta.
                    <br>
                    <br>
                    <strong>6. Perdi a minha password, como posso recuperá-la?</strong>
                    <br>
                    <br>
                    Para recuperar a sua password, basta ir a este <a href="{{route('recoverPassword')}}">link</a>, inserir o seu email e seguir as instruções que lhe serão enviadas.
                    <br>
                    <br>
                </p>
            </div>
        </div>
    </div>
    
@endsection