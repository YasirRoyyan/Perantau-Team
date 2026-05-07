document.addEventListener('DOMContentLoaded', function () {
    var menus = document.querySelectorAll('.account-menu');

    for (var i = 0; i < menus.length; i++) {
        var menu = menus[i];
        var trigger = menu.querySelector('.account-trigger');

        if (!trigger) {
            continue;
        }

        trigger.addEventListener('click', function (event) {
            event.stopPropagation();
            var currentMenu = this.closest('.account-menu');
            var isOpen = currentMenu.classList.toggle('is-open');
            this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.addEventListener('click', function () {
        closeMenus();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMenus();
        }
    });

    function closeMenus() {
        for (var i = 0; i < menus.length; i++) {
            menus[i].classList.remove('is-open');
            var trigger = menus[i].querySelector('.account-trigger');

            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        }
    }
});
