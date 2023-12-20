/****** Specific for events ********/
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/*Report Modal */
function updateButtonColor(modalId) {
    const modal = document.getElementById(modalId);

    const denunciarButton = modal.querySelector('#denunciarButton');
    const radioButtons = modal.querySelectorAll('[name="reason"]');

    let isAnyOptionSelected = false;

    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            isAnyOptionSelected = true;
            break;
        }
    }

    if (isAnyOptionSelected) {
        denunciarButton.style.color = '#826fce';
        denunciarButton.disabled = false;
    }
    else {
        denunciarButton.style.color = '#808080';
        denunciarButton.disabled = true;
    }
}

function openReportCommentModal(commentId) {
    const reportCommentForm = document.getElementById('reportCommentForm');
    const commentIdInput = document.createElement('input');
    commentIdInput.type = 'hidden';
    commentIdInput.name = 'comment_id';
    commentIdInput.value = commentId;

    reportCommentForm.appendChild(commentIdInput);

    $('#reportCommentModal').modal('show');
}

function openReportEventModal(eventId) {
    const reportEventForm = document.getElementById('reportEventForm');
    const eventIdInput = document.createElement('input');
    eventIdInput.type = 'hidden';
    eventIdInput.name = 'event_id';
    eventIdInput.value = eventId;

    reportEventForm.appendChild(eventIdInput);

    $('#reportEventModal').modal('show');
}

$('#reportCommentModal').on('hidden.bs.modal', function () { resetCommentModalContent(); });
$('#reportEventModal').on('hidden.bs.modal', function () { resetEventModalContent(); });

function resetCommentModalContent() {
    const modalForm = document.getElementById('reportCommentForm');
    const inputElement = modalForm.querySelector(`input[name="comment_id"]`);

    if (inputElement) {
        inputElement.remove();
    }

    const radioButtons = document.getElementsByName('reason');
    for (const radioButton of radioButtons) {
        radioButton.checked = false;
    }

    const denunciarButton = document.getElementById('denunciarButton');
    denunciarButton.style.color = '#808080';
    denunciarButton.disabled = true;
}

function resetEventModalContent() {
    const modalForm = document.getElementById('reportEventForm');
    const inputElement = modalForm.querySelector(`input[name="event_id"]`);

    if (inputElement) {
        inputElement.remove();
    }

    const radioButtons = document.getElementsByName('reason');
    for (const radioButton of radioButtons) {
        radioButton.checked = false;
    }

    const denunciarButton = document.getElementById('denunciarButton');
    denunciarButton.style.color = '#808080';
    denunciarButton.disabled = true;
}

function reportComment() {
    const reportCommentForm = document.getElementById('reportCommentForm');
    const commentIdInput = reportCommentForm.querySelector('input[name="comment_id"]');
    const commentId = commentIdInput.value;

    const radioButtons = document.getElementsByName('reason');
    let reason = '';
    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            reason = radioButton.value;
            break;
        }
    }

    const url = `${window.location.origin}/api/denuncias/comentarios/${commentId}/reportar`;

    fetch(new URL(url, window.location.origin), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrfToken
        },
        body: `reason=${reason}`
    }).then(res => {
        if (res.ok) {
            resetCommentModalContent();
            $('#reportCommentModal').modal('hide');

            openMessageModal('Comentário reportado com sucesso!');
        }
    }
    ).catch(err => {
        resetCommentModalContent();
        $('#reportCommentModal').modal('hide');

        openMessageModal('Erro ao reportar comentário!');
    });

    return false;
}

function reportEvent(eventId) {
    const radioButtons = document.getElementsByName('reason');
    let reason = '';
    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            reason = radioButton.value;
            break;
        }
    }

    const url = `${window.location.origin}/api/denuncias/evento/${eventId}/reportar`;

    fetch(new URL(url, window.location.origin), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrfToken
        },
        body: `reason=${reason}`
    }).then(res => {
        if (res.ok) {
            resetEventModalContent();
            $('#reportEventModal').modal('hide');

            openMessageModal('Evento reportado com sucesso!');
        }
    }
    ).catch(err => {
        resetEventModalContent();
        $('#reportEventModal').modal('hide');

        openMessageModal('Erro ao reportar evento!');
    });

    return false;
}

function openMessageModal(message) {
    const messageModal = document.createElement('div');
    messageModal.setAttribute('class', 'modal fade');
    messageModal.setAttribute('id', 'messageModal');
    messageModal.setAttribute('tabindex', '-1');
    messageModal.setAttribute('role', 'dialog');
    messageModal.setAttribute('aria-labelledby', 'messageModalLabel');
    messageModal.setAttribute('aria-hidden', 'true');

    const modalDialog = document.createElement('div');
    modalDialog.setAttribute('class', 'modal-dialog');
    modalDialog.setAttribute('role', 'document');

    const modalContent = document.createElement('div');
    modalContent.setAttribute('class', 'modal-content');

    const modalBody = document.createElement('div');
    modalBody.setAttribute('class', 'modal-body');
    modalBody.textContent = message;

    modalContent.appendChild(modalBody);

    modalDialog.appendChild(modalContent);

    messageModal.appendChild(modalDialog);

    document.body.appendChild(messageModal);

    $(messageModal).modal('show');
}

function showThreeDots(comment) {
    const threeDots = comment.querySelector('.three-dots');
    const commentId = comment.id.split('-')[1];

    threeDots.style.display = 'block';
}


function hideThreeDots(comment) {
    const threeDots = comment.querySelector('.three-dots');
    const commentId = comment.id.split('-')[1];

    threeDots.style.display = 'none';
}

/*Comments */
document.addEventListener('DOMContentLoaded', function () {
    const comment = document.querySelectorAll('.comment');

    const threeDots = document.querySelector('.three-dots');
    if (threeDots) {
        comment.forEach(comment => {
            comment.addEventListener('mouseover', () => {
                showThreeDots(comment);
            });

            comment.addEventListener('mouseout', () => {
                hideThreeDots(comment);
            });
        });
    }

    const fileUpload = document.getElementById('file-upload');
    if (fileUpload) {
        fileUpload.addEventListener('change', () => {
            handleFileUpload(fileUpload);
        });
    }
    function handleFileUpload(input) {
        const fileUploadSection = document.getElementById('file-upload-section');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file');

        const file = input.files[0];

        if (file) {
            fileName.textContent = file.name;
            fileUploadSection.style.display = 'flex';

            input.disabled = true;
        } else {
            fileUploadSection.style.display = 'none';
        }
    }

    const removeFileBtn = document.getElementById('remove-file');
    removeFileBtn.addEventListener('click', () => {
        removeFile();
    });

    function removeFile() {
        const fileInput = document.getElementById('file-upload');
        const fileUploadSection = document.getElementById('file-upload-section');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');

        fileInput.value = null;
        fileName.textContent = '';
        fileUploadSection.style.display = 'none';

        fileInput.disabled = false;
    }
});

document.addEventListener('submit', function (e) {
    if (e.target.matches('.edit-comment-form')) {
        updateComment(e);
    }
    if (e.target.matches('#add-comment-form')) {
        addComment(e);
    }
});

function addComment(e) {
    e.preventDefault();
    let form = e.target;
    let formData = new FormData(form);
    let fileUpload = document.getElementById('file-upload').files[0];
    let fileName = document.getElementById('file-name').textContent;

    if (fileUpload) {
        formData.append('file', fileUpload);
    }

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
                form.reset();

                const newComment = createCommentElement(data);

                let fileUploadSection = document.querySelector('#file-upload-section');

                fileUploadSection.style.display = 'none';
                fileUploadSection.querySelector('#file-name').textContent = '';

                fileUploadSection.insertAdjacentElement('afterend', newComment);

                let sectionTitle = document.querySelector('#comentarios #section-title');
                let numberOfComments = sectionTitle.textContent.split('•')[1].trim();
                numberOfComments++;
                sectionTitle.textContent = `Comentários • ${numberOfComments}`;
            });
        }
    }
    ).catch(err => console.log(err));
}

function createCommentElement(commentData) {
    console.log(commentData);

    let commentDiv = document.createElement('div');
    commentDiv.setAttribute('class', 'comment-row comment');
    commentDiv.setAttribute('id', `comment-${commentData.comment.id}`);

    let profileLink = document.createElement('a');
    profileLink.setAttribute('href', `${window.location.origin}/utilizador/${commentData.author.username}`);
    let profilePic = document.createElement('img');
    profilePic.setAttribute('class', 'profile-pic');
    profilePic.setAttribute('src', `${window.location.origin}/assets/profile/${commentData.author.photo}`);
    profileLink.appendChild(profilePic);
    commentDiv.appendChild(profileLink);

    let commentContentDiv = document.createElement('div');
    commentContentDiv.setAttribute('class', 'comment-content');

    let usernameDateDiv = document.createElement('div');
    usernameDateDiv.setAttribute('class', 'username-and-date');

    let commentAuthorSpan = document.createElement('span');
    commentAuthorSpan.setAttribute('class', 'comment-author');
    commentAuthorSpan.setAttribute('style', 'cursor: pointer');
    commentAuthorSpan.setAttribute('onclick', `window.location.href='${window.location.origin}/utilizador/${commentData.author.username}'`);
    commentAuthorSpan.textContent = commentData.author.name;
    usernameDateDiv.appendChild(commentAuthorSpan);

    let commentDateSpan = document.createElement('span');
    commentDateSpan.setAttribute('class', 'comment-date');
    commentDateSpan.textContent = '1 second ago';
    usernameDateDiv.appendChild(commentDateSpan);

    let dropdownLi = document.createElement('li');
    dropdownLi.setAttribute('class', 'nav-item dropdown');

    let dropdownImg = document.createElement('img');
    dropdownImg.setAttribute('class', 'three-dots');
    dropdownImg.setAttribute('src', `${window.location.origin}/assets/three-dots-horizontal.svg`);
    dropdownImg.setAttribute('data-toggle', 'dropdown');
    dropdownImg.setAttribute('aria-haspopup', 'true');
    dropdownImg.setAttribute('aria-expanded', 'false');
    dropdownImg.setAttribute('style', 'display: none; cursor: pointer;');
    dropdownLi.appendChild(dropdownImg);

    let dropdownUl = document.createElement('ul');
    dropdownUl.setAttribute('class', 'dropdown-menu dropdown-menu-dark');
    dropdownUl.setAttribute('aria-labelledby', 'navbarDarkDropdownMenuLink');
    dropdownLi.appendChild(dropdownUl);

    let editLi = document.createElement('li');
    let editA = document.createElement('a');
    editA.setAttribute('class', 'dropdown-item');
    editA.setAttribute('onclick', `activateEditComment(${commentData.comment.id})`);
    editA.setAttribute('style', 'cursor: pointer;');
    editA.textContent = 'Editar';
    editLi.appendChild(editA);
    dropdownUl.appendChild(editLi);

    let deleteLi = document.createElement('li');
    let deleteA = document.createElement('a');
    deleteA.setAttribute('class', 'dropdown-item');
    deleteA.setAttribute('onclick', `deleteComment(${commentData.comment.id})`);
    deleteA.setAttribute('style', 'cursor: pointer;');
    deleteA.textContent = 'Apagar';
    deleteLi.appendChild(deleteA);
    dropdownUl.appendChild(deleteLi);

    usernameDateDiv.appendChild(dropdownLi);

    commentContentDiv.appendChild(usernameDateDiv);

    let commentTextP = document.createElement('p');
    commentTextP.setAttribute('class', 'comment-text');
    commentTextP.textContent = commentData.comment.text;
    commentContentDiv.appendChild(commentTextP);

    commentDiv.appendChild(commentContentDiv);

    if (commentData.file) {
        let commentFileDiv = document.createElement('div');
        commentFileDiv.setAttribute('class', 'comment-file');

        let fileLink = document.createElement('a');
        fileLink.setAttribute('href', `${window.location.origin}/assets/comments/${commentData.file.file_name}`);

        let fileImg = document.createElement('img');
        fileImg.setAttribute('src', `${window.location.origin}/assets/comments/${commentData.file.file_name}`);

        fileLink.appendChild(fileImg);
        commentFileDiv.appendChild(fileLink);
        commentContentDiv.appendChild(commentFileDiv);
    }

    commentDiv.addEventListener('mouseover', () => {
        showThreeDots(commentDiv);
    });

    commentDiv.addEventListener('mouseout', () => {
        hideThreeDots(commentDiv);
    });

    return commentDiv;
}

function activateEditComment(commentId) {
    let commentRow = document.getElementById(`comment-${commentId}`);
    let commentText = commentRow.querySelector('.comment-text');
    let commentTextValue = commentText.textContent;

    let form = document.createElement('form');
    form.setAttribute('action', `${window.location.origin}/api/comentario/${commentId}/editar`);
    form.setAttribute('method', 'POST');
    form.setAttribute('class', 'edit-comment-form');

    let input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('name', 'text');
    input.setAttribute('value', commentTextValue);
    input.setAttribute('autocomplete', 'off');

    let cancelIcon = document.createElement('img');
    cancelIcon.setAttribute('class', 'icon');
    cancelIcon.setAttribute('src', `${window.location.origin}/assets/cross-icon.svg`);
    cancelIcon.addEventListener('click', function () {
        location.reload();
    });

    let submitButton = document.createElement('button');
    submitButton.setAttribute('type', 'submit');
    submitButton.setAttribute('class', 'icon-button edit-comment');
    let submitIcon = document.createElement('img');
    submitIcon.setAttribute('class', 'icon');
    submitIcon.setAttribute('src', `${window.location.origin}/assets/save-icon.svg`);
    submitButton.setAttribute('style', 'background-color: transparent; border: none;');
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
}

document.addEventListener('submit', function (e) {
    if (e.target.matches('.edit-comment-form')) {
        updateComment(e);
    }
});

function updateComment(e) {
    e.preventDefault();
    let form = e.target;
    let url = form.action;
    let commentId = url.split('/')[5];

    let formData = new FormData(form);
    let formParams = new URLSearchParams(formData);

    fetch(new URL(url, window.location.origin), {
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

const upvoteButtons = document.querySelectorAll('.up-btn');
upvoteButtons.forEach(button => {
    button.addEventListener('click', async e => {
        upVote(button);
    });
});

function upVote(upButton) {
    let commentRow = upButton.parentElement.parentElement.parentElement;
    let downButton = commentRow.querySelector('.down-btn');
    let commentVotes = commentRow.querySelector('.comment-votes');
    let votesBalance = parseInt(commentVotes.textContent);

    let upVoteSelected = upButton.hasAttribute('selected');
    let downVoteSelected = downButton.hasAttribute('selected');

    let commentId = commentRow.id.split('-')[1];
    let url = `${window.location.origin}/api/comentario/${commentId}/`;
    let method = 'POST';

    if (upVoteSelected && !downVoteSelected) {
        // remove upvote
        url += 'unvote'
        method = 'DELETE'
    }
    else if (!upVoteSelected && downVoteSelected) {
        // remove downvote
        url += 'unvote'
        method = 'DELETE'
    }
    else if (!upVoteSelected && !downVoteSelected) {
        // add upvote
        url += 'up'
    }
    else {
        console.log('You cannot upvote and downvote');
        return;
    }

    fetch(new URL(url, window.location.origin), {
        method: method,
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(res => {
        if (res.ok) {
            if (upVoteSelected && !downVoteSelected) {
                upButton.removeAttribute('selected');
                upButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-up.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance - 1;
            }
            else if (!upVoteSelected && downVoteSelected) {
                downButton.removeAttribute('selected');
                downButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-down.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance + 1;
            }
            else if (!upVoteSelected && !downVoteSelected) {
                upButton.setAttribute('selected', '');
                upButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-up-selected.svg" class="vote-icon">
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

function downVote(downButton) {
    let commentRow = downButton.parentElement.parentElement.parentElement;
    let upButton = commentRow.querySelector('.up-btn');
    let commentVotes = commentRow.querySelector('.comment-votes');
    let votesBalance = parseInt(commentVotes.textContent);

    let upVoteSelected = upButton.hasAttribute('selected');
    let downVoteSelected = downButton.hasAttribute('selected');

    let commentId = commentRow.id.split('-')[1];
    let url = `${window.location.origin}/api/comentario/${commentId}/`;
    let method = 'POST';

    if (upVoteSelected && !downVoteSelected) {
        url += 'unvote'
        method = 'DELETE'
    }
    else if (!upVoteSelected && downVoteSelected) {
        url += 'unvote'
        method = 'DELETE'
    }
    else if (!upVoteSelected && !downVoteSelected) {
        url += 'down'
        method = 'POST';
    }
    else {
        console.log('You cannot upvote and downvote');
        return;
    }

    fetch(new URL(url, window.location.origin), {
        method: method,
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(res => {
        if (res.ok) {
            if (upVoteSelected && !downVoteSelected) {
                upButton.removeAttribute('selected');
                upButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-up.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance - 1;
            }
            else if (!upVoteSelected && downVoteSelected) {
                downButton.removeAttribute('selected');
                downButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-down.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance + 1;

            }
            else if (!upVoteSelected && !downVoteSelected) {
                downButton.setAttribute('selected', '');
                downButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-down-selected.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance - 1;
            }
        }
    })
}

function deleteComment(commentId) {
    if (!confirm('Tem a certeza?')) {
        return;
    }

    let url = `${window.location.origin}/api/comentario/${commentId}/apagar`;
    fetch(new URL(url, window.location.origin), {
        method: 'DELETE',
        headers: {
            'content-type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(res => {
        if (res.ok) {
            let commentRow = document.getElementById(`comment-${commentId}`);
            commentRow.remove();

            let sectionTitle = document.querySelector('#comentarios #section-title');
            let numberOfComments = sectionTitle.textContent.split('•')[1].trim();
            numberOfComments--;
            sectionTitle.textContent = `Comentários • ${numberOfComments}`;
        }
    })
        .catch(err => console.log(err));
}

async function deleteEvent(eventId) {
    const response = await fetch(`/api/evento/${eventId}/apagar`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        }
    });

    if (response.ok) {
        window.location.href = '/';
    } else {
        console.error(`Failed to delete event. Status: ${response.status}`);
    }
}