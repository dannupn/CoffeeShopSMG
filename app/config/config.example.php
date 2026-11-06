<?php
defined('APP_JALAN') or exit;

// ---------- koneksi database (bawaan Laragon) ----------
const DB_HOST = 'localhost';
const DB_NAMA = 'nama_database';
const DB_USER = 'isi_username_di_sini';
const DB_PASS = 'isi_password_di_sini';

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
    'data_label' => 987, 'data_latih' => 789, 'data_uji' => 198,
    'kernel' => 'linear', 'c' => 1, 'akurasi' => 0.8939, 'f1_makro' => 0.7566,
];
// hasil evaluasi relevansi rekomendasi (BAB 4.7.2)
const HASIL_REKOMENDASI = [
    'skenario' => 10, 'precision' => 0.9300, 'recall' => 0.8809, 'f1' => 0.8979,
];

// BASE_URL dihitung otomatis agar jalan di http://localhost/rekomendasi-coffee/
// maupun di http://rekomendasi-coffee.test/
$folder = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if (str_ends_with($folder, '/public')) {
    $folder = substr($folder, 0, -strlen('/public'));
}
define('BASE_URL', $folder . '/');
unset($folder);