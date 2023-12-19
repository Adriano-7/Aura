/****** Common for event-org ********/
document.addEventListener('DOMContentLoaded', function () {
    let pageNav = document.querySelector('#pageNav');
    let navLinks = pageNav.querySelectorAll('.nav-link');
    let navSects = document.querySelectorAll('.navSect');

    function setActiveNavLink() {
        let current = '';

        navSects.forEach(navSect => {
            const sectionTop = navSect.offsetTop;
            if (window.scrollY + 5 >= sectionTop) {
                current = navSect.getAttribute('id');
            }
        })

        navLinks.forEach(navLink => {
            navLink.classList.remove('active');
        })

        if (current === '') {
            navLinks[0].classList.add('active');
        } else {
            navLinks.forEach(navLink => {
                if (navLink.getAttribute('href') === '#' + current) {
                    navLink.classList.add('active');
                }
            })
        }
    }

    setActiveNavLink();
    window.addEventListener('scroll', setActiveNavLink);
});

/****** Specific for events ********/
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/*Report Modal */
function updateButtonColor() {
    const denunciarButton = document.getElementById('denunciarButton');
    const radioButtons = document.getElementsByName('reason');

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

function openReportModal(commentId) {
    const reportCommentForm = document.getElementById('reportCommentForm');
    const commentIdInput = document.createElement('input');
    commentIdInput.type = 'hidden';
    commentIdInput.name = 'comment_id';
    commentIdInput.value = commentId;

    reportCommentForm.appendChild(commentIdInput);

    $('#reportCommentModal').modal('show');
}

$('#reportCommentModal').on('hidden.bs.modal', function () { resetModalContent(); });

function resetModalContent() {
    const reportCommentForm = document.getElementById('reportCommentForm');
    const commentIdInput = reportCommentForm.querySelector('input[name="comment_id"]');

    if (commentIdInput) {
        commentIdInput.remove();
    }

    const radioButtons = document.getElementsByName('reason');
    for (const radioButton of radioButtons) {
        radioButton.checked = false;
    }

    const denunciarButton = document.getElementById('denunciarButton');
    denunciarButton.style.color = '#808080';
    denunciarButton.disabled = true;
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

    commentDiv.addEventListener('mouseover', () => {
        showThreeDots(commentDiv);
    });

    commentDiv.addEventListener('mouseout', () => {
        hideThreeDots(commentDiv);
    });

    return commentDiv;
}


function activateEditComment(commentId) {
}

function updateComment(e) {
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
                    <img src="${window.location.origin}/assets/icons/vote-up.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance - 1;
            }
            else if(!upVoteSelected && downVoteSelected){
                downButton.removeAttribute('selected');
                downButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-down.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance + 1;
            }
            else if(!upVoteSelected && !downVoteSelected){
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
                    <img src="${window.location.origin}/assets/icons/vote-up.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance - 1;
            }
            else if(!upVoteSelected && downVoteSelected){
                downButton.removeAttribute('selected');
                downButton.innerHTML = `
                    <img src="${window.location.origin}/assets/icons/vote-down.svg" class="vote-icon">
                `;
                commentVotes.textContent = votesBalance + 1;

            }
            else if(!upVoteSelected && !downVoteSelected){
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