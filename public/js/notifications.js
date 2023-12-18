const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

deleteNotification = async (id) => {
    const response = await fetch(`/api/notificacoes/${id}/apagar`, {
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
        const notification = document.getElementById('notification-' + id);
        notification.remove();
    }
}