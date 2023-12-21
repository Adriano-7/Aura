<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="inviteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title">Convidar</h5>
                <div class="row">
                    <div class="part-col">
                        <div class="part-row align-items-center">
                            <form id="inviteForm" action="{{ route('event.inviteUser') }}" method="POST">
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                @csrf
                                @method('POST')
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <div class="form-group">
                                            <input type="email" name="email" placeholder="Email" id="inviteInput"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <button id="submit-button" type="submit">Convidar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>