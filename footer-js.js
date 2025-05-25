document.addEventListener('DOMContentLoaded', function () {
  // Ürün sayfasında fiyata "/ Gece" ekle
  const priceEl = document.querySelector('p.price');
  if (priceEl && !priceEl.innerHTML.includes('/ Gece')) {
    const suffix = document.createElement('span');
    suffix.className = 'per-night';
    suffix.textContent = ' / Gece';
    priceEl.appendChild(suffix);
  }

  // Mini cart içine villa fiyat bilgisi ekle
  function injectVillaPricing() {
    document.querySelectorAll('.woocommerce-mini-cart-item').forEach(function (item) {
      const nameEl = item.querySelector('.woocommerce-mini-cart-item__name');
      if (!nameEl || !nameEl.textContent.includes('Bozburun Ev')) return;
      if (item.querySelector('.villa-mini-cart-pricing')) return;

      const gecelikFiyat = '3.000₺';
      const gunSayisi = 7;
      const toplam = '21.000₺';
      const kalan = '15.750₺';

      const html = `
        <div class="villa-mini-cart-pricing" style="font-size:13px; margin-top:5px;">
          ${gecelikFiyat} x ${gunSayisi} Gece<br>
          Toplam Tutar: ${toplam}<br>
          Kalan Tutar: ${kalan}<br>
          <small>(Konaklama sırasında ödenecek)</small>
        </div>
      `;

      nameEl.insertAdjacentHTML('afterend', html);
    });
  }

  // DOM izleyici: Mini-cart açıldığında ya da ürün sepete eklendiğinde çalışsın
  const observer = new MutationObserver(() => {
    const cart = document.querySelector('.woocommerce-mini-cart');
    if (cart && cart.offsetHeight > 0) {
      injectVillaPricing();
    }
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true
  });

  // Ek garanti: sepete ürün eklenince kısa süre sonra çalıştır
  document.body.addEventListener('added_to_cart', () => {
    setTimeout(injectVillaPricing, 700);
  });
});
