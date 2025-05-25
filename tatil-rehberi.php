add_shortcode('villa_guide', 'villa_guide_html_block');
function villa_guide_html_block() {
    ob_start();
    ?>
    <div class="villa-guide-box">
      <div class="guide-photo">
        <img src="/wp-content/uploads/2025/05/wood-testimon-4.jpg" alt="Tatil Rehberi" />
      </div>
      <div class="guide-info">
        <div class="guide-name">Zeynep K. <span class="guide-title">Tatil Rehberi</span></div>
        <div class="guide-contact">
          <a href="https://wa.me/905321112233" target="_blank" class="guide-whatsapp">
            <img src="http://www.marmaristatilevleri.com/wp-content/uploads/2025/05/whatsapp.png" alt="WhatsApp" class="whatsapp-icon" />
            +90 532 111 22 33
          </a>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}


add_action('woocommerce_single_product_summary', function () {
    echo do_shortcode('[villa_guide]');
}, 11); // fiyat altına denk gelir

