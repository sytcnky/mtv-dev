add_action('wp_footer', function () {
    if (!is_product()) return;

    // Eğer sabit tanımlı değilse boş string dön
    $key = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';

    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('villa-map');
        if (!container) return;

        const lat = parseFloat(container.dataset.lat);
        const lng = parseFloat(container.dataset.lng);

        window.initGoogleMap = function () {
            const map = new google.maps.Map(container, {
                center: { lat: lat, lng: lng },
                zoom: 15
            });

            new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: "Bu evin konumu"
            });
        };

        // Google Maps API yükle
        const script = document.createElement('script');
        script.src = "https://maps.googleapis.com/maps/api/js?key=<?php echo esc_js($key); ?>&callback=initGoogleMap";
        script.async = true;
        document.head.appendChild(script);
    });
    </script>
    <?php
});
