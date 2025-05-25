# Marmaris Tatil Evleri - WPCode Geliştirme Altyapısı

Bu repo, MarmarisTatilEvleri.com sitesinde kullanılan özel WordPress fonksiyonlarını, WPCode snippet'lerini ve geliştirmeye açık PHP/JS parçacıklarını merkezi olarak yönetmek için oluşturulmuştur.

## 🎯 Amaç

- Tüm WPCode snippet’lerini tek merkezde takip etmek
- Frontend ve backend işlevlerini açıkça ayırmak
- WooCommerce rezervasyon sistemine özel özelleştirmeleri modüler hale getirmek
- Geliştirme sırasında bağımlılık ve bağlam ilişkilerini analiz etmek

## ⚙️ Sistem Gereksinimleri

| Bileşen        | Versiyon  |
|----------------|-----------|
| WordPress      | 6.8.1     |
| WooCommerce    | 9.8.5     |
| Woodmart Tema  | 8.1.2     |
| Woodmart Core  | 1.1.1     |

## 📁 Yapı

Tüm snippet’ler `snippets/` klasörü altında mantıksal olarak bölünmüştür. Her dosya bir WPCode snippet’ini temsil eder.

```
snippets/
├── villa-map-archive.php
├── woocommerce-cart-price-override.php
├── urun-ozellikleri.php
├── cart-booking-metadata.php
└── ...
```

## 🔐 Gizli Bilgiler

Hassas bilgiler (`API key`, `payment secret`, `webhook` vb.) ayrı bir dosyada tanımlanmalı:

- `config-private.php` dosyası içinde `define(...)` bloklarıyla ayarlanır.
- Ana snippet’lerde bu sabitler `defined(...) ? SABIT : ''` mantığıyla çağrılır.
- Bu dosya `.gitignore` ile dışlanmıştır.

## 🛠 Kurulum (geliştirici için)

1. Bu repoyu klonla:
   ```bash
   git clone https://github.com/sytcnky/mtv-dev.git
   ```

2. WordPress kurulumunda gerekli dizine yerleştir

3. WPCode eklentisi aktif olmalı

4. `config-private.php` dosyasını manuel olarak ekle

## 🧩 Katkı / Geliştirme

Kodlar sürekli geliştirilmekte olup:
- Kod tekrarları
- İşlev bağımlılıkları
- Kod standardı dışı kullanımlar
- Performans önerileri

düzenli olarak bu repo üzerinden analiz edilmekte ve iyileştirilmektedir.

---

Güncel geliştirici: [Seyit Çankaya](https://github.com/sytcnky)
