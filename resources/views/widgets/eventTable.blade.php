<div class="container-fluid px-sm-0  mt-5">

    <div class="row justify-content-center">
        <div class="col-10 col-sm-10 col-md-8 col-lg-8 col-xl-7">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            <div class="row options">
                <div class="col-12 col-sm-6 text-center text-sm-start">
                    <div class="title">Eventos que {{$header}} </div>
                </div>
                <div class="col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end">
                    @if(auth()->user()->userOrganizations->count() > 0)
                    <a href="{{ route('criar-evento') }}" class="btn btn-primary mb-3 me-4 ">Criar Evento</a>
                    @include ('widgets.eventFilter')
                    @endif
                </div>


                <div class="cards">
                    @if($events)
                    @foreach($events as $event)
                    <div class="card mb-4 shadow-sm clickable-card"
                        data-href="{{ route('event', ['id' => $event->id]) }}" style="border-radius: 15px;">
                        <div class="row ">
                            <div class="col-4 col-md-3 col-lg-3 col-xl-2 p-2 text-center d-sm-block">
                                <img class=" card-img-aura img-fluid"
                                    src="{{ asset('storage/eventos/' . $event->photo) }}" alt="Card image cap"
                                    style="object-fit: cover;">
                            </div>


                            <div class="col-4 col-md-6 p-1 card-container d-flex align-items-center">
                                <div class="card-body">
                                    <h5 class="card-title d-flex align-items-center">{{ $event->name }}&nbsp
                                        @if($header == 'Organizo')
                                        <a href='{{route("edit-event", ["id" => $event->id])}}'
                                            class='edit-icon p-1'>@include('widgets.icons.editIcon')</a>
                                        @endif
                                    </h5>
                                    <p class="col-6 card-text ">
                                        <strong>{{ $event->start_date->locale('pt')->translatedFormat('D')
                                            }}</strong>&nbsp;&nbsp;{{
                                        $event->start_date->locale('pt')->translatedFormat('d F Y') }}
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
                    <div class="alert alert-danger" role="alert">
                        Error: No events found.
                    </div>
                    @endif

                </div>
            </div>

            <script>
    document.addEventListener("DOMContentLoaded", function() {
        var clickableCards = document.querySelectorAll(".clickable-card");

        clickableCards.forEach(function(card) {
            card.addEventListener("click", function() {
                window.location.href = this.getAttribute("data-href");
            });
        });
    });
</script>
