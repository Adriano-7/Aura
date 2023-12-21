<div class="modal fade" id="participantsModal" tabindex="-1" role="dialog" aria-labelledby="participantsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title">Participantes</h5>
                <div class="row">
                    <div class="part-col">
                        @foreach ($event->participants as $participant)
                        <div class="part-row"
                            onclick="window.location.href='{{ route('user', $participant->username) }}'">
                            <div class="">
                                <img class="profile-pic" src="{{ asset('assets/profile/' . $participant->photo) }}" alt="foto de perfil">
                            </div>
                            <div class="">
                                <h2>{{ $participant->name }}</h2>
                                <h3>{{ $participant->username }}</h3>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>