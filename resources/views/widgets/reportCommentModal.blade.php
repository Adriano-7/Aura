<div class="modal fade" id="reportCommentModal" tabindex="-1" role="dialog" aria-labelledby="reportCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="reportCommentModalLabel">Denunciar Comentário</h5>

                <form id="reportCommentForm">
                    @csrf
                    @method('POST')
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="inappropriate_content"
                            value="inappropriate_content" onchange="updateButtonColor('reportCommentModal')">
                        <label class="form-check-label" for="inappropriate_content">
                            Conteúdo inadequado ou não apropriado
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="violence_threats"
                            value="violence_threats" onchange="updateButtonColor('reportCommentModal')">
                        <label class="form-check-label" for="violence_threats">
                            Ameaças ou incitação à violência
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="incorrect_information"
                            value="incorrect_information" onchange="updateButtonColor('reportCommentModal')">
                        <label class="form-check-label" for="incorrect_information">
                            Informações incorretas ou enganosas
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="harassment_bullying"
                            value="harassment_bullying" onchange="updateButtonColor('reportCommentModal')">
                        <label class="form-check-label" for="harassment_bullying">
                            Assédio ou bullying
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="commercial_spam"
                            value="commercial_spam" onchange="updateButtonColor('reportCommentModal')">
                        <label class="form-check-label" for="commercial_spam">
                            Conteúdo comercial ou spam
                        </label>
                    </div>
                </form>

                <div class="modal-footer" style="border-top: none;">
                    <button type="button" data-dismiss="modal"
                        style="color: white; border-radius: 0.5em; padding: 0.5em;">Cancelar</button>
                    <button type="button" id="denunciarButton" onclick="reportComment()"
                        style="color: #808080; border-radius: 0.5em; padding: 0.5em;" disabled>Denunciar</button>
                </div>
            </div>
        </div>
    </div>
</div>