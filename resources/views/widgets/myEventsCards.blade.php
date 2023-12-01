@if ($type == 'organize')
    @php
        $events = $orgEvents;
    @endphp
@else
    @php
        $events = $partEvents;
    @endphp
@endif

<div class="cards" id="{{ $type }}-cards">
    @if ($events)
        @foreach ($events as $event)
            <div class="card mb-4 shadow-sm clickable-card"
                data-href="{{ route('event', ['id' => $event->id]) }}" style="border-radius: 15px;">
                <div class="row ">
                    <div class="col-4 col-md-3 col-lg-3 col-xl-2 p-2 text-center d-sm-block">
                        <img class=" card-img-aura img-fluid"
                            src="{{ asset('assets/eventos/' . $event->photo) }}" alt="Card image cap"
                            style="object-fit: cover;">
                    </div>


                    <div class="col-4 col-md-6 p-1 card-container d-flex align-items-center">
                        <div class="card-body">
                            <h5 class="card-title d-flex align-items-center">{{ $event->name }}&nbsp
                                @if ($type == 'organize')
                                    <a href='{{ route('edit-event', ['id' => $event->id]) }}'
                                        class='edit-icon p-1'>@include('widgets.icons.editIcon')</a>
                                @endif
                            </h5>
                            <p class="col-6 card-text ">
                                <strong>{{ $event->start_date->locale('pt')->translatedFormat('D') }}</strong>&nbsp;&nbsp;{{ $event->start_date->locale('pt')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-4 col-md-3 d-flex align-items-center">
                        <div class="col m-1 d-flex">
                            @include('widgets.icons.userCountIcon')
                            <span>&nbsp;{{ $event->participants->count() }}</span>
                        </div>
                        <div class="col m-1 d-flex ">
                            @include('widgets.icons.commentIcon')
                            <span>&nbsp;{{ $event->comments->count() }}</span>
                        </div>
                    </div>

                </div>

            </div>
        @endforeach
    @else
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Não há eventos para mostrar</h5>
            </div>
        </div>
    @endif

</div>