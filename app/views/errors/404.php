<?php defined('APP_JALAN') or exit; ?>
<section class="isi halaman-galat">
    <p class="kode-galat">404</p>
    <h1 class="judul">Halaman tidak ditemukan</h1>
    <p class="pengantar">Alamat yang dibuka tidak mengarah ke halaman atau coffee shop yang terdata. Mungkin tautannya salah ketik atau sudah berubah.</p>
    <form class="cari-cepat" action="<?= url('cafe') ?>" method="get" role="search">
        <label for="cari-404">Cari coffee shop</label>
        <div class="kolom-cari">
            <input type="search" id="cari-404" name="q" placeholder="Nama coffee shop" maxlength="60">
            <button class="tombol" type="submit">Cari</button>
        </div>
    </form>
    <div class="baris-tombol">
        <a class="tombol tombol-garis" href="<?= url() ?>">Cari rekomendasi</a>
        <a class="tombol tombol-garis" href="<?= url('cafe') ?>">Jelajahi semua</a>
    </div>
</section>