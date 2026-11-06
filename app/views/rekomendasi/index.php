<?php defined('APP_JALAN') or exit; ?>
<section class="isi">
    <a class="kembali" href="<?= url() ?>">Ubah preferensi</a>
    <h1 class="judul"><?= count($skor) ?> coffee shop paling cocok untukmu</h1>
    <ul class="ringkasan-preferensi" aria-label="Preferensi yang dipilih">
        <li>Budget <?= e(strtolower(BUDGET[$budget][0])) ?>, <?= e(BUDGET[$budget][1]) ?></li>
        <?php foreach ($prioritas as $p): ?>
            <li><?= e(LABEL_PRIORITAS[$p]) ?></li>
        <?php endforeach; ?>
        <?php if (!$prioritas): ?>
            <li>Semua aspek sama penting</li>
        <?php endif; ?>
    </ul>

    <?php if (!$skor): ?>
        <p class="kosong">Belum ada coffee shop yang cocok. Coba pilih budget lain.</p>
    <?php else: ?>
        <ol class="daftar-hasil">
            <?php $no = 1; foreach ($skor as $id => $nilai): $c = $cafe[$id]; ?>
                <li class="kartu hasil" data-id="<?= $id ?>">
                    <button class="ubin-peringkat" type="button" data-fokus="<?= $id ?>"
                            aria-label="Tampilkan <?= e($c['nama_cafe']) ?> di peta"><?= $no++ ?></button>
                    <div class="hasil-isi">
                        <div class="hasil-atas">
                            <h2 class="hasil-nama"><a href="<?= url('cafe/' . $id) ?>"><?= e($c['nama_cafe']) ?></a></h2>
                            <span class="skor" title="Nilai cosine similarity <?= angka($nilai, 4) ?>">
                                <span class="skor-angka"><?= persen($nilai) ?></span>
                                <span class="skor-label">cocok</span>
                            </span>
                        </div>
                        <div class="meter" aria-hidden="true"><span style="width: <?= round($nilai * 100, 1) ?>%"></span></div>
                        <p class="info">
                            <span><?= e($c['harga_indikasi']) ?></span>
                            <span><span class="bintang" aria-hidden="true">&#9733;</span> <?= $c['rating_rata2'] !== null ? angka($c['rating_rata2'], 1) : '-' ?></span>
                            <span><?= e(teksJam($c['jam_operasional'])) ?></span>
                        </p>
                        <div class="daftar-badge">
                            <?php foreach (array_keys(ASPEK) as $a): ?>
                                <?= htmlBadge($badge[$id][$a] ?? null) ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="hasil-aksi">
                            <a href="<?= url('cafe/' . $id) ?>">Lihat detail</a>
                            <a href="<?= e(urlArah($c['latitude'], $c['longitude'])) ?>" target="_blank" rel="noopener">Petunjuk arah</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php require ROOT . '/app/views/partials/legenda-badge.php'; ?>

    <details class="kotak-info">
        <summary>Bagaimana tingkat kecocokan dihitung?</summary>
        <p>
            Preferensimu diubah menjadi vektor fitur. Budget dan aspek yang kamu pilih diberi nilai TF 2,
            aspek positif lainnya diberi nilai TF 1, lalu dikalikan nilai IDF dan dinormalisasi.
            Tingkat kecocokan adalah nilai <i>cosine similarity</i> antara vektor ini dan vektor fitur setiap coffee shop,
            yang dibentuk dari kategori harga dan hasil analisis sentimen ulasan.
        </p>
        <div class="tabel-gulir">
            <table class="tabel">
                <thead><tr><th scope="col">Fitur</th><th scope="col">TF</th><th scope="col">IDF</th><th scope="col">Bobot</th></tr></thead>
                <tbody>
                <?php foreach ($vektor as $f => $v): ?>
                    <tr>
                        <td><code><?= e($f) ?></code></td>
                        <td><?= angka($v['tf']) ?></td>
                        <td><?= angka($v['idf'], 4) ?></td>
                        <td><?= angka($v['bobot_normal'], 4) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </details>
</section>