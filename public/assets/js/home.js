var smoothLinks = document.querySelectorAll('.nav-links a[href^="#"]');

for (var i = 0; i < smoothLinks.length; i++) {
    smoothLinks[i].addEventListener("click", function (event) {
        var targetId = this.getAttribute("href").substring(1);
        var targetElement = document.getElementById(targetId);

        if (!targetElement) {
            return;
        }

        event.preventDefault();
        targetElement.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
    });
}

function updateNavbarBackground() {
    var navbar = document.querySelector('.navbar');

    if (!navbar) {
        return;
    }
    
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled'); 
    } else {
        navbar.classList.remove('scrolled'); 
    }
}

window.addEventListener('scroll', updateNavbarBackground);
window.addEventListener('load', updateNavbarBackground);
updateNavbarBackground();
