document.addEventListener('DOMContentLoaded', function () {
    var toggles = document.querySelectorAll('.password-toggle');

    for (var i = 0; i < toggles.length; i++) {
        toggles[i].addEventListener('click', function () {
            var field = this.closest('.password-field');
            var input = field ? field.querySelector('input') : null;

            if (!input) {
                return;
            }

            var isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            this.classList.toggle('is-visible', isHidden);
            this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    }
});
