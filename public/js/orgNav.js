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