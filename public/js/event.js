/* 

<body>
    <main>
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="http://127.0.0.1:8000"> <img
                        src="http://127.0.0.1:8000/assets/AuraLogo.svg"> </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
                    aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="http://127.0.0.1:8000">
                                <span class=""> DESCOBRIR </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://127.0.0.1:8000/notificacoes">
                                <span class=""> NOTIFICAÇÕES </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://127.0.0.1:8000/meus-eventos">
                                <span class=""> MEUS EVENTOS </span>
                            </a>
                        </li>

                        <form id="search-form" class="form-inline my-2 my-lg-0 looged_in"
                            action="http://127.0.0.1:8000/pesquisa" method="get">
                            <input id="search_bar" class="mr-sm-2 looged_in" name="query" type="text"
                                placeholder="Pesquisa por evento"
                                style="background-image: url(http://127.0.0.1:8000/assets/search-icon.svg);">
                        </form>

                    </ul>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown ">
                            <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <img src="http://127.0.0.1:8000/assets/profile/teresa_rodrigues.jpeg"
                                    class="rounded-circle">
                                <span class="navbar-text dropdown-toggle">Teresa Rodrigues</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="#">Definições</a></li>
                                <li><a class="dropdown-item" href="#">Perfil</a></li>
                                <li><a class="dropdown-item" href="http://127.0.0.1:8000/terminar-sessao">Log Out</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <section id="content">


            <section id="event-header">
                <img src="http://127.0.0.1:8000/assets/eventos/nos-alive.jpg">
                <h1>NOS Alive</h1>
            </section>

            <div id="event-fields">
                <section id="details" class="event-field">
                    <h2>Detalhes</h2>
                    <div class="card">
                        <div id="details-card-content">
                            <div id="first-column">
                                <span id="date">17 Jul 2024</span>
                                <br>
                                <span id="date">20 Jul 2024</span>
                            </div>
                            <div id="second-column">
                                <div id="weekday-and-time">
                                    <span id="weekday">Wed</span>
                                    <span id="time">17:00</span>
                                    <span id="time"> - </span>
                                    <span id="weekday">Sat</span>
                                    <span id="time">06:00</span>
                                </div>
                                <span id="venue">Passeio Marítimo de Algés</span>
                                <span id="city">Oeiras</span>
                            </div>
                            <div id="third-column">
                                <span id="numParticipants"> 4 participantes</span>
                                <form method="POST" action="http://127.0.0.1:8000/evento/1/sair">
                                    <input type="hidden" name="_token" value="beXgqA2xGMnEwvReFPEHvE7lmE5fIlFfMins2CmF"
                                        autocomplete="off"> <input type="hidden" name="_method" value="DELETE"> <button
                                        id="leave-event" type="submit">Sair do evento</button>
                                </form>
                                <div id="span-container">
                                    <button type="button" id="show-participants" class="btn" data-toggle="modal"
                                        data-target="#participantsModal">Ver participantes </button>

                                    <div class="modal fade" id="participantsModal" tabindex="-1" role="dialog"
                                        aria-labelledby="participantsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <img
                                                                src="http://127.0.0.1:8000/assets/profile/isabel_alves.jpeg">
                                                        </div>
                                                        <div class="col-10">
                                                            <h1>Isabel Alves</h1>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <img
                                                                src="http://127.0.0.1:8000/assets/profile/helena_oliveira.jpeg">
                                                        </div>
                                                        <div class="col-10">
                                                            <h1>Helena Oliveira</h1>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <img
                                                                src="http://127.0.0.1:8000/assets/profile/teresa_rodrigues.jpeg">
                                                        </div>
                                                        <div class="col-10">
                                                            <h1>Teresa Rodrigues</h1>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <img
                                                                src="http://127.0.0.1:8000/assets/profile/catarina_santos.jpeg">
                                                        </div>
                                                        <div class="col-10">
                                                            <h1>Catarina Santos</h1>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn" data-toggle="modal"
                                        data-target="#inviteModal">Convidar</button>

                                    <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog"
                                        aria-labelledby="inviteModal" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form id="inviteForm"
                                                        action="http://127.0.0.1:8000/evento/convidar-utilizador"
                                                        method="POST">
                                                        <input type="hidden" name="_token"
                                                            value="beXgqA2xGMnEwvReFPEHvE7lmE5fIlFfMins2CmF"
                                                            autocomplete="off"> <input type="hidden" name="event_id"
                                                            value="1">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <input type="email" class="form-control" id="email"
                                                                        name="email" placeholder="Email">
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="d-flex justify-content-center">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
                <section id="about" class="event-field">
                    <h2>Sobre</h2>
                    <div class="card">
                        <p id="about-text">NOS Alive é um festival de música anual que acontece em Algés, Portugal. É
                            organizado pela Everything is New e patrocinado pela NOS. O festival é conhecido por ter um
                            cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip
                            hop, pop e eletrónica.</p>
                    </div>
                </section>
                <section id="comments" class="event-field">
                    <h2>Comentários (2)</h2>
            </div>
            <div class="card" id="comments-card">
                <div id="add-comment-row" class="comment-row">
                    <img class="profile-pic" src="http://127.0.0.1:8000/assets/profile/teresa_rodrigues.jpeg">
                    <form id="add-comment-form" method="POST" action="http://127.0.0.1:8000/api/comentario/inserir"
                        enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="beXgqA2xGMnEwvReFPEHvE7lmE5fIlFfMins2CmF"
                            autocomplete="off"> <input type="text" name="text" placeholder="Adicione um comentário"
                            autocomplete="off" required>
                        <input type="hidden" name="event_id" value="1">
                        <label for="file-upload" class="icon-button">
                            <img class="icon" src="http://127.0.0.1:8000/assets/clip-icon.svg">
                            <input id="file-upload" type="file" name="file" style="display:none;">
                        </label>
                        <button type="submit" class="icon-button insert-comment">
                            <img class="icon" src="http://127.0.0.1:8000/assets/send-icon.svg">
                        </button>
                    </form>
                </div>

                <div class="comment-row" id="COMMENT-83">
                    <img class="profile-pic" src="http://127.0.0.1:8000/assets/profile/teresa_rodrigues.jpeg">
                    <div class="comment-content">
                        <div class="username-and-date">
                            <span class="comment-author">Teresa Rodrigues</span>
                            <span class="comment-date">9 minutes ago</span>
                            <button class="icon-button edit-comment-btn">
                                <img class="icon" src="http://127.0.0.1:8000/assets/edit-icon.svg">
                            </button>
                            <button class="icon-button delete-comment-btn">
                                <img class="icon" src="http://127.0.0.1:8000/assets/delete-icon.svg">
                            </button>
                        </div>
                        <p class="comment-text">ahhhaaa</p>
                        <div class="votes-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff"
                                class="bi bi-chevron-expand" viewBox="0 0 16 16" style="margin-right:0.5em">
                                <path fill-rule="evenodd"
                                    d="M3.646 9.146a.5.5 0 0 1 .708 0L8 12.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zm0-2.292a.5.5 0 0 0 .708 0L8 3.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708z" />
                            </svg>
                            <span class="comment-votes" inert>0</span>
                        </div>
                    </div>
                </div>
                <div class="comment-row" id="COMMENT-1">
                    <img class="profile-pic" src="http://127.0.0.1:8000/assets/profile/isabel_alves.jpeg">
                    <div class="comment-content">
                        <div class="username-and-date">
                            <span class="comment-author">Isabel Alves</span>
                            <span class="comment-date">20 hours ago</span>
                        </div>
                        <p class="comment-text">Vai ser um concerto incrível!</p>
                        <div class="votes-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff"
                                class="bi bi-arrow-up-circle L0" viewBox="0 0 16 16"
                                style="cursor: pointer; margin-right:0.5em" id="L-1">
                                <path fill-rule="evenodd"
                                    d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff"
                                class="bi bi-arrow-down-circle D0" viewBox="0 0 16 16"
                                style="cursor: pointer; margin-right:0.5em" id="D-1">
                                <path fill-rule="evenodd"
                                    d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                            </svg>
                            <span class="comment-votes" inert>0</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
        </section>
    </main>
</body>

*/

let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$(document).on('click', '.L0', function(){
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

const deleteCommentButtons = document.querySelectorAll('.delete-comment-btn');

deleteCommentButtons.forEach(button => {
    button.addEventListener('click', async e => {
        deleteComment(button);
    });
});

const editCommentButtons = document.querySelectorAll('.edit-comment-btn');

editCommentButtons.forEach(button => {
    button.addEventListener('click', async e => {
        activateEditComment(button);
    });
});

/*
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
*/

/*
$(document).on('click', '.cancel-edit-comment-btn', function(){
    location.reload();
});
*/

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
                    commentRow = document.getElementById(`COMMENT-${commentId}`);

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
                        commentRow.setAttribute('id', `COMMENT-${comment.id}`);
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
                // remove comment row
                commentRow.parentNode.removeChild(commentRow);
                // update comments count
                let commentCountElement = document.querySelector('#comments h2');
                let commentCount = commentCountElement.textContent.split('(')[1].split(')')[0];
                commentCount = parseInt(commentCount);
                commentCount--;
                commentCountElement.textContent = `Comentários (${commentCount})`;
            }
        })
    .catch(err => console.log(err));
}

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