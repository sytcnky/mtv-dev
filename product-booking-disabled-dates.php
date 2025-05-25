add_action('wp_footer', function () {
    if (!is_product()) return;

    global $product;
    if (!$product || get_post_meta($product->get_id(), '_service_type', true) !== 'villa') return;

    global $wpdb;
    $table = $wpdb->prefix . 'booking_availability';

    $rows = $wpdb->get_results($wpdb->prepare("
        SELECT start_date, end_date FROM $table
        WHERE product_id = %d AND status = 'booked'
    ", $product->get_id()));

    $disabled = [];

    foreach ($rows as $row) {
        $period = new DatePeriod(
            new DateTime($row->start_date),
            new DateInterval('P1D'),
            (new DateTime($row->end_date))->modify('+1 day')
        );
        foreach ($period as $date) {
            $disabled[] = $date->format('Y-m-d');
        }
    }

    // JS’ye inline olarak aktar
    echo '<script>window.disabledDates = ' . json_encode($disabled) . ';</script>';
});
