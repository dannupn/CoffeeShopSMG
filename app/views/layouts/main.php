<?php
defined('APP_JALAN') or exit;
/** @var string $konten  isi halaman dari view */
$judulHalaman = $judulHalaman ?? NAMA_APLIKASI;
$dataPeta = ($dataPeta ?? []) + ['baseUrl' => BASE_URL, 'gaya' => GAYA_PETA];
$ikonCangkir = '<path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/>'
    . '<path d="M6 2v2M10 2v2M14 2v2"/>';
$favicon = 'data:image/svg+xml,' . rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" rx="8" fill="#134E4A"/>'
    . '<g transform="translate(4 4)" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">'
    . $ikonCangkir . '</g></svg>'
);
?>
<!doctype html>
<html lang="id" data-tema="terang">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judulHalaman) ?> | <?= e(NAMA_APLIKASI) ?></title>
    <meta name="description" content="Rekomendasi coffee shop di Kota Semarang berdasarkan budget dan analisis sentimen ulasan pengunjung.">
    <script>
        // terapkan tema sebelum halaman tampil agar tidak berkedip
        (function () {
            var t; try { t = localStorage.getItem('tema'); } catch (e) {}
            if (t !== 'gelap' && t !== 'terang') t = matchMedia('(prefers-color-scheme: dark)').matches ? 'gelap' : 'terang';
            document.documentElement.dataset.tema = t;
        })();
    </script>
    <link rel="icon" href="<?= $favicon ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500..700&family=Plus+Jakarta+Sans:wght@400..800&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@5.24.0/dist/maplibre-gl.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<a class="lewati" href="#konten">Lewati ke konten</a>
<div class="aplikasi">
    <div class="panel">
        <header class="kepala">
            <div class="kepala-atas">
                <a class="merek" href="<?= url() ?>">
                    <span class="logo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $ikonCangkir ?></svg>
                    </span>
                    <span class="merek-teks">
                        <span class="merek-nama"><?= e(NAMA_APLIKASI) ?></span>
                        <span class="merek-kota">Coffee shop di Semarang</span>
                    </span>
                </a>
                <button class="tombol-tema" type="button" data-ganti-tema aria-label="Ganti ke mode gelap">
                    <svg class="ikon-bulan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
                    <svg class="ikon-matahari" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                </button>
            </div>
            <nav class="navigasi" aria-label="Menu utama">
                <a href="<?= url() ?>"<?= navAktif('/') ?>>Rekomendasi</a>
                <a href="<?= url('cafe') ?>"<?= navAktif('/cafe') ?>>Jelajahi</a>
                <a href="<?= url('statistik') ?>"<?= navAktif('/statistik') ?>>Statistik</a>
                <a href="<?= url('tentang') ?>"<?= navAktif('/tentang') ?>>Tentang</a>
            </nav>
        </header>
        <main id="konten" class="konten">
            <?= $konten ?>
        </main>
        <footer class="kaki">
            Data ulasan Google Maps, diolah dengan <i>Content-Based Filtering</i> dan analisis sentimen berbasis aspek.
        </footer>
    </div>
    <div class="area-peta">
        <div id="peta" role="region" aria-label="Peta sebaran coffee shop"></div>
        <div class="peta-muat" id="peta-muat" role="status"><span class="pemutar" aria-hidden="true"></span><span class="peta-muat-teks">Memuat peta</span></div>
    </div>
</div>
<script src="https://unpkg.com/maplibre-gl@5.24.0/dist/maplibre-gl.js"></script>
<script>window.DATA_PETA = <?= json_encode($dataPeta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
<script src="<?= asset('js/app.js') ?>"></script>
<script src="<?= asset('js/peta.js') ?>"></script>
</body>
</html>