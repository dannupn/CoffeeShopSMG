<?php
defined('APP_JALAN') or exit;
// tautan halaman dengan filter yang sama
$urlHalaman = fn(int $n) => url('cafe', array_filter($filter + ['halaman' => $n > 1 ? $n : ''],
    fn($v, $k) => $v !== '' && !($k === 'urut' && $v === 'rating'), ARRAY_FILTER_USE_BOTH));
?>
<section class="isi">
    <h1 class="judul">Jelajahi coffee shop</h1>
    <p class="pengantar">Cari berdasarkan nama atau alamat, lalu saring berdasarkan budget dan label ulasan.</p>

    <form class="kartu form-saring" action="<?= url('cafe') ?>" method="get" role="search">
        <label class="sr-only" for="q">Nama atau alamat</label>
        <div class="kolom-cari">
            <input type="search" id="q" name="q" value="<?= e($filter['q']) ?>" placeholder="Nama atau alamat coffee shop" maxlength="60">
            <button class="tombol" type="submit">Cari</button>
        </div>
        <div class="baris-saring">
            <label>
                <span>Budget</span>
                <select name="budget" data-kirim-otomatis>
                    <option value="">Semua</option>
                    <?php foreach (KATEGORI_HARGA as $k => $nama): ?>
                        <option value="<?= e($k) ?>"<?= $filter['budget'] === $k ? ' selected' : '' ?>><?= e($nama) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Label</span>
                <select name="aspek" data-kirim-otomatis>
                    <option value="">Semua</option>
                    <?php foreach (TEKS_BADGE as $a => [$positif]): ?>
                        <option value="<?= e($a) ?>"<?= $filter['aspek'] === $a ? ' selected' : '' ?>><?= e($positif) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Urutkan</span>
                <select name="urut" data-kirim-otomatis>
                    <option value="rating"<?= $filter['urut'] === 'rating' ? ' selected' : '' ?>>Rating</option>
                    <option value="ulasan"<?= $filter['urut'] === 'ulasan' ? ' selected' : '' ?>>Ulasan</option>
                    <option value="nama"<?= $filter['urut'] === 'nama' ? ' selected' : '' ?>>Nama</option>
                </select>
            </label>
        </div>
    </form>

    <div class="baris-jumlah">
        <p><strong><?= angka($total) ?></strong> coffee shop<?= $adaFilter ? ' cocok' : '' ?></p>
        <?php if ($adaFilter): ?>
            <a href="<?= url('cafe') ?>">Hapus filter</a>
        <?php endif; ?>
    </div>

    <?php if (!$daftar): ?>
        <p class="kosong">Tidak ada coffee shop yang cocok. Coba kata kunci lain atau longgarkan filternya.</p>
    <?php else: ?>
        <div class="tumpukan">
            <?php foreach ($daftar as $id => $c):
                $b = $badge[$id] ?? [];
                unset($skorCafe);
                require ROOT . '/app/views/partials/kartu-cafe.php';
            endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($jumlahHalaman > 1): ?>
        <nav class="paginasi" aria-label="Halaman hasil">
            <?php if ($halaman > 1): ?>
                <a class="tombol tombol-garis" href="<?= e($urlHalaman($halaman - 1)) ?>" rel="prev">Sebelumnya</a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>
            <span>Halaman <?= $halaman ?> dari <?= $jumlahHalaman ?></span>
            <?php if ($halaman < $jumlahHalaman): ?>
                <a class="tombol tombol-garis" href="<?= e($urlHalaman($halaman + 1)) ?>" rel="next">Berikutnya</a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

    <?php require ROOT . '/app/views/partials/legenda-badge.php'; ?>
</section>