add_shortcode('custom_booking_filter', function () {
    ob_start();
    ?>
    <div class="custom-booking-filter">
        <form method="get" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">

            <!-- Giriş Tarihi -->
            <div class="filter-group">
                <label for="start_date"><strong>Giriş Tarihi:</strong></label>
				<input type="text" id="filter_start_date" name="start_date" placeholder="Seçiniz" value="<?php echo esc_attr($_GET['start_date'] ?? ''); ?>">

            </div>

            <!-- Çıkış Tarihi -->
            <div class="filter-group">
                <label for="end_date"><strong>Çıkış Tarihi:</strong></label>
				<input type="text" id="filter_end_date" name="end_date" placeholder="Seçiniz" value="<?php echo esc_attr($_GET['end_date'] ?? ''); ?>">
            </div>

            <!-- Lokasyon -->
            <div class="filter-group">
                <label><strong>Lokasyon</strong></label>
                <div id="lokasyon-dropdown" class="custom-dropdown">
                    <div class="dropdown-toggle"></div>
                    <div class="dropdown-menu">
                        <ul>
                        <?php
                        $locations = get_terms(['taxonomy' => 'pa_lokasyon', 'hide_empty' => false]);
                        $selectedLoc = array_map('sanitize_title', (array) ($_GET['custom_lokasyon'] ?? []));
                        foreach ($locations as $loc) {
                            $isActive = in_array($loc->slug, $selectedLoc) ? ' active' : '';
                            echo "<li class='dropdown-item{$isActive}' data-value='{$loc->slug}'>{$loc->name}</li>";
                        }
                        ?>
                        </ul>
                    </div>
                    <div class="selected-tags"></div>
                </div>
                <div id="lokasyon-hidden-inputs">
                    <?php foreach ($selectedLoc as $slug): ?>
                        <input type="hidden" name="custom_lokasyon[]" value="<?php echo esc_attr($slug); ?>" data-value="<?php echo esc_attr($slug); ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Ev Tipi -->
			<?php if (!is_front_page()): ?>
            <div class="filter-group">
                <label><strong>Ev Tipi</strong></label>
                <div id="evtipi-dropdown" class="custom-dropdown">
                    <div class="dropdown-toggle"></div>
                    <div class="dropdown-menu">
                        <ul>
                        <?php
                        $terms = get_terms(['taxonomy' => 'pa_ev-tipi', 'hide_empty' => false]);
                        $selected = array_map('sanitize_title', (array) ($_GET['custom_ev_tipi'] ?? []));
                        foreach ($terms as $term) {
                            $isActive = in_array($term->slug, $selected) ? ' active' : '';
                            echo "<li class='dropdown-item{$isActive}' data-value='{$term->slug}'>{$term->name}</li>";
                        }
                        ?>
                        </ul>
                    </div>
                    <div class="selected-tags"></div>
                </div>
                <div id="evtipi-hidden-inputs">
                    <?php foreach ($selected as $slug): ?>
                        <input type="hidden" name="custom_ev_tipi[]" value="<?php echo esc_attr($slug); ?>" data-value="<?php echo esc_attr($slug); ?>">
                    <?php endforeach; ?>
                </div>
            </div>
			<?php endif; ?>

            <!-- Butonlar -->
            <div class="filter-group">
                <button type="submit" class="btn btn-style-default btn-color-primary">Tatil Evi Ara</button>
                <button type="button" id="clear-filters" class="btn btn-style-default btn-color-secondary">Temizle</button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
});


add_action('woocommerce_product_query', 'apply_availability_to_wc_query');
function apply_availability_to_wc_query($q) {
    $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
    $end_date   = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
    $ev_tipi    = array_map('sanitize_title', (array) ($_GET['custom_ev_tipi'] ?? []));
    $lokasyon   = array_map('sanitize_title', (array) ($_GET['custom_lokasyon'] ?? []));

    $tax_query = [];

    if (!empty($ev_tipi)) {
        $tax_query[] = [
            'taxonomy' => 'pa_ev-tipi',
            'field'    => 'slug',
            'terms'    => $ev_tipi,
        ];
    }

    if (!empty($lokasyon)) {
        $tax_query[] = [
            'taxonomy' => 'pa_lokasyon',
            'field'    => 'slug',
            'terms'    => $lokasyon,
        ];
    }

    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $q->set('tax_query', $tax_query);
    }

    // Tarihe göre müsait olmayan ürünleri dışla
    if (!empty($start_date) && !empty($end_date)) {
        global $wpdb;
        $table = $wpdb->prefix . 'booking_availability';

        $booked_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT product_id FROM $table
                 WHERE status = 'booked'
                 AND start_date <= %s
                 AND end_date >= %s",
                $end_date, $start_date
            )
        );

        if (!empty($booked_ids)) {
            $q->set('post__not_in', $booked_ids);
        }
    }

    // Woo default
    if (!$q->get('post_type')) {
        $q->set('post_type', 'product');
    }

    if (!$q->get('post_status')) {
        $q->set('post_status', 'publish');
    }
}
