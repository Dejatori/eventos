(function() {
    "use strict";

    let scrollToTop = document.querySelector(".scroll-to-top");

    if (scrollToTop) {
        window.addEventListener("scroll", function() {
            let scrollDistance = window.scrollY;

            if (scrollDistance > 100) {
                scrollToTop.style.visibility = "visible";
            } else {
                scrollToTop.style.visibility = "hidden";
            }
        });

        scrollToTop.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth" // Esto hace que el scroll sea suave
            });
        });
    }
})();