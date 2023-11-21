<nav class="navbar navbar-expand-md navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"> <img src="{{ asset('storage/AuraLogo.svg') }}"> </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
            aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample05">
            <form id="search-form" class="form-inline my-2 my-lg-0" action="{{ route('search') }}"method="get">
                <input class="mr-sm-2 looged_out" id="search_bar" name="query" type="text" placeholder="Pesquisa por evento"  style="background-image: url({{ asset('storage/search-Icon.svg') }});">
            </form>

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
        </div>
    </div>
</nav>
