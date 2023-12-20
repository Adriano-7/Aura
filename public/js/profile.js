let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

function deleteAccount(userId){
    if (!confirm("Tem a certeza? A sua conta será apagada permanentemente.")) {
        return;
    }

    fetch(new URL(`/api/utilizador/${userId}/apagar`, window.location.origin),{
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    }).then(response => {
        if(response.ok){
            window.location.href = '/';
        }
    }).catch(error => {
        console.log(error);
    });
}

function cancelEdit(){
    let nameInput = document.querySelector('#nameInput');
    let usernameInput = document.querySelector('#usernameInput');
    let emailInput = document.querySelector('#emailInput');

    let completeName = document.querySelector('#complete-name');
    let username = document.querySelector('#username');
    let email = document.querySelector('#email');

    nameInput.value = completeName.textContent;
    usernameInput.value = username.textContent;
    emailInput.value = email.textContent;
}

function submitForm(){
    let form = document.querySelector('#editProfileForm');
    let formData = new FormData(form);
    let formParams = new URLSearchParams(formData);
    
    let userId = formData.get('id');
    fetch(new URL(`/api/utilizador/${userId}/editar`, window.location.origin),{
        method: 'PUT',
        headers: {
            'content-type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrf
        },
        body: formParams
    }).then(response => {
        if(response.ok){
            response.json().then(data => {
                console.log(data.username);
                window.location.href = `/utilizador/${data.username}`;
            });
        }
    }).catch(error => {
        console.log(error);
    });
}