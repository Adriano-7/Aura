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

    $(document).on('submit', '#add-comment', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = new FormData(form[0]);
        let url = form.attr('action');
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(new URL(url,  window.location.origin), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        }).then(res => {
                if (res.ok) {
                    res.json().then(data => {
                        // clear form
                        form[0].reset();
                        
                        // insert comment
                        let comment = data.comment;
                        let author = data.author;
                        let commentRow = document.createElement('div');
                        commentRow.setAttribute('class', 'comment-row');
                        let profilePic = document.createElement('img');
                        profilePic.setAttribute('class', 'profile-pic');
                        profilePic.setAttribute('src', `${window.location.origin}/assets/profile/${author.photo}`);
                        commentRow.appendChild(profilePic);
                        let commentContent = document.createElement('div');
                        commentContent.setAttribute('class', 'comment-content');
                        let usernameAndDate = document.createElement('div');
                        usernameAndDate.setAttribute('class', 'username-and-date');
                        let commentAuthor = document.createElement('span');
                        commentAuthor.setAttribute('class', 'comment-author');
                        commentAuthor.innerText = author.name;
                        let commentDate = document.createElement('span');
                        commentDate.setAttribute('class', 'comment-date');
                        commentDate.innerText = "Agora mesmo";
                        usernameAndDate.appendChild(commentAuthor);
                        usernameAndDate.appendChild(commentDate);

                        // Create edit button
                        let editButton = document.createElement('button');
                        editButton.setAttribute('class', 'icon-button edit-comment-btn');
                        editButton.setAttribute('id', `EDIT-${comment.id}`);

                        // Create edit icon
                        let editIcon = document.createElement('img');
                        editIcon.setAttribute('class', 'icon');
                        editIcon.setAttribute('src', `${window.location.origin}/assets/edit-icon.svg`);
                        editButton.appendChild(editIcon);

                        usernameAndDate.appendChild(editButton);

                        // Create trash bin button
                        let trashBinButton = document.createElement('button');
                        trashBinButton.setAttribute('class', 'icon-button delete-comment-btn');
                        trashBinButton.setAttribute('id', `DELETE-${comment.id}`);

                        // Create trash bin icon
                        let trashBinIcon = document.createElement('img');
                        trashBinIcon.setAttribute('class', 'icon');
                        trashBinIcon.setAttribute('src', `${window.location.origin}/assets/delete-icon.svg`);
                        trashBinButton.appendChild(trashBinIcon);

                        usernameAndDate.appendChild(trashBinButton);

                        let commentText = document.createElement('p');
                        commentText.setAttribute('class', 'comment-text');
                        commentText.innerText = comment.text;
                        let votesRow = document.createElement('div');
                        votesRow.setAttribute('class', 'votes-row');

                        // Create SVG element
                        let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                        svg.setAttribute('width', '16');
                        svg.setAttribute('height', '16');
                        svg.setAttribute('fill', '#ffffff');
                        svg.setAttribute('class', 'bi bi-chevron-expand');
                        svg.setAttribute('viewBox', '0 0 16 16');
                        svg.style.marginRight = '0.5em';

                        // Create path element inside SVG
                        let path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                        path.setAttribute('fill-rule', 'evenodd');
                        path.setAttribute('d', 'M3.646 9.146a.5.5 0 0 1 .708 0L8 12.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zm0-2.292a.5.5 0 0 0 .708 0L8 3.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708z');
                        svg.appendChild(path);

                        votesRow.appendChild(svg);

                        let commentVotes = document.createElement('span');
                        commentVotes.setAttribute('class', 'comment-votes');
                        commentVotes.setAttribute('inert', '');
                        commentVotes.innerText = '0';
                        votesRow.appendChild(commentVotes);
                        commentContent.appendChild(usernameAndDate);
                        commentContent.appendChild(commentText);
                        commentContent.appendChild(votesRow);
                        commentRow.appendChild(commentContent);
                        let comments = document.getElementById('comments-card');

                        comments.insertBefore(commentRow, comments.children[1]);
                        let commentCount = $('#comments').find('h2').text().split('(')[1].split(')')[0];
                        commentCount = parseInt(commentCount);
                        commentCount++;
                        $('#comments').find('h2').text(`Comentários (${commentCount})`);
                    });
                }
            }
        ).catch(err => console.log(err));
    });

    $(document).on('click', '.delete-comment-btn', function(){
        if (!confirm('Tem a certeza?')) {
            return;
        }

        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let commentId = $(this).attr('id').split('-')[1];

        fetch(new URL(`api/comentario/${commentId}/apagar`,  window.location.origin), {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        }).then(res => {
                if (res.ok) {
                    // remove comment row
                    let commentRow = $(this).parent().parent().parent();
                    commentRow.remove();
                    // update comments count
                    let commentCount = $('#comments').find('h2').text().split('(')[1].split(')')[0];
                    commentCount = parseInt(commentCount);
                    commentCount--;
                    $('#comments').find('h2').text(`Comentários (${commentCount})`);
                }
            })
        .catch(err => console.log(err));
    });

    $(document).on('click', '.edit-comment-btn', function(){
        let commentRow = $(this).parent().parent().parent();
        let commentText = commentRow.find('.comment-text');
        let commentTextValue = commentText.text();
        let commentId = $(this).attr('id').split('-')[1];

        // Create form
        let form = document.createElement('form');
        form.setAttribute('action', `${window.location.origin}/api/comentario/${commentId}/editar`);
        form.setAttribute('method', 'POST');
        form.setAttribute('id', `editComment-${commentId}`);
        form.setAttribute('class', 'edit-comment-form');
        // Create input
        let input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'text');
        input.setAttribute('value', commentTextValue);
        // set autocomplete to off
        input.setAttribute('autocomplete', 'off');
        // Create submit button
        let submitButton = document.createElement('button');
        submitButton.setAttribute('type', 'submit');
        submitButton.setAttribute('class', 'icon-button edit-comment');
        let submitIcon = document.createElement('img');
        submitIcon.setAttribute('class', 'icon');
        submitIcon.setAttribute('src', `${window.location.origin}/assets/save-icon.svg`);
        submitButton.appendChild(submitIcon);
        
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let hiddenCSRF = document.createElement('input');
        hiddenCSRF.setAttribute('type', 'hidden');
        hiddenCSRF.setAttribute('name', '_token');
        hiddenCSRF.setAttribute('value', csrfToken);
        // Append input and submit button to form
        form.appendChild(input);
        form.appendChild(submitButton);
        form.appendChild(hiddenCSRF);
        // Replace comment text with form
        commentText.replaceWith(form);

        // change this icon to a cancel icon
        $(this).attr('class', 'icon-button cancel-edit-comment-btn');
        let cancelIcon = document.createElement('img');
        cancelIcon.setAttribute('class', 'icon');
        cancelIcon.setAttribute('src', `${window.location.origin}/assets/cross-icon.svg`);
        $(this).html(cancelIcon);
    });

    $(document).on('click', '.cancel-edit-comment-btn', function(){
        location.reload();
    });

    document.addEventListener('submit', function(e){
        if(e.target.matches('.edit-comment-form')) {
            editComment(e);
        }
    });
});

function editComment(e){
    e.preventDefault();
    let form = e.target;
    let url = form.action;
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let formData = new FormData(form);
    let formParams = new URLSearchParams(formData);

    fetch(new URL(url,  window.location.origin), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrfToken
        },
        body: formParams
    }).then(res => {
            if (res.ok) {
                res.json().then(data => {
                    let commentId = form.id.split('-')[1];
                    let commentText = form.children[0].value;
                    let commentTextElement = document.createElement('p');
                    commentTextElement.setAttribute('class', 'comment-text');
                    commentTextElement.innerText = commentText;
                    form.replaceWith(commentTextElement);
                    let editButton = document.getElementById(`EDIT-${commentId}`);
                    editButton.setAttribute('class', 'icon-button edit-comment-btn');
                    let editIcon = document.createElement('img');
                    editIcon.setAttribute('class', 'icon');
                    editIcon.setAttribute('src', `${window.location.origin}/assets/edit-icon.svg`);
                    editButton.innerHTML = '';
                    editButton.appendChild(editIcon);
                });
            }
        }
    ).catch(err => console.log(err));
}