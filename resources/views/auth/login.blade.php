@extends('layouts.app')

@section('header')
@endsection

@section('content')


<!-- Section: Design Block -->
<section class="text-center text-lg-start">
  <style>
    .bg-image-vertical {
    position: relative;
    overflow: hidden;
    background-repeat: no-repeat;
    background-position: right center;
    background-size: auto 100%;
    }

    .h-custom-2 {
    height: 80%;
    }
    

    #content{
    background-color: black;
    height: 100%;
    overflow: hidden;
    }

    #submit-button{
        margin-top: 10%;
        border-radius: 10px;
        border: 1px solid #50457D;
        color: #50457D;
        background-color: black;
        width: 100%;
        padding: 10px;
    }

    input{
        border: none;
        border-bottom: 1px solid #808080;
        color: #808080;
        background-color: black;
        width: 100%;
        margin-bottom: 8%;
    }

    input:focus{
        outline: none;
        border-bottom: 1px solid #50457D;
        color: #808080;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active  {
        -webkit-box-shadow: 0 0 0 30px #000000 inset !important;
    }

    form{
        width: 23rem;
    }

  </style>

<section class="vh-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6 text-black">
        <div class="px-5 ms-xl-4">
          <img src="{{asset('images/AuraLogo.svg')}}" alt="Logo" style="width: 7rem;" class="pt-5 mt-xl-4">
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

              <input type="password" name="password"  placeholder="Password" required/>
              @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
                @endif

              <button id="submit-button" type="submit">Log In</button>

            <p>Ainda não tem conta? <a href="#!" class="link-info" id="registo-mensagem" >Register here</a></p>
          
            @if (session('success'))
                <p class="success">
                {{ session('success') }}
                </p>
            @endif
            </form>

        </div>

      </div>
      <div class="col-sm-6 px-0 d-none d-sm-block">
        <img src="{{asset('images/LoginBanner.svg')}}"
          alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
      </div>
    </div>
  </div>
</section>

</section>

<!--
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
-->
@endsection