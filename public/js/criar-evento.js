document.getElementById('end_date').addEventListener('change', function() {
    var startDate = new Date(document.getElementById('start_date').value);
    var endDate = new Date(this.value);

    if (endDate < startDate) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'A data de fim não pode ser anterior à data de início!'
        });
        this.value = '';
    } 
});

document.getElementById('start_date').addEventListener('change', function() {
    var startDate = new Date(this.value);
    var endDate = new Date(document.getElementById('end_date').value);

    if (startDate > endDate) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'A data de início não pode ser posterior à data de fim!'
        });
        this.value = '';
    }
});


document.getElementById('end_time').addEventListener('change', function() {
    var startDate = new Date(document.getElementById('start_date').value);
    var endDate = new Date(document.getElementById('end_date').value);

    if (endDate.getTime() === startDate.getTime()) {
        var startTime = document.getElementById('start_time').value;
        var endTime = this.value;

        if (endTime < startTime) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'O horário de término não pode ser anterior ao horário de início!'
            });
            this.value = '';
        }
    }
});

document.getElementById('start_time').addEventListener('change', function() {
    var startDate = new Date(document.getElementById('start_date').value);
    var endDate = new Date(document.getElementById('end_date').value);

    if (endDate.getTime() === startDate.getTime()) {
        var startTime = this.value;
        var endTime = document.getElementById('end_time').value;

        if (startTime > endTime) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'O horário de início não pode ser posterior ao horário de término!'
            });
            this.value = '';
        }
    }
});

document.getElementById('event_picture').addEventListener('change', function() {
    var fileName = this.files.length > 0 ? this.files[0].name : '{{ basename($event->photo) }}';
    document.getElementById('file-name').textContent = fileName;
});