<?php
defined('APP_JALAN') or exit;
$totalCafe = $ringkasan['cafe'];
$maksKategori = max($perKategori) ?: 1;
?>
<section class="isi">
    <h1 class="judul">Statistik ulasan</h1>
    <p class="pengantar">Gambaran hasil analisis sentimen berbasis aspek terhadap seluruh ulasan coffee shop di Kota Semarang.</p>

    <dl class="angka-ringkas angka-ringkas-4">
        <div><dt>Coffee shop</dt><dd><?= angka($ringkasan['cafe']) ?></dd></div>
        <div><dt>Ulasan</dt><dd><?= angka($ringkasan['ulasan']) ?></dd></div>
        <div><dt>Kalimat beraspek</dt><dd><?= angka($ringkasan['kalimat']) ?></dd></div>
        <div><dt>Punya label</dt><dd><?= angka($ringkasan['berbadge']) ?></dd></div>
    </dl>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Sentimen kalimat per aspek</h2>
        <ul class="kunci-warna" aria-hidden="true">
            <li><span class="titik titik-positif"></span>Positif</li>
            <li><span class="titik titik-negatif"></span>Negatif</li>
            <li><span class="titik titik-netral"></span>Netral</li>
        </ul>
        <?php foreach (ASPEK as $a => $nama):
            $d = $distribusi[$a];
            $total = array_sum($d) ?: 1;
            ?>
            <div class="baris-statistik">
                <div class="baris-statistik-kepala">
                    <span><?= e($nama) ?></span>
                    <span class="redup"><?= angka(array_sum($d)) ?> kalimat</span>
                </div>
                <div class="batang batang-tebal" role="img"
                     aria-label="<?= e($nama) ?>: <?= $d['positif'] ?> positif, <?= $d['negatif'] ?> negatif, <?= $d['netral'] ?> netral">
                    <?php foreach (['positif', 'negatif', 'netral'] as $s): ?>
                        <span class="batang-<?= $s ?>" style="width: <?= 100 * $d[$s] / $total ?>%"></span>
                    <?php endforeach; ?>
                </div>
                <p class="baris-statistik-ket">
                    <?= persen($d['positif'] / $total) ?> positif, <?= persen($d['negatif'] / $total) ?> negatif, <?= persen($d['netral'] / $total) ?> netral
                </p>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Label coffee shop per aspek</h2>
        <ul class="kunci-warna" aria-hidden="true">
            <li><span class="titik titik-positif"></span>Label positif</li>
            <li><span class="titik titik-negatif"></span>Label negatif</li>
            <li><span class="titik titik-netral"></span>Tanpa label</li>
            <li><span class="titik titik-kosong"></span>Belum dibahas</li>
        </ul>
        <?php foreach (ASPEK as $a => $nama):
            $p = $perBadge[$a];
            $belum = max(0, $totalCafe - array_sum($p));
            ?>
            <div class="baris-statistik">
                <div class="baris-statistik-kepala">
                    <span><?= e($nama) ?></span>
                    <span class="redup"><?= angka($p['positif']) ?> <?= e(TEKS_BADGE[$a][0]) ?></span>
                </div>
                <div class="batang batang-tebal" role="img"
                     aria-label="<?= e($nama) ?>: <?= $p['positif'] ?> label positif, <?= $p['negatif'] ?> label negatif, <?= $p['tanpa'] ?> tanpa label, <?= $belum ?> belum dibahas">
                    <span class="batang-positif" style="width: <?= 100 * $p['positif'] / $totalCafe ?>%"></span>
                    <span class="batang-negatif" style="width: <?= 100 * $p['negatif'] / $totalCafe ?>%"></span>
                    <span class="batang-netral" style="width: <?= 100 * $p['tanpa'] / $totalCafe ?>%"></span>
                </div>
                <p class="baris-statistik-ket">
                    <?= angka($p['negatif']) ?> <?= e(strtolower(TEKS_BADGE[$a][1])) ?>, <?= angka($p['tanpa']) ?> tanpa label, <?= angka($belum) ?> belum dibahas
                </p>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Sebaran kategori harga</h2>
        <?php foreach (KATEGORI_HARGA as $k => $nama): ?>
            <div class="baris-horizontal">
                <span class="baris-horizontal-label"><?= e($nama) ?></span>
                <span class="baris-horizontal-batang"><span style="width: <?= 100 * $perKategori[$k] / $maksKategori ?>%"></span></span>
                <span class="baris-horizontal-angka"><?= angka($perKategori[$k]) ?></span>
            </div>
        <?php endforeach; ?>
        <p class="bantuan">Kategori dari indikasi harga per orang di Google Maps. Coffee shop tanpa indikasi harga tidak memiliki fitur budget.</p>
    </section>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Paling disukai per aspek</h2>
        <p class="bantuan">Persentase kalimat positif tertinggi, minimal 30 kalimat ulasan pada aspek tersebut.</p>
        <div class="grid-teratas">
            <?php foreach (ASPEK as $a => $nama): ?>
                <div>
                    <h3 class="teratas-judul"><?= e($nama) ?></h3>
                    <?php if (!$teratas[$a]): ?>
                        <p class="redup">Belum ada yang memenuhi syarat.</p>
                    <?php else: ?>
                        <ol class="teratas">
                            <?php foreach ($teratas[$a] as $t): ?>
                                <li>
                                    <a href="<?= url('cafe/' . $t['id_cafe']) ?>"><?= e($t['nama_cafe']) ?></a>
                                    <span class="redup"><?= angka($t['persen_positif'], 1) ?>% dari <?= angka($t['n_total']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</section>