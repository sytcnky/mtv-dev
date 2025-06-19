add_shortcode('villa_rozetleri', function () {
    if (!is_product()) return '';

    global $post;
    $terms = get_the_terms($post->ID, 'pa_ev-tipi');

    if (empty($terms) || is_wp_error($terms)) return '';

    // Flaticon ikon eşlemesi
    $badge_map = get_villa_badge_map();

    $output = '<div class="villa-rozetleri-wrapper" style="display:flex; flex-wrap:wrap; gap:20px;">';

    foreach ($terms as $term) {
        $slug = $term->slug;
        if (isset($badge_map[$slug])) {
            $icon_class = esc_attr($badge_map[$slug]['icon']);
            $label = esc_html($badge_map[$slug]['label']);

            $output .= '<div class="villa-rozet" style="flex:1; min-width:100px; text-align:center;">';
            $output .= '<i class="' . $icon_class . '" style="font-size:32px; display:block; margin:0 auto;"></i>';
            $output .= '<div style="margin-top:5px; font-size:14px;">' . $label . '</div>';
            $output .= '</div>';
        }
    }

    $output .= '</div>';
    return $output;
});
