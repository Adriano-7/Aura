<div class="card">
    <img class="card-img-top" src="{{ asset('images/eventos/nos-alive.jpg') }}" alt="Card image cap">
    <div class="card-body">
        <h5 class="card-title">{{ $event->name }}</h5>
        <p class="card-text">{{ $event->start_date->format('d M Y') }} <br> {{ $event->location }}</p>
    </div>
</div>
