
<a class="card" href="route('event.show', $event->id)">
    <img class="card-img-top img-fluid card-img-aura" src="{{ asset('images/eventos/' . $event->photo) }}" alt="Card image cap" style="object-fit: cover;">
    <div class="card-body">
        <h5 class="card-title">{{ $event->name }}</h5>
        <p class="card-text">{{ $event->start_date->format('d M Y') }} <br> {{ $event->city }}</p>
    </div>
</a>