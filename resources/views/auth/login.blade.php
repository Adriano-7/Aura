@extends('layouts.app')

@section('title', 'Log In')


@section('styles')
<link rel="stylesheet" href="{{ asset('css/login_register.css') }}">
@endsection

@section('content')
<section class="text-center text-lg-start">
  <section class="vh-100">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 text-black">
          <div class="px-5 ms-xl-4">
            <a href="/home"><img src="{{asset('storage/AuraLogo.svg')}}" alt="Logo" style="width: 7rem;" class="pt-5 mt-xl-4"></a>
          </div>

          <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-xl-n5">
            <form method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required/>
                @if ($errors->has('email'))
                  <span class="error">
                  {{ $errors->first('email') }}
                  </span>
                @endif

                <input type="password" name="password"  placeholder="Palavra passe" required/>
                @if ($errors->has('password'))
                  <span class="error">
                      {{ $errors->first('password') }}
                  </span>
                @endif

                <button id="submit-button" type="submit">Iniciar sessão</button>

              <p>Ainda não tem conta? <a href="/register" id="registo-mensagem" >Registe-se!</a></p>
          
              </form>

          </div>

        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="{{asset('storage/LoginBanner.jpg')}}"
            alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
        </div>
      </div>
    </div>
  </section>
</section>
@endsection