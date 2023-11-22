window.onscroll = function() {
    scrollFunction()
};
function scrollFunction() {
    var banner = document.getElementById('bandBanner');
    var navbar = document.getElementById('orgNav');
    if (window.pageYOffset > banner.offsetHeight) {
        navbar.classList.add("fixed-top");
    } else {
        navbar.classList.remove("fixed-top");
    }
}
