
<div class="container-fluid px-sm-0 px-lg-3 mt-5">
    <div class="row justify-content-center">
        <div class="col-10 col-sm-10 col-md-8 col-lg-8 col-xl-7">
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
                <div class="card mb-4 shadow-sm" style="border-radius: 15px;">
                    <a href="{{ route('events', ['id' => $event->id]) }}" class="text-decoration-none text-light">
                    <div class="row ">
                        <div class="col-3 col-md-2 p-2 text-center d-sm-block">             
                            <img src="{{ asset('storage/eventos/' . $event->photo) }}" class="card-img " alt="Event Photo"></div>
                        <div class="col-5 col-md-7 p-1 card-container d-flex align-items-center">
                            <div class="card-body">
                                <h5 class="card-title d-flex align-items-center">{{ $event->name }}&nbsp
                                @if($header == 'Organizo')
                                        <div class = 'edit p-1' href="{{ route('events', ['id' => $event->id]) }}">@include('widgets.icons.editIcon')</div>
                                    @endif
                                </h5>
                                <p class="card-text ">
                                    <strong>{{ $event->start_date->locale('pt')->translatedFormat('D') }}</strong>&nbsp;&nbsp;{{ $event->start_date->locale('pt')->translatedFormat('d F Y')  }}
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
                </a>
                </div>
            @endforeach
            @else
            <div class="alert alert-danger" role="alert">
                        Error: No events found.
                    </div>
            @endif
                
        </div>
        
    
</div>