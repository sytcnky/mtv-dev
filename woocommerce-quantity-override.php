// Villa ürünlerinde ürün detay sayfasında quantity alanını tamamen devre dışı bırak
add_filter('woocommerce_is_sold_individually', function ($sold_individually, $product) {
    if (get_post_meta($product->get_id(), '_service_type', true) === 'villa') {
        return true; // Quantity sabit: 1 adet
    }
    return $sold_individually;
}, 10, 2);

// Sepet ve ödeme sayfasında quantity hücresi DOM’da görünmesin (CSS ile desteklenmeli)
add_filter('woocommerce_cart_item_quantity', function ($product_quantity, $cart_item_key, $cart_item) {
    $product = $cart_item['data'];
    if (get_post_meta($product->get_id(), '_service_type', true) === 'villa') {
        return ''; // DOM’a quantity yazma
    }
    return $product_quantity;
}, 10, 3);

// Mini cart (hızlı sepet) içindeki "1 × fiyat" ifadesini kaldır
add_filter('woocommerce_widget_cart_item_quantity', function ($quantity_html, $cart_item, $cart_item_key) {
    $product = $cart_item['data'];
    if (get_post_meta($product->get_id(), '_service_type', true) === 'villa') {
        return ''; // Mini cart’ta sadece isim + görsel kalsın
    }
    return $quantity_html;
}, 10, 3);
