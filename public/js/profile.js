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