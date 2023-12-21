function show_results(pollId, optionVoted, card) {
    fetch('/api/poll/' + pollId + '/resultados')
        .then(response => {
            return response.json();
        })
        .then(results => {
            var questionTextNode = card.querySelector('.mb-0').firstChild;
            questionTextNode.nodeValue += ' (Resultados)';

            card.querySelectorAll('.option-box').forEach(function (box) {
                var optionId = Number(box.getAttribute('data-option-id'));
                var result = results.find(result => result.option_id === optionId);

                box.textContent = result.text + ' (' + result.percentage + '%)';
                box.style.width = (12 + result.percentage * 0.88) + '%';

                box.classList.add('disabled');
                box.classList.add('optResult')

                if (optionId === optionVoted) {
                    box.classList.add('highlighted');
                }
            });

            var submitButton = card.querySelector('.btn-primary');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('hidden');
            }
        });
}



//Function that opens the modal to create a new poll
document.querySelectorAll('.option-box').forEach(function (box) {
    box.addEventListener('click', function () {
        if (this.classList.contains('disabled')) {
            return;
        }
        if (this.classList.contains('selected')) {
            this.classList.remove('selected');
        } else {
            document.querySelectorAll('.option-box').forEach(function (box) {
                box.classList.remove('selected');
            });

            this.classList.add('selected');
        }
    });
});

//Function to vote in a poll
document.querySelectorAll('.poll-card').forEach(function (card) {
    var pollId = card.querySelector('.poll-card-header').getAttribute('id').split('_')[1];
    var submitButtons = card.querySelectorAll('.btn-primary');

    submitButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            var selectedOptionBox = card.querySelector('.option-box.selected');
            if (!selectedOptionBox) {
                console.error('No option selected');
                return;
            }
            var selectedOptionId = selectedOptionBox.getAttribute('data-option-id');

            fetch('/api/poll/' + pollId + '/votar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    option_id: selectedOptionId
                })
            })
                .then(response => response.json())
                .then(data => {

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error);
                });

            show_results(pollId, selectedOptionId, card);
        });
    });
});


//Function to add and remove options in the create poll form
document.addEventListener('DOMContentLoaded', function () {
    var optionCount = 3;
    var addOptionButton = document.getElementById('addOption');
    var removeOptionButton = document.getElementById('removeOption');
    var inputContainer = document.getElementById('pollForm');
    var btnContainer = document.getElementById('OptBtn');

    addOptionButton.addEventListener('click', function () {
        if (optionCount > 6) {
            swal("Erro", "Só podes adicionar até 6 opções.", "error");
            return;
        }
        var newLabel = document.createElement('label');
        newLabel.setAttribute('for', 'option' + optionCount);
        newLabel.textContent = 'Opção ' + optionCount;

        var newInput = document.createElement('input');
        newInput.setAttribute('type', 'text');
        newInput.setAttribute('id', 'option' + optionCount);
        newInput.setAttribute('name', 'option' + optionCount);

        newInput.classList.add('form-control');
        newInput.setAttribute('required', '');

        btnContainer.parentNode.insertBefore(newLabel, btnContainer);
        btnContainer.parentNode.insertBefore(newInput, btnContainer);

        optionCount++;
    });

    removeOptionButton.addEventListener('click', function () {
        if (optionCount > 3) {
            optionCount--;
            var lastLabel = document.getElementById('option' + optionCount);
            var lastInput = document.querySelector('label[for=option' + optionCount + ']');

            inputContainer.removeChild(lastLabel);
            inputContainer.removeChild(lastInput);
        }
        else {
            swal("Oops!", "O número mínimo de opções é 2", "error");
        }
    });
});
