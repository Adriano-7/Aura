document.addEventListener('DOMContentLoaded', function () {
    let orgNav = document.querySelector('#orgNav');
    let navLinks = orgNav.querySelectorAll('.nav-link');
    let navSects = document.querySelectorAll('.navSect');

    navLinks[0].classList.add('active');

    window.addEventListener('scroll', function () {
        let current = '';

        navSects.forEach(navSect => {
            const sectionTop = navSect.offsetTop;
            if (window.scrollY + 5 >= sectionTop) { // Changed here
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