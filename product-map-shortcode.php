add_shortcode('villa_map', function () {
    global $product;

    $product_id = $product ? $product->get_id() : get_the_ID();
    if (!$product_id) return '';

    $type = get_post_meta($product_id, '_service_type', true);
    if ($type !== 'villa') return '';

    $lat = get_post_meta($product_id, '_villa_latitude', true);
    $lng = get_post_meta($product_id, '_villa_longitude', true);

    if (!$lat || !$lng) return '';

    ob_start();
    echo '<div id="villa-map" 
        data-lat="' . esc_attr($lat) . '" 
        data-lng="' . esc_attr($lng) . '" 
        style="height: 300px; margin-top: 2em;"></div>';

    return ob_get_clean();
});
