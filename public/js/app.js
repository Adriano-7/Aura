const commentDeleteButtons = document.querySelectorAll('.delete-button');

commentDeleteButtons.forEach(button => {
  button.addEventListener('click', async e => {
    const commentId = e.target.dataset.id;
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

    fetch(`api/comments/${commentId}`, {
      method: 'DELETE',
      headers: {
        'content-type': 'application/json',
        'X-CSRF-TOKEN': csrf
      }
    })
      .then(res => {
        // there is some error parsing json (`res.json()`)
        if (res.ok) {
          e.target.parentElement.remove();
        }
      })
      .catch(err => console.log(err));
  });
});
