<nav class="navbar navbar-expand-md navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"> <img src="{{ asset('storage/AuraLogo.svg') }}"> </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
            aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample05">
            @if (Auth::check())
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                           <span  class="{{ request()->routeIs('home') ? 'active' : '' }}"> DESCOBRIR </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications') }}">
                            <span class="{{request()->routeIs('notifications') ? 'active' : ''}}"> NOTIFICAÇÕES </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('my-events') }}">
                            <span class="{{ request()->routeIs('my-events') ? 'active' : '' }}"> MEUS EVENTOS </span>
                        </a>
                    </li>
                </ul>

                <form id="search-form" class="form-inline my-2 my-lg-0 {{ request()->routeIs('home') ? 'd-none' : '' }}">
                    <input class="mr-sm-2" type="text" placeholder="Pesquisa por evento" id="search_bar" style="background-image: url({{ asset('storage/search-Icon.svg')}});">
                </form>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown ">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img src="{{ asset('storage/profile/' . $user->photo) }}" class="rounded-circle">
                            <span class="navbar-text dropdown-toggle">{{ $user->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Definições</a></li>
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav" style="margin-left: auto;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar Sessão</a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link">|</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Registar</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
