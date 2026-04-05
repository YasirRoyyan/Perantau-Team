document.addEventListener('DOMContentLoaded', function() {
    var box = document.querySelector('.prepare-box');
    box.style.opacity = '0';
    box.style.transform = 'translateY(20px)';
    box.style.transition = 'opacity 0.6s, transform 0.6s';

    setTimeout(function() {
        box.style.opacity = '1';
        box.style.transform = 'translateY(0)';
    }, 200);
});