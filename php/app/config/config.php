<?php
defined('APP_JALAN') or exit;

// ---------- koneksi database (bawaan Laragon) ----------
const DB_HOST = 'localhost';
const DB_NAMA = 'db_coffee_shop_baru';
const DB_USER = 'xxx';
const DB_PASS = 'xxx';

// ---------- pengaturan aplikasi ----------
const NAMA_APLIKASI = 'Ngopi di Mana';
const JUMLAH_REKOMENDASI = 10;
const MIN_KALIMAT_BADGE = 3;

const ASPEK = [
    'wifi'      => 'Wifi',
    'harga'     => 'Harga',
    'suasana'   => 'Suasana',
    'pelayanan' => 'Pelayanan',
];
const LABEL_PRIORITAS = [
    'wifi'      => 'Wifi kencang dan stabil',
    'harga'     => 'Harga terjangkau',
    'suasana'   => 'Suasana nyaman',
    'pelayanan' => 'Pelayanan ramah',
];
const TEKS_BADGE = [
    'wifi'      => ['Wifi Kencang', 'Wifi Kurang Stabil'],
    'harga'     => ['Harga Terjangkau', 'Harga Kurang Bersahabat'],
    'suasana'   => ['Suasana Nyaman', 'Kurang Cocok untuk Fokus'],
    'pelayanan' => ['Pelayanan Ramah', 'Pelayanan Perlu Ditingkatkan'],
];
const BUDGET = [
    'murah'  => ['Murah', 'sampai Rp25.000'],
    'sedang' => ['Sedang', 'Rp25.001 sampai Rp50.000'],
    'mahal'  => ['Mahal', 'di atas Rp50.000'],
];

const KATEGORI_HARGA = [
    'murah'           => 'Murah',
    'sedang'          => 'Sedang',
    'mahal'           => 'Mahal',
    'tidak_diketahui' => 'Tidak diketahui',
];
const JUMLAH_SERUPA = 5;
const PER_HALAMAN = 20;

// ---------- gaya peta OpenFreeMap (gratis, tanpa API key) ----------
const GAYA_PETA = [
    'terang' => 'https://tiles.openfreemap.org/styles/positron',
    'gelap'  => 'https://tiles.openfreemap.org/styles/dark',
];
// hasil evaluasi_svm.txt (BAB 4.7.3)
const HASIL_SVM = [
    'data_label' => 1000, 'data_latih' => 800, 'data_uji' => 200,
    'kernel' => 'linear', 'c' => 1, 'akurasi' => 0.86, 'f1_makro' => 0.7183,
];
// hasil evaluasi relevansi rekomendasi (BAB 4.7.2)
const HASIL_REKOMENDASI = [
    'skenario' => 10, 'precision' => 0.9500, 'recall' => 0.8832, 'f1' => 0.9109,
];

// BASE_URL dihitung otomatis agar jalan di http://localhost/rekomendasi-coffee/
// maupun di http://rekomendasi-coffee.test/
$folder = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if (str_ends_with($folder, '/public')) {
    $folder = substr($folder, 0, -strlen('/public'));
}
define('BASE_URL', $folder . '/');
unset($folder);