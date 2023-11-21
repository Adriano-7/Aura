
<div class="custom-container mt-5">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            @foreach($events as $event)
                <div class="card mb-4 shadow-sm" style="border-radius: 15px;">
                    <img src="{{ $event->photo }}" class="card-img-top" alt="Event Photo">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->name }}</h5>
                        <p class="card-text">{{ $event->date }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-2"></div>
    </div>
</div>