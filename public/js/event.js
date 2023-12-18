let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('submit', function(e){
    if(e.target.matches('.edit-comment-form')) {
        updateComment(e);
    }
    if(e.target.matches('#add-comment-form')) {
        addComment(e);
    }
});

function updateComment(e){
    e.preventDefault();
    let form = e.target;
    let url = form.action;
    let commentId = url.split('/')[5];

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
                    let commentText = data.text;
                    commentRow = document.getElementById(`comment-${commentId}`);

                    let commentTextElement = document.createElement('p');
                    commentTextElement.setAttribute('class', 'comment-text');
                    commentTextElement.innerText = commentText;
                    
                    let commentContent = commentRow.querySelector('.comment-content');
                    commentContent.replaceChild(commentTextElement, form);
                    
                    let editIcon = document.getElementById(`editButton-${commentId}`);
                    editIcon.style.visibility = 'visible';
                });
            }
        }
    ).catch(err => console.log(err));
}

function addComment(e){
    e.preventDefault();
        let form = e.target;
        let formData = new FormData(form);
        let url = form.getAttribute('action');

        fetch(new URL(url, window.location.origin), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        }).then(res => {
                if (res.ok) {
                    res.json().then(data => {
                        // clear form
                        form.reset();
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
                        editButton.setAttribute('id', `editButton-${comment.id}`);
                        editButton.addEventListener('click', async e => {
                            activateEditComment(editButton);
                        });
                        let editIcon = document.createElement('img');
                        editIcon.setAttribute('class', 'icon');
                        editIcon.setAttribute('src', `${window.location.origin}/assets/edit-icon.svg`);
                        editButton.appendChild(editIcon);
                        usernameAndDate.appendChild(editButton);

                        // Create trash bin button
                        let trashBinButton = document.createElement('button');
                        trashBinButton.setAttribute('class', 'icon-button delete-comment-btn');
                        // add event listener
                        trashBinButton.addEventListener('click', async e => {
                            deleteComment(trashBinButton);
                        });

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
                        commentRow.setAttribute('id', `comment-${comment.id}`);
                        let comments = document.getElementById('comments-card');

                        comments.insertBefore(commentRow, comments.children[1]);
                        let commentCount = document.querySelector('#comments').querySelector('h2').textContent.split('(')[1].split(')')[0];
                        commentCount = parseInt(commentCount);
                        commentCount++;
                        document.querySelector('#comments').querySelector('h2').textContent = `Comentários (${commentCount})`;
                    });
                }
            }
        ).catch(err => console.log(err));
}


const deleteCommentButtons = document.querySelectorAll('.delete-comment-btn');
deleteCommentButtons.forEach(button => {
    button.addEventListener('click', async e => {
        deleteComment(button);
    });
});

function deleteComment(button){
    if (!confirm('Tem a certeza?')) {
        return;
    }

    let commentRow = button.parentElement.parentElement.parentElement;
    let commentId = commentRow.id.split('-')[1];

    fetch(new URL(`api/comentario/${commentId}/apagar`,  window.location.origin), {
        method: 'DELETE',
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
    }).then(res => {
            if (res.ok) {
                commentRow.parentNode.removeChild(commentRow);
                let commentCountElement = document.querySelector('#comments h2');
                let commentCount = commentCountElement.textContent.split('(')[1].split(')')[0];
                commentCount = parseInt(commentCount);
                commentCount--;
                commentCountElement.textContent = `Comentários (${commentCount})`;
            }
        })
    .catch(err => console.log(err));
}


const editCommentButtons = document.querySelectorAll('.edit-comment-btn');
editCommentButtons.forEach(button => {
    button.addEventListener('click', async e => {
        activateEditComment(button);
    });
});

function activateEditComment(button){
    let commentRow = button.parentElement.parentElement.parentElement;
    let commentText = commentRow.querySelector('.comment-text');
    let commentTextValue = commentText.textContent;
    let commentId = commentRow.id.split('-')[1];

    // Create form
    let form = document.createElement('form');
    form.setAttribute('action', `${window.location.origin}/api/comentario/${commentId}/editar`);
    form.setAttribute('method', 'POST');
    form.setAttribute('class', 'edit-comment-form');

    // Create input text
    let input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('name', 'text');
    input.setAttribute('value', commentTextValue);
    input.setAttribute('autocomplete', 'off');

    // Create cancel icon
    let cancelIcon = document.createElement('img');
    cancelIcon.setAttribute('class', 'icon');
    cancelIcon.setAttribute('src', `${window.location.origin}/assets/cross-icon.svg`);
    cancelIcon.addEventListener('click', function(){
        location.reload();
    });

    // Create submit button
    let submitButton = document.createElement('button');
    submitButton.setAttribute('type', 'submit');
    submitButton.setAttribute('class', 'icon-button edit-comment');
    let submitIcon = document.createElement('img');
    submitIcon.setAttribute('class', 'icon');
    submitIcon.setAttribute('src', `${window.location.origin}/assets/save-icon.svg`);
    submitButton.appendChild(submitIcon);
    
    let hiddenCSRF = document.createElement('input');
    hiddenCSRF.setAttribute('type', 'hidden');
    hiddenCSRF.setAttribute('name', '_token');
    hiddenCSRF.setAttribute('value', csrfToken);

    form.appendChild(input);
    form.appendChild(cancelIcon);
    form.appendChild(submitButton);
    form.appendChild(hiddenCSRF);
    commentText.parentNode.replaceChild(form, commentText);

    let editButton = document.getElementById(`editButton-${commentId}`);
    editButton.style.visibility = 'hidden';
}

const upvoteButtons = document.querySelectorAll('.up-btn');
upvoteButtons.forEach(button => {
    button.addEventListener('click', async e => {
        upVote(button);
    });
});

function upVote(upButton){
    let commentRow = upButton.parentElement.parentElement.parentElement;
    let downButton = commentRow.querySelector('.down-btn');
    let commentVotes = commentRow.querySelector('.comment-votes');
    let votesBalance = parseInt(commentVotes.textContent);
    
    let upVoteSelected = upButton.hasAttribute('selected');
    let downVoteSelected = downButton.hasAttribute('selected');
    
    let commentId = commentRow.id.split('-')[1];
    let url = `${window.location.origin}/api/comentario/${commentId}/`;
    let method = 'POST';

    if(upVoteSelected && !downVoteSelected){
        // remove upvote
        url += 'unvote'
        method = 'DELETE'
    }
    else if(!upVoteSelected && downVoteSelected){
        // remove downvote
        url += 'unvote'
        method = 'DELETE'
    }
    else if(!upVoteSelected && !downVoteSelected){
        // add upvote
        url += 'up'
    }
    else{
        console.log('You cannot upvote and downvote');
        return;
    }

    fetch(new URL(url,  window.location.origin), {
        method: method,
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(res => {
        if (res.ok) {
            if(upVoteSelected && !downVoteSelected){
                upButton.removeAttribute('selected');
                upButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                    </svg>
                `;
                commentVotes.textContent = votesBalance - 1;
            }
            else if(!upVoteSelected && downVoteSelected){
                downButton.removeAttribute('selected');
                downButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-down-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                    </svg>
                `;
                commentVotes.textContent = votesBalance + 1;
            }
            else if(!upVoteSelected && !downVoteSelected){
                upButton.setAttribute('selected', '');
                upButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle-fill" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                        <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                    </svg>
                `;
                commentVotes.textContent = votesBalance + 1;
            }
        }
    })
}

const downvoteButtons = document.querySelectorAll('.down-btn');
downvoteButtons.forEach(button => {
    button.addEventListener('click', async e => {
        downVote(button);
    });
});

function downVote(downButton){
    let commentRow = downButton.parentElement.parentElement.parentElement;
    let upButton = commentRow.querySelector('.up-btn');
    let commentVotes = commentRow.querySelector('.comment-votes');
    let votesBalance = parseInt(commentVotes.textContent);
    
    let upVoteSelected = upButton.hasAttribute('selected');
    let downVoteSelected = downButton.hasAttribute('selected');
    
    let commentId = commentRow.id.split('-')[1];
    let url = `${window.location.origin}/api/comentario/${commentId}/`;
    let method = 'POST';

    if(upVoteSelected && !downVoteSelected){
        // remove upvote
        url += 'unvote'
        method = 'DELETE'
    }
    else if(!upVoteSelected && downVoteSelected){
        // remove downvote
        url += 'unvote'
        method = 'DELETE'
    }
    else if(!upVoteSelected && !downVoteSelected){
        // add downvote
        url += 'down'
    }
    else{
        console.log('You cannot upvote and downvote');
        return;
    }

    fetch(new URL(url,  window.location.origin), {
        method: method,
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(res => {
        if (res.ok) {
            if(upVoteSelected && !downVoteSelected){
                upButton.removeAttribute('selected');
                upButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-up-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                    </svg>
                `;
                commentVotes.textContent = votesBalance - 1;
            }
            else if(!upVoteSelected && downVoteSelected){
                downButton.removeAttribute('selected');
                downButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-down-circle" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8m15 0A8 8 0 1 0 0 8a8 8 0 0 0 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                    </svg>
                `;
                commentVotes.textContent = votesBalance + 1;

            }
            else if(!upVoteSelected && !downVoteSelected){
                downButton.setAttribute('selected', '');
                downButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16" style="cursor: pointer; margin-right:0.5em">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                    </svg>
                `;
                commentVotes.textContent = votesBalance - 1;
            }
        }
    })
}