<?php defined('APP_JALAN') or exit; ?>
<section class="isi">
    <p class="label-atas">Rekomendasi berbasis ulasan pengunjung</p>
    <h1 class="judul">Temukan coffee shop yang pas untukmu</h1>
    <p class="pengantar">
        Pilih budget dan hal yang paling penting bagimu. Rekomendasi disusun dari ulasan Google Maps
        yang dianalisis per aspek: wifi, harga, suasana, dan pelayanan.
    </p>

    <dl class="angka-ringkas">
        <div><dt>Coffee shop</dt><dd><?= angka($ringkasan['cafe']) ?></dd></div>
        <div><dt>Ulasan dianalisis</dt><dd><?= angka($ringkasan['ulasan']) ?></dd></div>
        <div><dt>Akurasi sentimen</dt><dd><?= persen(HASIL_SVM['akurasi'], 0) ?></dd></div>
    </dl>

    <form class="kartu form-preferensi" action="<?= url('rekomendasi') ?>" method="get">
        <fieldset>
            <legend><span class="nomor-langkah">1</span>Budget per orang</legend>
            <?php if ($galat === 'budget'): ?>
                <p class="pesan-galat" role="alert">Pilih salah satu budget dulu.</p>
            <?php endif; ?>
            <div class="pilihan-budget">
                <?php foreach (BUDGET as $kunci => [$nama, $rentang]): ?>
                    <label class="opsi-budget">
                        <input type="radio" name="budget" value="<?= e($kunci) ?>" required>
                        <span class="opsi-isi">
                            <span class="opsi-nama"><?= e($nama) ?></span>
                            <span class="opsi-rentang"><?= e($rentang) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <fieldset>
            <legend><span class="nomor-langkah">2</span>Yang paling penting bagimu</legend>
            <p class="bantuan">Boleh pilih lebih dari satu. Aspek yang tidak dipilih tetap ikut dipertimbangkan dengan bobot lebih kecil.</p>
            <div class="pilihan-prioritas">
                <?php foreach (LABEL_PRIORITAS as $kunci => $label): ?>
                    <label class="chip">
                        <input type="checkbox" name="prioritas[]" value="<?= e($kunci) ?>">
                        <span class="chip-isi">
                            <svg class="chip-centang" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                            <?= e($label) ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <button class="tombol tombol-penuh" type="submit">
            Tampilkan rekomendasi
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
    </form>

    <form class="cari-cepat" action="<?= url('cafe') ?>" method="get" role="search">
        <label for="cari-nama">Sudah tahu tempatnya?</label>
        <div class="kolom-cari">
            <input type="search" id="cari-nama" name="q" placeholder="Cari nama coffee shop" maxlength="60">
            <button class="tombol tombol-garis" type="submit">Cari</button>
        </div>
    </form>
</section>