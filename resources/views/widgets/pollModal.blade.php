<!-- Create Poll Modal -->

<div class="modal fade" id="createPollModal" tabindex="-1" aria-labelledby="createPollModalLabel" aria-hidden="true" style="margin-top: 100px;">          
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="createPollModalLabel">Criar Nova Sondagem</h2>
                </div>
                <div class="modal-body" id="inputContainer">
        
                <form id="pollForm">

                <input type="hidden" id="eventId" name="eventId" value="{{ $event->id }}">

                    <label for="mainQuestion">Pergunta</label>
                    <input type="text" id="mainQuestion" name="mainQuestion" class="form-control" required>

                    <label for="option1">Opção 1</label>
                    <input type="text" id="option1" name="option1" class="form-control" required>

                    <label for="option2">Opção 2</label>
                    <input type="text" id="option2" name="option2" class="form-control" required>
                    
                </div>
                <div class="text-center">
                    <button type="button" id="addOption" class="btn btn-primary">Add Option</button>
                    <button type="button" id="removeOption" class="btn btn-danger mx-3">Remove Option</button>
                </div>
                


                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>

            </div>
        </div>
</div>