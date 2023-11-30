document.addEventListener("DOMContentLoaded", function () {
    const dropdownButton = document.getElementById("dropdownMenuButton");
    const dropdownItems = document.querySelectorAll(".dropdown-item");
    const organizoCards = document.getElementById("organize-cards");
    const participoCards = document.getElementById("participate-cards");
    let title = document.getElementById("title-text");

    function toggleCardSections(filterValue) {
        if (filterValue === "organizo") {
            organizoCards.style.display = "block";
            participoCards.style.display = "none";
            title.innerHTML = "Eventos que Organizo";
        } else if (filterValue === "participo") {
            organizoCards.style.display = "none";
            participoCards.style.display = "block";
            title.innerHTML = "Eventos em que Participo";
        }

        dropdownButton.innerHTML = filterValue.charAt(0).toUpperCase() + filterValue.slice(1);
    }

    function handleDropdownItemClick(event) {
        const selectedValue = event.target.getAttribute("data-value");
        toggleCardSections(selectedValue);
    }

    dropdownItems.forEach(item => {
        item.addEventListener("click", handleDropdownItemClick);
    });


    if (title.innerHTML === "Eventos que Organizo ") {
        toggleCardSections("organizo");
    } else {
        toggleCardSections("participo");
    }

    var clickableCards = document.querySelectorAll(".clickable-card");

    clickableCards.forEach(function(card) {
        card.addEventListener("click", function() {
            window.location.href = this.getAttribute("data-href");
        });
    });
});