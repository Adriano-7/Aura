<div class="container home-row">
    <h1 class="row_title">{{$text}}</h1>
    <div class="row flex-row flex-nowrap overflow-auto">
        @foreach ($organizations as $organization)
            <a class="card" href="{{ route('organization.show', ['id' => $organization->id]) }}">
                <img class="card-img-top img-fluid card-img-aura" src="{{ asset('assets/organizations/' . $organization->photo) }}"
                    alt="Card image cap" style="object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $organization->name }}</h5>
                </div>
            </a>
        @endforeach
    </div>
</div>
