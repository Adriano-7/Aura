<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 align-items-center">
        <p class="col-md-3 footerText">© 2023 Todos os direitos reservados</p>
    
        <ul class="nav col-md-6 justify-content-center flex-row">
            <li class="nav-item"><a href="{{route('aboutUs')}}" class="nav-link px-2 footerText">Sobre nós</a></li>
            <li class="nav-item"><a href="{{route('privacy')}}" class="nav-link px-2 footerText">Política de Privacidade</a></li>
            <li class="nav-item"><a href="{{route('contacts')}}" class="nav-link px-2 footerText">Contactos</a></li>
        </ul>
    
        <a href="/" class="col-md-3 d-flex align-items-center justify-content-end ">
            <img class="bi me-2" width="40" height="32" src="{{ asset('assets/AuraLogo.svg') }}" alt="logo">
        </a>
</footer>