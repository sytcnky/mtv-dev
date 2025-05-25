// Ürün adının altına Gecelik Fiyat satırı EKLENMEYECEK (sadece ad döndürülür)
add_filter('woocommerce_cart_item_name', function ($name, $cart_item, $cart_item_key) {
    $product = $cart_item['data'];

    if (get_post_meta($product->get_id(), '_service_type', true) !== 'villa') {
        return $name;
    }

    return $name; // sade, yalnızca ürün adı
}, 10, 3);

// Mini sepetteki “1 × ₺...” ifadesini kaldır
add_filter('woocommerce_widget_cart_item_quantity', function ($quantity_html, $cart_item, $cart_item_key) {
    $product = $cart_item['data'];
    if (get_post_meta($product->get_id(), '_service_type', true) === 'villa') {
        return '';
    }
    return $quantity_html;
}, 10, 3);

// FİYAT sütununa Fiyat + Toplam + Kalan Tutar yaz
add_filter('woocommerce_cart_item_price', function ($price, $cart_item, $cart_item_key) {
    $product = $cart_item['data'];

    if (get_post_meta($product->get_id(), '_service_type', true) !== 'villa') {
        return $price;
    }

    $total   = floatval($cart_item['villa_total_price'] ?? 0);
    $upfront = floatval($cart_item['villa_upfront_price'] ?? 0);
    $kalan   = max(0, $total - $upfront);
    $gun     = intval($cart_item['villa_days'] ?? 1);
    $gecelik = floatval($product->get_regular_price());

    return '<div class="villa-price-column">
                <div>' . wc_price($gecelik) . ' x ' . $gun . ' Gece</div>
                <div>Toplam Tutar: ' . wc_price($total) . '</div>
                <div>Kalan Tutar: ' . wc_price($kalan) . '<br> <small>(Konaklama sırasında ödenecek)</small></div>
            </div>';
}, 10, 3);

// Sepet toplamı sadece ön ödeme fiyatı üzerinden hesaplanır
add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];

        if (get_post_meta($product->get_id(), '_service_type', true) === 'villa') {
            if (!empty($cart_item['villa_upfront_price'])) {
                $product->set_price(floatval($cart_item['villa_upfront_price']));
            }
        }
    }
});

// Ara Toplam kolonunda fiyatın üzerine "Ön Ödeme Tutarı" yaz
add_filter('woocommerce_cart_item_subtotal', function ($subtotal, $cart_item, $cart_item_key) {
    $product = $cart_item['data'];

    if (get_post_meta($product->get_id(), '_service_type', true) !== 'villa') {
        return $subtotal;
    }

    $upfront = floatval($cart_item['villa_upfront_price'] ?? 0);

    return '<div class="villa-subtotal">
                <strong>Ön Ödeme Tutarı:</strong><br>' . wc_price($upfront) . '
            </div>';
}, 10, 3);
