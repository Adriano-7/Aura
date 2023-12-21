<div class="modal fade" id="reportEventModal" tabindex="-1" role="dialog" aria-labelledby="reportEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="reportEventModalLabel">Denunciar Evento</h5>
                    <form id="reportEventForm">
                        @csrf
                        @method('POST')
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="suspect_fraud"
                                value="suspect_fraud" onchange="updateButtonColor('reportEventModal')">
                            <label class="form-check-label" for="suspect_fraud">
                                Suspeita de fraude ou golpe
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="inappropriate_content"
                                value="inappropriate_content" onchange="updateButtonColor('reportEventModal')">
                            <label class="form-check-label" for="inappropriate_content">
                                Conteúdo inadequado ou ofensivo
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="incorrect_information"
                                value="incorrect_information" onchange="updateButtonColor('reportEventModal')">
                            <label class="form-check-label" for="incorrect_information">
                                Informações incorretas sobre o evento
                            </label>
                        </div>
                    </form>

                    <div class="modal-footer" style="border-top: none;">
                        <button type="button" data-dismiss="modal"
                            style="color: white; border-radius: 0.5em; padding: 0.5em;">Cancelar</button>
                        <button type="button" id="denunciarButton" onclick="reportEvent({{ $event->id }})"
                            style="color: #808080; border-radius: 0.5em; padding: 0.5em;" disabled>Denunciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>