const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

const deleteCommentButtons = document.querySelectorAll('.delete-comment');
const ignoreCommentButtons = document.querySelectorAll('.ignore-comment');

const deleteEventButtons = document.querySelectorAll('.delete-event');
const ignoreEventButtons = document.querySelectorAll('.ignore-event');

deleteCommentButtons.forEach(button => {
  const commentId = button.dataset.commentId;

  button.addEventListener('click', async e => {
    fetch(`/api/comments/${commentId}`, {
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
    fetch(`/api/reports/comment/${reportId}/resolved`, {
      method: 'PATCH',
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

deleteEventButtons.forEach(button => {
  const eventId = button.dataset.eventId;

  button.addEventListener('click', async e => {
    fetch(`/api/event/${eventId}`, {
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

ignoreEventButtons.forEach(button => {
  const reportId = button.dataset.reportId;

  button.addEventListener('click', async e => {
    fetch(`/api/reports/event/${reportId}/resolved`, {
      method: 'PATCH',
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
