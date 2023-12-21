async function joinEvent(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/api/evento/${id}/aderir`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
        }
    });

    if (!response.ok) {
        console.error(`Error: ${response.statusText}`);
        return;
    }
    else {
        const button = document.getElementById('button-' + id);
        button.innerHTML = 'Sair do Evento';
        button.onclick = () => leaveEvent(id);
    }
}

async function leaveEvent(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/api/evento/${id}/sair`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
        }
    });

    if (!response.ok) {
        console.error(`Error: ${response.statusText}`);
        return;
    }
    else {
        const button = document.getElementById('button-' + id);
        button.innerHTML = 'Aderir ao Evento';
        button.onclick = () => joinEvent(id);
    }
}