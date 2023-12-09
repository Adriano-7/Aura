$(document).ready(function(){
    $(document).on('click', '.L0', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/up`,  window.location.origin), {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle-fill L1");
                    $(this).html('<path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>');
                    otherBtnId = 'D-' + commentId;
                    otherBtn = document.getElementById(otherBtnId);
                    otherBtn.setAttribute('class', "bi bi-arrow-up-circle D1");
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance + 1);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.D0', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/down`,  window.location.origin), {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle D-1");
                    $(this).html('<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>');
                    otherBtnId = 'L-' + commentId;
                    otherBtn = document.getElementById(otherBtnId);
                    otherBtn.setAttribute('class', "bi bi-arrow-up-circle L-1");
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance - 1);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.L1', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/unvote`,  window.location.origin), {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle L0");
                    $(this).html('<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>');
                    otherBtnId = 'D-' + commentId;
                    otherBtn = document.getElementById(otherBtnId);
                    otherBtn.setAttribute('class', "bi bi-arrow-up-circle D0");
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance - 1);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.D1', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/unvote`,  window.location.origin), {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle D0");
                    otherBtnId = 'L-' + commentId;
                    otherBtn = document.getElementById(otherBtnId);
                    otherBtn.setAttribute('class', "bi bi-arrow-up-circle L0");
                    otherBtn.innerHTML = '<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>';
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance - 1);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.L-1', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/unvote`,  window.location.origin), {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle L0");
                    otherBtnId = 'D-' + commentId;
                    otherBtn = document.getElementById(otherBtnId);
                    otherBtn.setAttribute('class', "bi bi-arrow-up-circle D0");
                    otherBtn.innerHTML = '<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>';
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance + 1);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.D-1', function(){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/unvote`,  window.location.origin), {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    $(this).attr('class', "bi bi-arrow-up-circle D0");
                    $(this).html('<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>');
                    otherBtnId = 'L-' + commentId;
                    otherBtn = document.getElementById(otherBtnId);
                    otherBtn.setAttribute('class', "bi bi-arrow-up-circle L0");
                    let voteSpan = $(this).nextAll('.comment-votes').first();
                    let voteBalance = parseInt(voteSpan.text());
                    voteSpan.text(voteBalance + 1);
                }
            })
        .catch(err => console.log(err));
    });
});