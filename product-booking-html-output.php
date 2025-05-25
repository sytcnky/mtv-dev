add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;

    $type = get_post_meta($product->get_id(), '_service_type', true);
    if ($type !== 'villa') return;

    $price = wc_get_price_to_display($product);
    $min_days = (int) get_post_meta($product->get_id(), '_minimum_stay_days', true);
    $komisyon = (int) get_post_meta($product->get_id(), '_komisyon_orani', true);
    ?>

    <div class="booking-dates">
        <label for="booking_range"><strong>Tarih Seçimi:</strong></label>
        <input type="text" id="booking_range" placeholder="Tarih seçiniz">

        <input type="hidden" id="start_date" name="start_date">
        <input type="hidden" id="end_date" name="end_date">
    </div>

    <div id="booking-info" class="booking-info" style="margin-top: 1em; display: none;">
        <div id="booking-warning" style="color: red; display: none;">
            Minimum konaklama süresi <?php echo $min_days ?: 1; ?> gece olmalıdır.
        </div>
        <div id="booking-prices" style="display: none;">
            <p><strong>Toplam Tutar:</strong> <span id="booking-total">₺0</span></p>
            <p><strong>Ön Ödeme Tutarı:</strong><span id="booking-now">₺0</span><br>
				Şimdi ödeyeceğiniz tutar. Kalan tutarı konaklama sırasında ödersiniz.
			</p>
        </div>
    </div>

    <script>
        window.villaBookingData = {
            unitPrice: <?php echo (float) $price; ?>,
            komisyonOrani: <?php echo (int) $komisyon; ?>,
            minimumStay: <?php echo (int) ($min_days ?: 1); ?>
        };
    </script>
    <?php
});

