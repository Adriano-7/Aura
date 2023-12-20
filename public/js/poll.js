function show_results(pollId, optionVoted, card) {
    // If the user has voted, get the vote percentages for each option
    fetch('/api/poll/' + pollId + '/resultados')
    .then(response => {
        return response.json();
    })
    .then(results => {
        // Get the text node of the element containing the poll question
        var questionTextNode = card.querySelector('.mb-0').firstChild;
        // Append ' -Resultados' to the poll question
        questionTextNode.nodeValue += ' (Resultados)';

        // Update the text and style of each option box
        card.querySelectorAll('.option-box').forEach(function(box) {
            var optionId = Number(box.getAttribute('data-option-id'));
            var result = results.find(result => result.option_id === optionId);

            box.textContent = result.text + ' (' + result.percentage + '%)';
            box.style.width = result.percentage + '%';

            if (result.percentage === 0) {
                box.style.width = 'min-content';
                box.style.textAlign = 'left'; 
            }

            box.classList.add('disabled');

            // If the user has voted for this option, add the 'selected' class
            if (optionId === optionVoted) {
                box.classList.add('highlighted');
            }
        });

        // Disable the submit button
        var submitButton = card.querySelector('.btn-primary');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('hidden');
        }
    });
}



window.onload = function() {
    // Get all poll cards
    var pollCards = document.querySelectorAll('.poll-card');

    pollCards.forEach(function(card) {
        var pollId = Number(card.getAttribute('data-poll-id'));
        var isOrganizerOrAdmin = card.getAttribute('data-is-organizer-or-admin') === 'true';

        // Check if the user has voted in this poll or is an organizer or admin
        fetch('/api/poll/' + pollId + '/hasVoted')
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.hasVoted || isOrganizerOrAdmin) {


                    show_results(pollId, data.optionVoted, card);
                }
            });
    });
};

document.querySelectorAll('.poll-card-header').forEach(function(header) {
    var pollId = header.getAttribute('id').split('_')[1];
    var arrowIcon = document.getElementById('arrow_' + pollId);

    $('#collapse_' + pollId).on('shown.bs.collapse', function () {
        arrowIcon.classList.remove('bi-chevron-down');
        arrowIcon.classList.add('bi-chevron-up');
    });

    $('#collapse_' + pollId).on('hidden.bs.collapse', function () {
        arrowIcon.classList.remove('bi-chevron-up');
        arrowIcon.classList.add('bi-chevron-down');
    });
});



document.querySelectorAll('.option-box').forEach(function(box) {
    box.addEventListener('click', function() {
        // If the clicked option box is already selected, deselect it

        if (this.classList.contains('disabled')) {
            return;
        }
        if (this.classList.contains('selected')) {
            this.classList.remove('selected');
            // TODO: Do something when an option is deselected
        } else {
            // Remove the 'selected' class from all option boxes
            document.querySelectorAll('.option-box').forEach(function(box) {
                box.classList.remove('selected');
            });

            // Add the 'selected' class to the clicked option box
            this.classList.add('selected');

            // Get the option ID from the 'data-option-id' attribute
            var optionId = this.getAttribute('data-option-id');

        }
    });
});

document.querySelectorAll('.poll-card').forEach(function(card) {
    var pollId = card.querySelector('.poll-card-header').getAttribute('id').split('_')[1];
    var submitButtons = card.querySelectorAll('.btn-primary');

    submitButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            // Get the selected option
            var selectedOptionBox = card.querySelector('.option-box.selected');
            if (!selectedOptionBox) {
                console.error('No option selected');
                return;
            }
            var selectedOptionId = selectedOptionBox.getAttribute('data-option-id');

            // Send a POST request to register a new vote
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

            // Show the results
            show_results(pollId, selectedOptionId, card);
        });
    });
});


    document.addEventListener('DOMContentLoaded', function() {
        var optionCount = 3;
        var addOptionButton = document.getElementById('addOption');
        var removeOptionButton = document.getElementById('removeOption');
        var inputContainer = document.getElementById('pollForm');

        addOptionButton.addEventListener('click', function() {
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

            inputContainer.appendChild(newLabel);
            inputContainer.appendChild(newInput);

            optionCount++;
        });

        removeOptionButton.addEventListener('click', function() {
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

    document.getElementById('pollForm').addEventListener('submit', function(event) {
        event.preventDefault();
    
        let formData = new FormData(this);
    
        fetch('/api/poll/criar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
    });

    