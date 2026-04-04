window.onscroll = function() {
    var navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
};

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

window.addEventListener('scroll', function() {
    var elements = document.querySelectorAll('.step, .gallery-item');
    for (var j = 0; j < elements.length; j++) {
        var rect = elements[j].getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            elements[j].style.opacity = '1';
            elements[j].style.transform = 'translateY(0)';
        }
    }
});

window.addEventListener('DOMContentLoaded', function() {
    var elements = document.querySelectorAll('.step, .gallery-item');
    for (var k = 0; k < elements.length; k++) {
        elements[k].style.opacity = '0';
        elements[k].style.transform = 'translateY(20px)';
        elements[k].style.transition = 'opacity 0.5s, transform 0.5s';
    }

    window.dispatchEvent(new Event('scroll'));
});