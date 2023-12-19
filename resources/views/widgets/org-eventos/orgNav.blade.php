<nav id="orgNav" class="navbar">
    <div class="container">
        <div id="navbarNav">
            <ul class="navbar-nav">
                @foreach ($elements as $index => $element)
                    <li data-section="{{ strtolower($element) }}" class="nav-item">
                        <a class="nav-link" href="{{ $href[$index] }}">{{ $element }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>