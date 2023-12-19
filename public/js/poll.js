document.querySelectorAll('.card-header').forEach(function(header) {
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

            // TODO: Do something with the option ID
        }
    });
});

document.querySelectorAll('.poll-card').forEach(function(card) {
    var pollId = card.querySelector('.card-header').getAttribute('id').split('_')[1];
    var submitButtons = card.querySelectorAll('.btn-primary');

    submitButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            // Make an AJAX request to get the vote percentages for each option
            fetch('/api/poll/' + pollId + '/resultados')
                .then(response => {
                    return response.json();
                })
                .then(results => {
                    // Update the text and style of each option box
                    card.querySelectorAll('.option-box').forEach(function(box) {
                        var optionId = Number(box.getAttribute('data-option-id'));
                        var result = results.find(result => result.option_id === optionId);

                        box.textContent = result.text + ' (' + result.percentage + '%)';

                        box.style.width = result.percentage + '%';

                        box.classList.add('disabled');
    });

                    });

                    // Disable the submit button
                    event.target.disabled = true;
                    event.currentTarget.classList.add('hidden');
                    
                });
        });
    });