<div class="container home-row">
    <h1 class="row_title">Eventos Recomendados</h1>
    <div class="row flex-row flex-nowrap overflow-auto">
        @foreach ($events as $event)
            <a class="card" href="{{ route('events', ['id' => $event->id]) }}">
                <img class="card-img-top img-fluid card-img-aura" src="{{ asset('storage/eventos/' . $event->photo) }}"
                    alt="Card image cap" style="object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->name }}</h5>
                    <p class="card-text">{{ $event->start_date->format('d M Y') }} <br> {{ $event->city }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
