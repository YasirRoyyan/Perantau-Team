// HomePage.js - Script untuk halaman utama

// ===== Navbar scroll effect =====
// Navbar transparan di atas, solid coklat saat scroll
window.onscroll = function() {
    var navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
};

// ===== Smooth scroll untuk navigasi =====
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

// ===== Animasi sederhana saat scroll =====
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

// Set initial style untuk animasi
window.addEventListener('DOMContentLoaded', function() {
    var elements = document.querySelectorAll('.step, .gallery-item');
    for (var k = 0; k < elements.length; k++) {
        elements[k].style.opacity = '0';
        elements[k].style.transform = 'translateY(20px)';
        elements[k].style.transition = 'opacity 0.5s, transform 0.5s';
    }

    // Trigger scroll event untuk elemen yang sudah terlihat
    window.dispatchEvent(new Event('scroll'));
});