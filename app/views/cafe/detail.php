<?php defined('APP_JALAN') or exit; ?>
<section class="isi">
    <a class="kembali" href="<?= url() ?>" onclick="if (history.length > 1) { history.back(); return false; }">Kembali</a>
    <h1 class="judul"><?= e($cafe['nama_cafe']) ?></h1>
    <p class="alamat"><?= e($cafe['alamat']) ?></p>

    <dl class="fakta">
        <div><dt>Harga</dt><dd><?= e($cafe['harga_indikasi']) ?></dd></div>
        <div><dt>Rating</dt><dd><span class="bintang" aria-hidden="true">&#9733;</span> <?= $cafe['rating_rata2'] !== null ? angka($cafe['rating_rata2'], 1) : '-' ?></dd></div>
        <div><dt>Jam buka</dt><dd><?= e($cafe['jam_operasional']) ?></dd></div>
        <div><dt>Ulasan dianalisis</dt><dd><?= angka($cafe['jumlah_ulasan_terambil']) ?></dd></div>
    </dl>
    <div class="baris-tombol">
        <a class="tombol" href="<?= e(urlArah($cafe['latitude'], $cafe['longitude'])) ?>" target="_blank" rel="noopener">Petunjuk arah</a>
        <a class="tombol tombol-garis" href="<?= e($cafe['link']) ?>" target="_blank" rel="noopener">Buka di Google Maps</a>
    </div>

    <h2 class="subjudul">Kata pengunjung per aspek</h2>
    <?php foreach (ASPEK as $a => $namaAspek):
        $b = $badge[$a] ?? null;
        $total = $b ? (int) $b['n_total'] : 0;
        ?>
        <article class="kartu aspek">
            <div class="aspek-kepala">
                <h3><?= e($namaAspek) ?></h3>
                <?= htmlBadge($b) ?>
            </div>
            <?php if ($total === 0): ?>
                <p class="aspek-ket">Belum ada ulasan yang membahas <?= e(strtolower($namaAspek)) ?>.</p>
            <?php else: ?>
                <div class="batang" role="img"
                     aria-label="<?= $b['n_positif'] ?> positif, <?= $b['n_negatif'] ?> negatif, <?= $b['n_netral'] ?> netral">
                    <span class="batang-positif" style="width: <?= 100 * $b['n_positif'] / $total ?>%"></span>
                    <span class="batang-negatif" style="width: <?= 100 * $b['n_negatif'] / $total ?>%"></span>
                    <span class="batang-netral" style="width: <?= 100 * $b['n_netral'] / $total ?>%"></span>
                </div>
                <p class="aspek-ket">
                    <?= angka($b['n_positif']) ?> positif, <?= angka($b['n_negatif']) ?> negatif, dan <?= angka($b['n_netral']) ?> netral
                    dari <?= angka($total) ?> kalimat ulasan.
                    <?php if ($b['badge'] === '' && $total < MIN_KALIMAT_BADGE): ?>
                        Belum cukup ulasan untuk diberi label.
                    <?php elseif ($b['badge'] === ''): ?>
                        Pendapat pengunjung masih berimbang atau sebagian besar netral.
                    <?php endif; ?>
                </p>
                <?php foreach (['positif', 'negatif'] as $s): ?>
                    <?php foreach ($contoh[$a][$s] as $kalimat): ?>
                        <blockquote class="kutipan kutipan-<?= $s ?>"><?= e($kalimat) ?></blockquote>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>

    <?php require ROOT . '/app/views/partials/legenda-badge.php'; ?>

    <h2 class="subjudul">Coffee shop serupa</h2>
    <p class="bantuan">
        Dihitung dengan <i>cosine similarity</i> antara vektor fitur coffee shop ini dan vektor fitur coffee shop lain.
        Banyak coffee shop memiliki kategori harga dan label yang sama, sehingga selisih kemiripannya kecil.
    </p>
    <?php if (!$serupa): ?>
        <p class="kosong">Belum ada data fitur untuk mencari coffee shop serupa.</p>
    <?php else: ?>
        <div class="tumpukan">
            <?php foreach ($serupa as $idSerupa => $skorCafe):
                $c = $cafeSerupa[$idSerupa];
                $b = $badgeSerupa[$idSerupa] ?? [];
                require ROOT . '/app/views/partials/kartu-cafe.php';
            endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($vektor): ?>
        <details class="kotak-info">
            <summary>Vektor fitur coffee shop ini</summary>
            <p>Fitur dibentuk dari kategori harga dan proporsi kalimat ulasan positif atau negatif per aspek, lalu diberi bobot TF-IDF dan dinormalisasi.</p>
            <div class="tabel-gulir">
                <table class="tabel">
                    <thead><tr><th scope="col">Fitur</th><th scope="col">TF</th><th scope="col">IDF</th><th scope="col">Bobot</th></tr></thead>
                    <tbody>
                    <?php foreach ($vektor as $f => $v): ?>
                        <tr>
                            <td><code><?= e($f) ?></code></td>
                            <td><?= angka($v['tf'], 4) ?></td>
                            <td><?= angka($v['idf'], 4) ?></td>
                            <td><?= angka($v['bobot_normal'], 4) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </details>
    <?php endif; ?>
</section>