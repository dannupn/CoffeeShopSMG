<?php defined('APP_JALAN') or exit; ?>
<section class="isi">
    <p class="label-atas">Tentang sistem</p>
    <h1 class="judul">Bagaimana rekomendasi ini dibuat</h1>
    <p class="pengantar">
        <?= e(NAMA_APLIKASI) ?> merekomendasikan coffee shop di Kota Semarang dengan menggabungkan
        <i>Content-Based Filtering</i> (CBF) dan analisis sentimen berbasis aspek (ABSA) terhadap ulasan Google Maps.
    </p>

    <ol class="alur">
        <li>
            <h2>Pengumpulan data</h2>
            <p>Data <?= angka($ringkasan['cafe']) ?> coffee shop beserta <?= angka($ringkasan['ulasan']) ?> ulasan pengunjung diambil dari Google Maps.</p>
        </li>
        <li>
            <h2>Praproses teks</h2>
            <p>Ulasan dibersihkan, diubah ke huruf kecil, dinormalisasi, ditokenisasi, dihapus <i>stopword</i>-nya
                (kata negasi seperti "tidak" tetap dipertahankan), di-<i>stemming</i> dengan Sastrawi, lalu dipecah per kalimat.</p>
        </li>
        <li>
            <h2>Ekstraksi aspek</h2>
            <p>Setiap kalimat dicocokkan dengan kata kunci empat aspek: wifi, harga, suasana, dan pelayanan.
                Hasilnya <?= angka($kalimat) ?> pasangan kalimat dan aspek.</p>
        </li>
        <li>
            <h2>Klasifikasi sentimen dengan SVM</h2>
            <p><?= angka(HASIL_SVM['data_label']) ?> kalimat dilabeli oleh tiga penilai (label gold dengan suara mayoritas) lalu dibagi
                <?= HASIL_SVM['data_latih'] ?> data latih dan <?= HASIL_SVM['data_uji'] ?> data uji.
                Model terbaik dari <i>grid search</i> 5-fold adalah kernel <?= e(HASIL_SVM['kernel']) ?> dengan C = <?= HASIL_SVM['c'] ?>.
                Seluruh kalimat kemudian diklasifikasikan dan diringkas menjadi label per aspek.</p>
        </li>
        <li>
            <h2>Content-Based Filtering</h2>
            <p>Setiap coffee shop direpresentasikan sebagai vektor fitur dari kategori harga dan sentimen per aspek.
                Preferensi pengguna dibentuk menjadi vektor yang sama, lalu keduanya dibandingkan dengan <i>cosine similarity</i>.</p>
        </li>
    </ol>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Rumus yang dipakai</h2>
        <div class="rumus">
            <p><span class="rumus-nama">IDF</span> idf(t) = ln(N / df(t)) + 1, dengan N = <?= angka($ringkasan['cafe']) ?></p>
            <p><span class="rumus-nama">Bobot</span> w(t) = tf(t) &times; idf(t), lalu dinormalisasi agar panjang vektor = 1</p>
            <p><span class="rumus-nama">Cosine</span> cos(q, d) = &Sigma; q<sub>t</sub> d<sub>t</sub> / (&#8214;q&#8214; &#8214;d&#8214;)</p>
        </div>
        <p class="bantuan">Pada vektor preferensi, budget dan aspek prioritas diberi TF 2, aspek positif lainnya TF 1.</p>
        <div class="tabel-gulir">
            <table class="tabel">
                <thead><tr><th scope="col">Fitur</th><th scope="col">df</th><th scope="col">IDF</th></tr></thead>
                <tbody>
                <?php foreach ($fitur as $f): ?>
                    <tr><td><code><?= e($f['fitur']) ?></code></td><td><?= angka($f['df']) ?></td><td><?= angka($f['idf'], 4) ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Hasil pengujian</h2>
        <h3 class="teratas-judul">Klasifikasi sentimen (SVM, <?= HASIL_SVM['data_uji'] ?> data uji)</h3>
        <dl class="angka-ringkas">
            <div><dt>Akurasi</dt><dd><?= persen(HASIL_SVM['akurasi']) ?></dd></div>
            <div><dt>F1 makro</dt><dd><?= angka(HASIL_SVM['f1_makro'], 4) ?></dd></div>
            <div><dt>Kernel</dt><dd><?= e(ucfirst(HASIL_SVM['kernel'])) ?></dd></div>
        </dl>
        <h3 class="teratas-judul">Relevansi rekomendasi (<?= HASIL_REKOMENDASI['skenario'] ?> skenario preferensi)</h3>
        <dl class="angka-ringkas">
            <div><dt>Precision</dt><dd><?= angka(HASIL_REKOMENDASI['precision'], 4) ?></dd></div>
            <div><dt>Recall</dt><dd><?= angka(HASIL_REKOMENDASI['recall'], 4) ?></dd></div>
            <div><dt>F1-score</dt><dd><?= angka(HASIL_REKOMENDASI['f1'], 4) ?></dd></div>
        </dl>
    </section>

    <section class="kartu blok-statistik">
        <h2 class="subjudul-kartu">Batasan</h2>
        <ul class="daftar-biasa">
            <li>Cakupan hanya coffee shop di Kota Semarang yang terdaftar di Google Maps.</li>
            <li>Ulasan dan rating mengikuti kondisi saat data diambil, sehingga bisa berbeda dengan keadaan terbaru.</li>
            <li>Harga memakai indikasi harga per orang dari Google Maps.</li>
            <li>Aspek yang dianalisis terbatas pada wifi, harga, suasana, dan pelayanan.</li>
            <li>Sebanyak 13 kalimat dikeluarkan dari data berlabel karena tiga penilai tidak mencapai kesepakatan mayoritas.</li>
            <li>Recall dihitung pada 20 coffee shop kandidat per skenario (10 teratas dan 10 acak), bukan pada seluruh coffee shop.</li>
        </ul>
    </section>
</section>