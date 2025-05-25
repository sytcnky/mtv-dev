// Sadece harita-gorunumu sayfasında Google Maps API yükle
add_action('wp_footer', function () {
    if (!is_page('harita-gorunumu')) return;

    $key = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';
    ?>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($key); ?>">
    </script>
    <?php
});

// Harita shortcodu: [villa_map_archive]
add_shortcode('villa_map_archive', function () {
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key'     => '_service_type',
                'value'   => 'villa',
                'compare' => '='
            ],
            [
                'key'     => '_villa_latitude',
                'compare' => 'EXISTS'
            ],
            [
                'key'     => '_villa_longitude',
                'compare' => 'EXISTS'
            ],
        ]
    ];

    $query = new WP_Query($args);
    $markers = [];

    foreach ($query->posts as $post) {
        $lat = get_post_meta($post->ID, '_villa_latitude', true);
        $lng = get_post_meta($post->ID, '_villa_longitude', true);
        $product = wc_get_product($post->ID);

        if ($lat && $lng) {
            $markers[] = [
                'title' => get_the_title($post),
                'lat'   => $lat,
                'lng'   => $lng,
                'url'   => get_permalink($post),
                'price' => wc_price($product->get_price())
            ];
        }
    }

    // DEBUG
    echo '<div style="background: #fff3cd; padding: 10px; border: 1px solid #ffeeba; margin-bottom: 20px;"><strong>DEBUG: </strong>' . count($markers) . ' marker bulundu.</div>';

    ob_start();
    ?>
    <div id="villa-map" style="height:500px;" data-markers='<?php echo wp_json_encode($markers); ?>'></div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('villa-map');
        if (!el || typeof google === 'undefined') return;

        const markers = JSON.parse(el.dataset.markers || '[]');

        const mapOptions = {
            zoom: 8,
            center: markers.length
                ? { lat: parseFloat(markers[0].lat), lng: parseFloat(markers[0].lng) }
                : { lat: 36.85, lng: 28.27 } // Marmaris
        };

        const map = new google.maps.Map(el, mapOptions);
        const bounds = new google.maps.LatLngBounds();

        markers.forEach(marker => {
            const position = { lat: parseFloat(marker.lat), lng: parseFloat(marker.lng) };

            const m = new google.maps.Marker({
                position: position,
                map,
                title: marker.title
            });

            const info = new google.maps.InfoWindow({
                content: `<strong><a href="${marker.url}" target="_blank">${marker.title}</a></strong><br>${marker.price}`
            });

            m.addListener('click', () => info.open(map, m));
            bounds.extend(position);
        });

        if (markers.length > 1) {
            map.fitBounds(bounds);
        }
    });
    </script>
    <?php
    return ob_get_clean();
});
