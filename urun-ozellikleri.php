// 🏠 Villa ürün detay bilgilerini başlık ve fiyat arasına ekle
add_action('woocommerce_single_product_summary', 'villa_bilgilerini_ekle', 6);
function villa_bilgilerini_ekle() {
    global $product;

    // Nitelikleri al
    $guests     = $product->get_attribute('pa_misafir');
    $beds       = $product->get_attribute('pa_yatak-sayisi');
    $bedrooms   = $product->get_attribute('pa_yatak-odasi');
    $bathrooms  = $product->get_attribute('pa_banyo');

    // Listeyi başlat
    echo '<ul class="villa-icons-detail">';

    // 👤 Misafir sayısı
    if ($guests) {
        echo '<li>';
        echo '<i class="' . esc_attr(get_icon_by_taxonomy('pa_misafir')) . '"></i>';
        echo esc_html(preg_replace('/\D+/', '', $guests)) . ' Misafir</li>';
    }

    // 🛏️ Yatak Odası + Yatak (tek satır)
    if ($bedrooms || $beds) {
        echo '<li>';
        echo '<i class="' . esc_attr(get_icon_by_taxonomy('pa_yatak-sayisi')) . '"></i>';
        if ($bedrooms) {
            echo esc_html(preg_replace('/\D+/', '', $bedrooms)) . ' Yatak Odası';
        }
        if ($bedrooms && $beds) echo ', ';
        if ($beds) {
            echo esc_html(preg_replace('/\D+/', '', $beds)) . ' Yatak';
        }
        echo '</li>';
    }

    // 🛁 Banyo sayısı
    if ($bathrooms) {
        echo '<li>';
        echo '<i class="' . esc_attr(get_icon_by_taxonomy('pa_banyo')) . '"></i>';
        echo esc_html(preg_replace('/\D+/', '', $bathrooms)) . ' Banyo</li>';
    }

    // Listeyi kapat
    echo '</ul>';
}

// 🖼️ Niteliklere karşılık ikon sınıfları (UIcons)
function get_icon_by_taxonomy($taxonomy) {
    $icons = [
        'pa_misafir'       => 'fi fi-rs-users',
        'pa_yatak-sayisi'  => 'fi fi-rs-bed-alt',
        'pa_yatak-odasi'   => 'fi fi-rs-bed-alt',
        'pa_banyo'         => 'fi fi-rs-shower',
    ];

    return $icons[$taxonomy] ?? '';
}

add_shortcode('ev_ozellikleri_listesi', function () {
    if (!is_product()) return '';

    global $product;

    $terms = get_the_terms($product->get_id(), 'pa_ev-ozellikleri');
    if (empty($terms) || is_wp_error($terms)) return '';

    ob_start();
    echo '<ul class="ev-ozellikleri-listesi">';
    foreach ($terms as $term) {
        echo '<li><i class="fi fi-rs-check-circle"></i> ' . esc_html($term->name) . '</li>';
    }
    echo '</ul>';

    return ob_get_clean();
});


// WooCommerce ürün detay sayfasında tabs'ız sadece açıklamayı göster
add_filter('woocommerce_product_tabs', function ($tabs) {
    unset($tabs['additional_information']); // Ek bilgi tabını kaldır
    return $tabs;
}, 98);

