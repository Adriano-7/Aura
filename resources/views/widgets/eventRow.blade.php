<div class="container home-row">
    <h1 class="row_title">Eventos Recomendados</h1>
    <div class="row flex-row flex-nowrap overflow-auto">
        @foreach ($events as $event)
            @include('widgets.eventCard', ['event' => $event])
        @endforeach
    </div>
</div>
