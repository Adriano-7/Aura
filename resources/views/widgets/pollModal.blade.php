<div class="modal fade createPollModal" tabindex="-1" aria-labelledby="createPollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" id="inputContainer">
                <h5 class="modal-title">Criar Nova Sondagem</h5>
                <div class="row">

                    <form id="pollForm" action="{{ route('poll.store') }}" method="POST">
                        @csrf

                        <input type="hidden" id="eventId" name="eventId" value="{{ $event->id }}">

                        <label for="mainQuestion">Pergunta</label>
                        <input type="text" id="mainQuestion" name="mainQuestion" class="form-control" required>

                        <label for="option1">Opção 1</label>
                        <input type="text" id="option1" name="option1" class="form-control" required>

                        <label for="option2">Opção 2</label>
                        <input type="text" id="option2" name="option2" class="form-control" required>


                        <div class="text-center " id="OptBtn">
                            <button type="button" id="addOption" class="pollBtn">Add Option</button>
                            <button type="button" id="removeOption" class="pollBtn">Remove Option</button>
                        </div>

                        <div class="modal-footer d-flex justify-content-center" style="border-top: none;">
                            <button type="submit" id="submit" class="btn">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>