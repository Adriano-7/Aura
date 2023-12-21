

@if($isOrg)
    <div class="container navSect" id="eventos">
@else
    <div class="container navSect" id="detalhes">
@endif

    <div class="row">
    <div class="col-12 ">
        <h1 id="results-title">{{$title}}</h1>
    </div>
</div>

@if ($isOrg && $organization->events->count() == 0)
    <div class="card mx-auto">
        <div class="row ">
            <div class="col-md-12 text-center">
                <p>Não há eventos planeados no momento.</p>
            </div>
        </div>
    </div>
@else
    <div class="card mx-auto">
        @foreach ($events as $event)
            @php
                $start_date = \Carbon\Carbon::parse($event->start_date);
                $moreThan24Hours = false;
                if ($event->end_date) {
                    $end_date = \Carbon\Carbon::parse($event->end_date);
                    $moreThan24Hours = $start_date->diffInHours($end_date) > 24;
                }
            @endphp
            <div class="row event">
                <div class="col-md-3">
                    <h2>{{ $start_date->format('d M') }}</h2>
                    <h2>{{ $start_date->format('Y') }}</h2>
                </div>
                @if($isOrg)
                <div class="col-md-7" onclick="window.location.href = '/evento/{{ $event->id }}'" style="cursor: pointer;">
                @else
                <div class="col-md-7">
                @endif
                    <h3>{{ $start_date->formatLocalized('%a') }} • {{ $start_date->format('H:i') }}</h3>
                    <h2>{{ $event->name }}</h2>
                    <h3>{{ $event->city }} • {{ $event->venue }}</h3>
                </div>
                @if (Auth::check() && !Auth::user()->is_admin && !$event->participants()->get()->contains(Auth::user()) && $event->start_date > now())
                    <div class="col-md-2 ml-auto">
                        <button type="button"  class="join-event" id="button-{{$event->id}}" onclick="joinEvent({{$event->id}})">Aderir ao Evento</button>
                    </div>
                @elseif (Auth::check() && !Auth::user()->is_admin && $event->participants()->get()->contains(Auth::user()) && $event->start_date > now())
                    <div class="col-md-2 ml-auto">
                        <button type="button"  class="leave-event" id="button-{{$event->id}}" onclick="leaveEvent({{$event->id}})">Sair do Evento</button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
</div>
