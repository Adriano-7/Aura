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

    comment.forEach(comment => {
        comment.addEventListener('mouseover', () => {
            showThreeDots(comment);
        });

        comment.addEventListener('mouseout', () => {
            hideThreeDots(comment);
        });
    });

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
    fileUpload.addEventListener('change', () => {
        handleFileUpload(fileUpload);
    });

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

    /*Scroll orgBar*/
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