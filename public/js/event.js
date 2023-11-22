async function leaveEvent(id) {
    let url = new URL('api/evento/' + id + '/sair', window.location.origin);
    const response = await fetch(url);
    if (response.status === 200) {
        window.location.reload();
    }
}

async function joinEvent(id) {
    let url = new URL('api/evento/' + id + '/aderir', window.location.origin);
    const response = await fetch(url);
    if (response.status === 200) {
        window.location.reload();
    }                                    
}