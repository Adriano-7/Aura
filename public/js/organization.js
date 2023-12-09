const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

approveOrg = async (id) => {
    const response = await fetch(`/api/organizacao/${id}/aprovar`, {
        method: 'PUT',
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
        const warning = document.getElementById('approveWarning');
        warning.remove();
    }
}