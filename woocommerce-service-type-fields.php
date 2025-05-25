if (!is_admin()) return;

add_action('woocommerce_product_options_general_product_data', function () {
    woocommerce_wp_select([
        'id' => '_service_type',
        'label' => 'Hizmet Türü',
        'description' => 'Bu ürünün hizmet türünü seçin.',
        'options' => [
            '' => 'Seçiniz',
            'villa' => 'Tatil Evi',
            'transfer' => 'Transfer Hizmeti',
            'tour' => 'Günlük Tur'
        ]
    ]);

    echo '<div class="options_group show-if-villa">';
    woocommerce_wp_text_input([
        'id' => '_komisyon_orani',
        'label' => 'Komisyon Oranı (%)',
        'type' => 'number',
        'custom_attributes' => [
            'step' => '1',
            'min' => '0',
            'max' => '100'
        ]
    ]);
    echo '</div>';

    echo '<div class="options_group show-if-villa">';
    woocommerce_wp_text_input([
        'id' => '_minimum_stay_days',
        'label' => 'Minimum Konaklama (gün)',
        'type' => 'number',
        'desc_tip' => true,
        'description' => 'Bu villa için en az kaç gün kiralama yapılabilir?',
        'custom_attributes' => [
            'min' => '1',
            'step' => '1'
        ]
    ]);
    echo '</div>';

    echo '<div class="options_group show-if-villa">';
    woocommerce_wp_text_input([
        'id' => '_villa_latitude',
        'label' => 'Enlem (Latitude)',
        'type' => 'text',
    ]);
    woocommerce_wp_text_input([
        'id' => '_villa_longitude',
        'label' => 'Boylam (Longitude)',
        'type' => 'text',
    ]);
    echo '</div>';
});

add_action('woocommerce_process_product_meta', function ($post_id) {
    foreach (['_service_type', '_komisyon_orani', '_minimum_stay_days', '_villa_latitude', '_villa_longitude'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
});
