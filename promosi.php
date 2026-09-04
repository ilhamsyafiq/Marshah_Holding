<?php
/**
 * Promosi & Pakej – dynamic version
 *
 * Renders promo cards from storage/promos.json while keeping the exact
 * nav + footer markup and CSS of the static promosi.html page.
 */

require_once __DIR__ . '/config.php';

$promos = load_json(STORAGE_DIR . '/promos.json');

// Only active promos, ordered by 'order' (fallback to large value)
$promos = array_filter($promos, fn($p) => !empty($p['active']));
uasort($promos, function ($a, $b) {
    return ((int)($a['order'] ?? 999)) <=> ((int)($b['order'] ?? 999));
});

// Inline SVG icons for included / excluded features
$iconCheck = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
$iconCross = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>';
?>
<!doctype html>
<html lang="ms">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Promosi &amp; Pakej – Marshah Holding</title>
<meta name="description" content="Pakej dan promosi istimewa bina rumah atas tanah sendiri.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/nav.css">
<link rel="stylesheet" href="assets/css/footer.css">
<link rel="stylesheet" href="assets/css/promosi.css">
</head>
<body>
<section class="zh-nav">


  <input type="checkbox" id="zh-menu-toggle" class="zh-toggle" hidden>
  <nav class="zh-bar" aria-label="Navigasi utama">

    <a href="index.html" class="zh-logo" aria-label="MARSHAH HOLDING - Halaman Utama">
      <img src="assets/img/logo-marshah.png" alt="Marshah Holding Sdn Bhd" class="zh-logo-img">
    </a>

    <ul class="zh-links">
      <li><a href="index.html">Halaman Utama</a></li>
      <li><a href="promosi.html">Promosi</a></li>
      <li><a href="portfolio.html">Portfolio</a></li>
      <li><a href="rumah-siap.html">Rumah Siap</a></li>
      <li class="zh-has-drop"><a href="profile-syarikat.html" tabindex="0">Tentang Kami
        <svg class="zh-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </a>
        <ul class="zh-drop">
          <li><a href="profile-syarikat.html">Profile Syarikat</a></li>
          <li><a href="pengurusan.html">Pengurusan</a></li>
        </ul>
      </li>
      <li><a href="kalkulator-lppsa.html">Kalkulator</a></li>
      <li><a href="hubungi-kami.html">Hubungi Kami</a></li>
      <span class="zh-nav__cursor" aria-hidden="true"></span>
    </ul>

    <div class="zh-cta">
      <a href="index.html#katalog" aria-label="Muat turun katalog">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        DOWNLOAD KATALOG
      </a>
    </div>

    <label for="zh-menu-toggle" class="zh-burger" aria-label="Buka menu" role="button" tabindex="0">
      <span></span><span></span><span></span>
    </label>

  </nav>
  <script>/*zh-nav-pill*/
  (function(){
    var bar=document.currentScript.closest('.zh-nav'); if(!bar) return;
    var ul=bar.querySelector('.zh-links'), cur=bar.querySelector('.zh-nav__cursor');
    if(!ul) return;
    var path=(location.pathname.split('/').pop()||'index.html'); if(path==='') path='index.html';
    var map={'pengurusan.html':'profile-syarikat.html','portfolio-detail.html':'portfolio.html','promosi.php':'promosi.html'};
    var target=map[path]||path;
    ul.querySelectorAll('li>a').forEach(function(a){ if(a.getAttribute('href')===target) a.parentElement.classList.add('is-active'); });
    if(cur){
      ul.querySelectorAll('li').forEach(function(li){
        li.addEventListener('mouseenter',function(){
          if(window.innerWidth<=960) return;
          cur.style.left=li.offsetLeft+'px'; cur.style.width=li.offsetWidth+'px'; cur.style.opacity='1';
        });
      });
      ul.addEventListener('mouseleave',function(){ cur.style.opacity='0'; });
    }
  })();
  </script>
</section>
<main class="zp-promosi">


  <!-- PAGE HEADER -->

  <!-- INTRO -->
  <section class="zp-intro">
    <div class="zp-wrap">
      <h2>Pakej Istimewa Untuk Anda</h2>
      <p>Pakej istimewa untuk anda memulakan pembinaan rumah impian. Pilih pakej yang paling sesuai dengan bajet dan keperluan keluarga anda &mdash; setiap satu merangkumi pengurusan menyeluruh dari pelan sehingga serahan kunci.</p>
    </div>
  </section>

  <!-- PACKAGES -->
  <section class="zp-pkgs">
    <div class="zp-wrap">
      <div class="zp-grid">

        <?php if (empty($promos)): ?>
          <p style="text-align:center;width:100%;color:#667;">Tiada pakej tersedia buat masa ini.</p>
        <?php else: ?>
          <?php foreach ($promos as $p): ?>
            <?php
              $featured = !empty($p['featured']);
              $badge    = trim($p['badge'] ?? '');
              $ctaUrl   = $p['cta_url'] ?? 'hubungi-kami.html';
              $btnStyle = $featured ? 'zp-btn-primary' : 'zp-btn-outline';
            ?>
            <div class="zp-card<?= $featured ? ' featured' : '' ?>">
              <?php if ($badge !== ''): ?>
                <div class="zp-badge"><?= htmlspecialchars($badge) ?></div>
              <?php endif; ?>
              <div class="zp-pkg-name"><?= htmlspecialchars($p['name'] ?? '') ?></div>
              <p class="zp-pkg-desc"><?= htmlspecialchars($p['desc'] ?? '') ?></p>
              <div class="zp-price">
                <span class="zp-from">Bermula dari</span>
                <span class="zp-amt"><?= htmlspecialchars($p['price'] ?? '') ?></span>
                <span class="zp-unit">&nbsp;/ unit</span>
              </div>
              <div class="zp-divider"></div>
              <ul class="zp-feats">
                <?php foreach (($p['features'] ?? []) as $f): ?>
                  <?php $on = !empty($f['included']); ?>
                  <li class="<?= $on ? 'on' : 'off' ?>"><?= $on ? $iconCheck : $iconCross ?><?= htmlspecialchars($f['text'] ?? '') ?></li>
                <?php endforeach; ?>
              </ul>
              <a href="<?= htmlspecialchars($ctaUrl) ?>" class="zp-btn <?= $btnStyle ?> zp-btn-block">Pilih Pakej</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- PROMO BANNER -->
  <section class="zp-promo">
    <div class="zp-promo-inner">
      <span class="zp-promo-tag">Promosi Rumah Raya Penuh Gaya 2025</span>
      <h2>Rebat Sehingga <span>RM10,000</span><br>Untuk 20 Pelanggan Terawal</h2>
      <p>Daftar sekarang dan nikmati rebat eksklusif untuk pembinaan rumah impian anda menjelang Hari Raya. Slot terhad &mdash; jangan lepaskan peluang ini!</p>

      <img src="assets/img/promo-raya-2025.jpg" alt="Promosi Rumah Raya Penuh Gaya 2025 - Marshah Holding" style="display:block;max-width:560px;width:100%;height:auto;margin:0 auto 30px;border-radius:var(--radius);box-shadow:0 14px 34px rgba(74,11,107,.28)">


      <div class="zp-count" aria-label="Kiraan detik tawaran">
        <div class="zp-box"><span class="zp-num">28</span><span class="zp-lbl">Hari</span></div>
        <div class="zp-box"><span class="zp-num">14</span><span class="zp-lbl">Jam</span></div>
        <div class="zp-box"><span class="zp-num">52</span><span class="zp-lbl">Minit</span></div>
        <div class="zp-box"><span class="zp-num">09</span><span class="zp-lbl">Saat</span></div>
      </div>

      <a href="https://wa.me/6097678466?text=Hai%20saya%20berminat%20dengan%20Promosi%20Rumah%20Raya%20Penuh%20Gaya%20MARSHAH%20HOME" class="zp-wa" id="hubungi">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.15-1.7-.85-2-.95-.26-.1-.45-.15-.64.15-.19.28-.73.94-.9 1.13-.16.19-.33.21-.62.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.08-.15-.64-1.55-.88-2.12-.23-.55-.47-.48-.64-.49l-.55-.01c-.19 0-.5.07-.76.36-.26.29-1 .98-1 2.38s1.02 2.76 1.17 2.95c.14.19 2 3.05 4.85 4.28.68.29 1.2.47 1.61.6.68.21 1.29.18 1.78.11.54-.08 1.66-.68 1.9-1.33.23-.66.23-1.22.16-1.34-.07-.12-.26-.19-.55-.34z"/><path d="M12 2a10 10 0 0 0-8.5 15.28L2 22l4.85-1.27A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.1.81.83-3.02-.2-.31A8.2 8.2 0 1 1 12 20.2z"/></svg>
        Hubungi Kami di WhatsApp
      </a>
      <p class="zp-promo-note">*Tertakluk kepada terma &amp; syarat. Rebat sah untuk 20 pelanggan terawal sahaja.</p>
    </div>
  </section>
</main>
<section class="zh-footer">

  <!-- MAIN -->
  <div class="zh-main">
    <div class="zh-inner">
      <div class="zh-grid">

        <!-- COL 1: LOKASI -->
        <div class="zh-col">
          <div class="zh-logo">
            <img src="assets/img/logo-marshah-white.png" alt="Marshah Holding Sdn Bhd" class="zh-foot-logo-img">
          </div>
          <h4>Lokasi</h4>
          <div class="zh-address">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            <span>822, Jalan KK 2/4, Kubang Kerian, 16150 Kota Bharu, Kelantan.</span>
          </div>
        </div>

        <!-- COL 2: TENTANG KAMI -->
        <div class="zh-col">
          <h4>Navigasi</h4>
          <ul>
            <li><a href="index.html">Halaman Utama</a></li>
            <li><a href="promosi.html">Promosi</a></li>
            <li><a href="portfolio.html">Portfolio</a></li>
            <li><a href="rumah-siap.html">Rumah Siap</a></li>
          </ul>
        </div>

        <!-- COL 3: LAIN-LAIN -->
        <div class="zh-col">
          <h4>Tentang Kami</h4>
          <ul>
            <li><a href="profile-syarikat.html">Profile Syarikat</a></li>
            <li><a href="pengurusan.html">Pengurusan</a></li>
            <li><a href="kalkulator-lppsa.html">Kalkulator</a></li>
            <li><a href="hubungi-kami.html">Hubungi Kami</a></li>
          </ul>
        </div>

                <!-- COL 4: PETA LOKASI -->
        <div class="zh-col zh-col--map">
          <h4>Peta Lokasi</h4>
          <div class="zh-map">
            <iframe class="zh-map__frame" title="Peta lokasi Marshah Holding" src="https://www.google.com/maps?q=822%20Jalan%20KK%202%2F4%20Kubang%20Kerian%2016150%20Kota%20Bharu%20Kelantan&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <a class="zh-map__link" href="https://www.google.com/maps/search/?api=1&query=822%20Jalan%20KK%202%2F4%20Kubang%20Kerian%2016150%20Kota%20Bharu%20Kelantan" target="_blank" rel="noopener" aria-label="Buka lokasi Marshah Holding di Google Maps">
              <span class="zh-map__badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>Buka di Google Maps</span>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- BOTTOM BAR -->
  <div class="zh-bottom">
    <div class="zh-inner">
      <div class="zh-copy">&copy; 2026 Marshah Holding Sdn Bhd (XXXXXX-X). Hak Cipta Terpelihara.</div>
      <div class="zh-foot-social">
        <a href="#" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.5 3c.4 2.3 1.7 3.9 4 4.2v2.6c-1.4.1-2.7-.3-4-1v6.1c0 3.7-2.8 6.1-6.2 5.9C6.9 20.6 4.8 18 5 15c.2-2.7 2.4-4.7 5.1-4.7.3 0 .6 0 .9.1v2.8c-.3-.1-.6-.2-1-.2-1.3 0-2.4 1.1-2.3 2.5.1 1.2 1.1 2.2 2.3 2.2 1.4 0 2.5-1.1 2.5-2.5V3h2.9Z"/></svg></a>
        <a href="https://www.facebook.com/MarshahHoldingSdnBhd" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.5V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.5 2.9h-2.3v7A10 10 0 0 0 22 12Z"/></svg></a>
        <a href="#" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
      </div>
      <div class="zh-bottom-links">
        <a href="#privasi">Dasar Privasi</a>
        <a href="#terma">Terma &amp; Syarat</a>
      </div>
    </div>
  </div>
</section>
</body>
</html>
