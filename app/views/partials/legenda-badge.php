<?php defined('APP_JALAN') or exit; ?>
<details class="kotak-info">
    <summary>Arti label pada coffee shop</summary>
    <p>
        Label disimpulkan dari hasil klasifikasi sentimen (SVM) terhadap kalimat ulasan yang membahas setiap aspek.
        Sebuah aspek baru diberi label bila dibahas minimal <?= MIN_KALIMAT_BADGE ?> kalimat.
    </p>
    <div class="legenda">
        <?php foreach (ASPEK as $a => $nama): ?>
            <div class="legenda-baris">
                <span class="legenda-aspek"><?= e($nama) ?></span>
                <span class="badge"><?= e(TEKS_BADGE[$a][0]) ?></span>
                <span class="badge badge-negatif"><?= e(TEKS_BADGE[$a][1]) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="bantuan">
        Label hijau berarti kalimat positif lebih banyak daripada kalimat negatif, label merah berarti sebaliknya.
        Tanpa label berarti ulasannya kurang dari <?= MIN_KALIMAT_BADGE ?> kalimat, pendapatnya berimbang, atau sebagian besar netral.
        Arahkan kursor ke label untuk melihat jumlah kalimat pendukungnya.
    </p>
</details>