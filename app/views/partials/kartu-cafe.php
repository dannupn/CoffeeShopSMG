<?php
defined('APP_JALAN') or exit;
/** Kartu ringkas satu coffee shop. Variabel: $c (baris cafe), $b (badge cafe), $skorCafe (opsional) */
?>
<article class="kartu kartu-cafe">
    <div class="kartu-cafe-atas">
        <h3 class="kartu-cafe-nama"><a href="<?= url('cafe/' . $c['id_cafe']) ?>"><?= e($c['nama_cafe']) ?></a></h3>
        <?php if (isset($skorCafe)): ?>
            <span class="skor-kecil" title="Cosine similarity <?= angka($skorCafe, 4) ?>"><?= persen($skorCafe, 2) ?></span>
        <?php endif; ?>
    </div>
    <p class="info">
        <span><?= e($c['harga_indikasi']) ?></span>
        <span><span class="bintang" aria-hidden="true">&#9733;</span> <?= $c['rating_rata2'] !== null ? angka($c['rating_rata2'], 1) : '-' ?></span>
        <span><?= angka($c['jumlah_ulasan_total']) ?> ulasan</span>
    </p>
    <div class="daftar-badge">
        <?php foreach (array_keys(ASPEK) as $a): ?>
            <?= htmlBadge($b[$a] ?? null) ?>
        <?php endforeach; ?>
    </div>
</article>