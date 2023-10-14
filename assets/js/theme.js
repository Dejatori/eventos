(function() {
    "use strict";

    let scrollToTop = document.querySelector(".scroll-to-top");

    if (scrollToTop) {
        window.addEventListener("scroll", function() {
            let scrollDistance = window.scrollY;

            // Simplificar la lógica de mostrar y ocultar el botón de scroll
            scrollToTop.style.visibility = scrollDistance > 100 ? "visible" : "hidden";
            scrollToTop.style.opacity = scrollDistance > 100 ? "1" : "0";
        });

        scrollToTop.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth" // Esto hace que el scroll sea suave
            });
        });

        // Agregar una transición CSS para hacer la transición de opacidad suave
        scrollToTop.style.transition = "opacity .3s ease-in-out";
    }
})();
