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

$(document).ready(function(){
    $(document).on('click', '.likeButton', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        //Route::post('api/comentarios/{id}/addLike', 'addLike')->name('comment.addLike');
        fetch(new URL(`api/comentarios/${commentId}/addLike`,  window.location.origin), {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle-fill alreadyLikedButton");
                    $(this).html('<path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>');
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance + 1);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.alreadyLikedButton', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        //Route::delete('api/comentarios/{id}/removeLike', 'removeLike')->name('comment.removeLike');
        fetch(new URL(`api/comentarios/${commentId}/removeLike`,  window.location.origin), {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle likeButton");
                    $(this).html('<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>');
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance - 1);
                }
            })
        .catch(err => console.log(err));
    });
});