@section('eventRow')
<div class="flex flex-col m-auto p-auto">
    <h1 class="flex py-5 font-bold text-1xl text-white">
        Mais Populares
    </h1>
    <div class="flex overflow-x-scroll pb-10 hide-scroll-bar scroll-row">
        @foreach ($events as $event)
            @include('widgets.eventCard', ['event' => $event])
        @endforeach
    </div>
</div>
@endsection