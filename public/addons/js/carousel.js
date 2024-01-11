// Fonction pour prévenir le comportement par défaut sur touchmove
function preventDefault(event) {
    event.preventDefault();
}

// Script principal jQuery
jQuery(document).ready(function($) {
    var $carousel = $(".custom-carousel");
    var isDragging = false;
    var startX;
    var startScrollLeft;

    $carousel.owlCarousel({
        autoWidth: true,
        loop: true,
        mouseDrag: false,
    });

    $carousel.on("mousedown touchstart", function(event) {
        isDragging = true;
        startX = event.pageX || event.originalEvent.touches[0].pageX;
        startScrollLeft = $carousel.scrollLeft();
        $carousel.addClass("grabbing");

        // Ajoutez passive: false pour éviter l'avertissement
        document.addEventListener('touchmove', preventDefault, { passive: false });
    });

    $(document).on("mousemove touchmove", function(event) {
        if (isDragging) {
            var currentX = event.pageX || event.originalEvent.touches[0].pageX;
            var deltaX = startX - currentX;

            $carousel.scrollLeft(startScrollLeft + deltaX);
        }
    });

    $(document).on("mouseup touchend", function() {
        if (isDragging) {
            isDragging = false;
            $carousel.removeClass("grabbing");

            // Retirez l'écouteur d'événements après le relâchement
            document.removeEventListener('touchmove', preventDefault);
        }
    });
});
