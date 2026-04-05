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