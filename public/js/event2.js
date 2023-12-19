/****** Common for event-org ********/
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('scroll', function () {
        let pageNav = document.querySelector('#pageNav');
        let navLinks = pageNav.querySelectorAll('.nav-link');
        let navSects = document.querySelectorAll('.navSect');

        let current = '';

        navSects.forEach(navSect => {
            const sectionTop = navSect.offsetTop;
            if (window.pageYOffset + 5 >= sectionTop) {
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

    });
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
                // clear form
                form.reset();

                //Insert the new comment under the file upload section
                const commentBox = document.getElementById('comment-box');
                const newComment = createCommentElement(data);
                let fileUploadSection = document.querySelector('#file-upload-section');

                fileUploadSection.insertAdjacentElement('afterend', newComment);
            });
        }
    }
    ).catch(err => console.log(err));
}

function createCommentElement(commentData) {
    console.log(commentData);

    /*
{
    "message": "Comment added successfully",
    "comment": {
        "user_id": 18,
        "text": "sdsd",
        "event_id": "6",
        "id": 127
    },
    "author": {
        "id": 18,
        "is_admin": false,
        "name": "Teresa Rodrigues",
        "username": "teresa.rodrigues",
        "email": "teresa@example.com",
        "photo": "teresa_rodrigues.jpeg",
        "background_color": "#A08AFA"
    }
}


    Create something like this:
    
<div class="comment-row comment" id="comment-116">
    <a href="http://127.0.0.1:8000/utilizador/teresa.rodrigues">
        <img class="profile-pic" src="http://127.0.0.1:8000/assets/profile/teresa_rodrigues.jpeg">
    </a>
    <div class="comment-content">
        <div class="username-and-date">
            <span class="comment-author"
                onclick="window.location.href='http://127.0.0.1:8000/utilizador/teresa.rodrigues'"
                style="cursor: pointer"> teresa.rodrigues</span>
            <span class="comment-date">6 minutes ago</span>

            <li class="nav-item dropdown">
                <img class="three-dots" src="http://127.0.0.1:8000/assets/three-dots-horizontal.svg"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    style="display: none; cursor: pointer;">

                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                    <li><a class="dropdown-item" onclick="activateEditComment(116)"
                            style="cursor: pointer;">Editar</a></li>

                    <li><a class="dropdown-item" onclick="deleteComment(116)" style="cursor: pointer;">Apagar</a>
                    </li>
                </ul>
            </li>
        </div>
        <p class="comment-text">lllll</p>
        <!--
                if($comment->file_id)
                    <div class="comment-file">
                        <a href="{ asset('assets/uploads/' . $comment->file->file_name) }}">
                            <img src="{ asset('assets/uploads/' . $comment->file->file_name) }}" style="max-height: 15em;">
                        </a>
                    </div>
                endif
            -->
        <div class="votes-row">
            <img src="http://127.0.0.1:8000/assets/icons/vote-disallowed.svg" class="vote-icon"
                style="margin-right:0.5em">
            <span class="comment-votes" inert>0</span>
        </div>
    </div>
</div>
*/

    const commentDiv = document.createElement('div');
    commentDiv.classList.add('comment-row', 'comment');
    commentDiv.id = 'comment-' + commentData.id;

    // create comment content
    const profileLink = document.createElement('a');
    //profileLink.href = window.location.origin + '/utilizador/' + commentData.author.username;

    const profilePic = document.createElement('img');
    profilePic.classList.add('profile-pic');
    profilePic.src = window.location.origin + '/assets/profile/' + commentData.author.photo; // Using relative URL

    profileLink.appendChild(profilePic);

    const commentContent = document.createElement('div');
    commentContent.classList.add('comment-content');

    const usernameAndDate = document.createElement('div');
    usernameAndDate.classList.add('username-and-date');

    const authorLink = document.createElement('span');
    authorLink.classList.add('comment-author');
    authorLink.textContent = commentData.author.username;
    authorLink.onclick = function () {
        window.location.href = window.location.origin + '/utilizador/' + commentData.author.username; // Using relative URL
    };

    const dateSpan = document.createElement('span');
    dateSpan.classList.add('comment-date');
    dateSpan.textContent = '1 minute ago';

    usernameAndDate.appendChild(authorLink);
    usernameAndDate.appendChild(dateSpan);

    // Other elements like three-dots, dropdown, etc., can be created similarly based on your existing HTML structure

    // Append elements to the commentContent div
    commentContent.appendChild(usernameAndDate);

    const commentText = document.createElement('p');
    commentText.classList.add('comment-text');
    commentText.textContent = commentData.text;

    commentContent.appendChild(commentText);

    // Append commentContent to commentDiv
    commentDiv.appendChild(profileLink);
    commentDiv.appendChild(commentContent);

    return commentDiv;
}



function activateEditComment(commentId) {
}

function updateComment(e) {
}