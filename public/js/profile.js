let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

function deleteAccount(userId) {
    Swal.fire({
        title: 'Tem a certeza?',
        text: 'A sua conta será apagada permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, apagar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(new URL(`/api/utilizador/${userId}/apagar`, window.location.origin), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = '/';
                }
            }).catch(error => {
                console.log(error);
            });
        }
    });
}

$('#editModal').on('hidden.bs.modal', function () { cancelEdit(); });

function cancelEdit() {
    let nameInput = document.querySelector('#nameInput');
    let usernameInput = document.querySelector('#usernameInput');
    let emailInput = document.querySelector('#emailInput');
    let photoPreview = document.querySelector('#profile-pic-preview');
    let photoInput = document.querySelector('#photoInput');

    let completeName = document.querySelector('#complete-name');
    let username = document.querySelector('#username');
    let email = document.querySelector('#email');
    let photo = document.querySelector('#profile-pic');

    nameInput.value = completeName.textContent;
    usernameInput.value = username.textContent;
    emailInput.value = email.textContent;
    photoPreview.src = photo.src;
    photoInput.value = '';
}

document.getElementById('photoInput').addEventListener('change', function (e) {
    previewProfilePhoto(e);
});

function previewProfilePhoto(e) {
    var preview = document.getElementById('profile-pic-preview');
    preview.src = URL.createObjectURL(e.target.files[0]);
    preview.onload = function () {
        URL.revokeObjectURL(preview.src);
    }
}