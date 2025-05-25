// Tarihleri ve hesapları sepete ekle
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    $type = get_post_meta($product_id, '_service_type', true);
    if ($type !== 'villa') return $cart_item_data;

    $start = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

    if ($start) $cart_item_data['start_date'] = $start;
    if ($end) $cart_item_data['end_date'] = $end;

    if ($start && $end) {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        if ($end_ts > $start_ts) {
            $days = ceil(($end_ts - $start_ts) / DAY_IN_SECONDS);
            $unit_price = (float) get_post_meta($product_id, '_price', true);
            $komisyon = (int) get_post_meta($product_id, '_komisyon_orani', true);
            $total_price = $unit_price * $days;
            $upfront = ceil($total_price * ($komisyon / 100));

            $cart_item_data['villa_days'] = $days;
            $cart_item_data['villa_total_price'] = $total_price;
            $cart_item_data['villa_upfront_price'] = $upfront;
        }
    }

    return $cart_item_data;
}, 10, 2);

// Sepet sayfasında gösterim
add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
    if (!empty($cart_item['start_date'])) {
        $item_data[] = [
            'key' => 'Giriş Tarihi',
            'value' => wc_clean($cart_item['start_date'])
        ];
    }

    if (!empty($cart_item['end_date'])) {
        $item_data[] = [
            'key' => 'Çıkış Tarihi',
            'value' => wc_clean($cart_item['end_date'])
        ];
    }

    return $item_data;
}, 10, 2);

// Sipariş meta’ya kaydet
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (!empty($values['start_date'])) {
        $item->add_meta_data('Giriş Tarihi', $values['start_date']);
    }

    if (!empty($values['end_date'])) {
        $item->add_meta_data('Çıkış Tarihi', $values['end_date']);
    }

    if (!empty($values['villa_days'])) {
        $item->add_meta_data('Gün Sayısı', $values['villa_days']);
    }

    if (!empty($values['villa_total_price'])) {
        $item->add_meta_data('Toplam Tutar', wc_price($values['villa_total_price']));
    }

    if (!empty($values['villa_upfront_price'])) {
        $item->add_meta_data('Şimdi Ödenecek', wc_price($values['villa_upfront_price']));
    }
}, 10, 4);
