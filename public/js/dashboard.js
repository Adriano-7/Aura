const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

/* Comment Reports */
const deleteCommentButtons = document.querySelectorAll('.delete-comment');
const ignoreCommentButtons = document.querySelectorAll('.ignore-comment');

deleteCommentButtons.forEach(button => {
  const commentId = button.dataset.commentId;

  button.addEventListener('click', async e => {
    fetch(`/api/comentario/${commentId}/apagar`, {
      method: 'DELETE',
      headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrf
      }
    })
      .then(res => {
        if (res.ok) {
          e.target
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .parentElement.remove();
        }
      })
      .catch(err => console.log(err));
  });
});

ignoreCommentButtons.forEach(button => {
  const reportId = button.dataset.reportId;

  button.addEventListener('click', async e => {
    fetch(`/api/denuncias/comentarios/${reportId}/marcar-resolvido`, {
      method: 'PUT',
      headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrf
      }
    })
      .then(res => {
        if (res.ok) {
          e.target
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .remove();
        }
      })
      .catch(err => console.log(err));
  });
});


/* Event Reports */
const deleteEventButtons = document.querySelectorAll('.delete-event');
const ignoreEventButtons = document.querySelectorAll('.ignore-event');

deleteEventButtons.forEach(button => {
  const eventId = button.dataset.eventId;

  button.addEventListener('click', async e => {
    try {
        const response = await fetch(`/api/evento/${eventId}/apagar`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            }
        });

        if (response.ok) {
            e.target.parentElement.parentElement.parentElement.parentElement.parentElement.remove();
        } 
        else {
            console.error(`Failed to delete event. Status: ${response.status}`);
        }
    } catch (err) {
        console.error(err);
    }
  });
});

ignoreEventButtons.forEach(button => {
  const reportId = button.dataset.reportId;

  button.addEventListener('click', async e => {
    fetch(`/api/denuncias/evento/${reportId}/marcar-resolvido`, {
      method: 'PUT',
      headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrf
      }
    })
      .then(res => {
        if (res.ok) {
          e.target
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .remove();
        }
      })
      .catch(err => console.log(err));
  });
});

/* Members */

const deleteUserButtons = document.querySelectorAll('.delete-user');

deleteUserButtons.forEach(button => {
  const userId = button.dataset.userId;

  button.addEventListener('click', async e => {
    fetch(`/api/utilizador/${userId}/apagar`, {
      method: 'DELETE',
      headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrf
      }
    })
      .then(res => {
        if (res.ok) {
          e.target
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .parentElement.remove();
        }
      })
      .catch(err => console.log(err));
  });
});

/* Organization */

const deleteOrgButtons = document.querySelectorAll('.delete-org');

deleteOrgButtons.forEach(button => {
  const orgId = button.dataset.orgId;

  button.addEventListener('click', async e => {
    fetch(`/api/organizacao/${orgId}/apagar`, {
      method: 'DELETE',
      headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrf
      }
    })
      .then(res => {
        if (res.ok) {
          e.target
            .parentElement
            .parentElement
            .parentElement
            .parentElement
            .parentElement.remove();
        }
      })
      .catch(err => console.log(err));
  });
});

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
    const request = document.getElementById(`request-${id}`);

    if (request) {
      request.remove();
    }
  }
}