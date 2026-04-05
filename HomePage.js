window.onscroll = function() {
    var navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
};

// Smooth scroll untuk navigasi
var smoothLinks = document.querySelectorAll('.nav-links a[href^="#"]');
for (var i = 0; i < smoothLinks.length; i++) {
    smoothLinks[i].addEventListener('click', function(e) {
        e.preventDefault();
        var targetId = this.getAttribute('href').substring(1);
        var targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
}
