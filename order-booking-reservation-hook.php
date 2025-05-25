add_action('woocommerce_order_status_completed', function ($order_id) {
    $order = wc_get_order($order_id);

    global $wpdb;
    $table = $wpdb->prefix . 'booking_availability';

    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();

        $type = get_post_meta($product_id, '_service_type', true);
        if ($type !== 'villa') continue;

        $start_date = $item->get_meta('Giriş Tarihi');
        $end_date = $item->get_meta('Çıkış Tarihi');

        if (!$start_date || !$end_date) continue;

        // Kaydı oluştur
        $wpdb->insert($table, [
            'product_id'  => $product_id,
            'order_id'    => $order_id,
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'status'      => 'booked'
        ]);
    }
});
