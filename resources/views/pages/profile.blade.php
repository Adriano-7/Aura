@extends('layouts.app')

@section('title', $userProfile->name)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/profile.js') }}" defer></script>
@endsection

@section('header')
    @include('widgets.navBar')
@endsection

@section('content')
    <section id="profile-header">
        <div id="background-banner" style="background-image: linear-gradient(to bottom right, {{$color1}}, {{$color2}})"></div>            
        <img src="{{ asset('assets/profile/' . $userProfile->photo) }}" id="profile-pic">
    </section>
    <section id="profile-fields">
        <div style="display: flex; flex-direction: row; align-items: center;">
            <span id="complete-name">{{$userProfile->name}}</span>
            @if(Auth::check() && $userProfile->id == $user->id)
                <li class="nav-item dropdown options" style="list-style-type: none;">
                    <img class="three-dots" src="{{asset('assets/Three-Dots-Icon.svg')}}" alt="OPTIONS"
                        data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" style="margin-left: 0.5em">

                    <ul class="dropdown-menu dropdown-menu-dark"
                        aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="dropdown-item" data-toggle="modal" data-target="#editModal">Editar perfil</a></li>                        
                        <li><a class="dropdown-item" onclick="deleteAccount({{$user->id}})">Eliminar conta</a></li>
                    </ul>
                </li>

                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h5 class="modal-title" id="editModalLabel">Editar Perfil</h5>
                                <form id="editProfileForm" method="POST" action="{{route('user.update', $user->id)}}" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                    <input id="nameInput" type="text" name="name" class="form-control" placeholder="Nome" value="{{$user->name}}">
                                    <input id="usernameInput" type="text" name="username" class="form-control" placeholder="Nome de utilizador" value="{{$user->username}}">
                                    <input id="emailInput" type="email" name="email" class="form-control" placeholder="Email" value="{{$user->email}}">
                                    <div id="color-input">
                                        <input type="color" name="background_color" class="form-control form-control-color" id="backgroundColorInput" value="{{$user->background_color}}">
                                        <label for="backgroundColorInput" class="form-label">Cor favorita</label>
                                    </div>
                                    <div id="photo-input">
                                        <img src="{{ asset('assets/profile/' . $user->photo) }}" id="profile-pic-preview">
                                        <input type="file" name="photo" class="form-control" id="photoInput" accept="image/png, image/jpeg, image/jpg"> 
                                    </div>
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-footer" style="border-top: none;">
                                        <button type="button" data-dismiss="modal" onclick="cancelEdit()"
                                            style="color: white; border-radius: 0.5em; padding: 0.5em;">Cancelar</button>
                                        <button type="submit" id="save-button"
                                            style="color: white; border-radius: 0.5em; padding: 0.5em;">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
            @if($userProfile->is_admin)
                <span class="badge" inert>Admin</span>
            @endif
        </div>
        <span id="username">{{$userProfile->username}}</span><span id="email">{{$userProfile->email}}</span>
    </section>
    <section id="organizations-events">
        @if($organizations->count() > 0)
            <div class="section-card">
                @include('widgets.organizationRow', ['organizations' => $organizations, 'text' => 'Organizações  • ' . $organizations->count()])</div>
        @endif
        @if($events->count() > 0)
            <div class="section-card">
                @include('widgets.eventRow', ['events' => $events, 'text' => 'Eventos em que participa • ' . $events->count()])
            </div>
        @endif
    </section>
@endsection
