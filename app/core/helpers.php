<?php
defined('APP_JALAN') or exit;

function e($teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function angka($nilai, int $desimal = 0): string
{
    return number_format((float) $nilai, $desimal, ',', '.');
}

/** URL halaman, contoh: url('cafe/2'), url('rekomendasi', ['budget' => 'murah']) */
function url(string $path = '', array $params = []): string
{
    $hasil = BASE_URL . ltrim($path, '/');
    return $params ? $hasil . '?' . http_build_query($params) : $hasil;
}

/** URL file di folder public/assets */
function asset(string $path): string
{
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

/** Path permintaan saat ini relatif terhadap BASE_URL, contoh: "/cafe/2" */
function pathSaatIni(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $path = rawurldecode($path);
    if (BASE_URL !== '/' && str_starts_with($path, BASE_URL)) {
        $path = substr($path, strlen(BASE_URL) - 1);
    }
    $path = preg_replace('#^/public(?=/|$)#', '', $path);   // bila diakses lewat /public
    $path = preg_replace('#^/index\.php(?=/|$)#', '', $path);
    return '/' . trim($path, '/');
}

/** Tautan petunjuk arah Google Maps dari lokasi pengguna ke koordinat tujuan */
function urlArah($lat, $lng): string
{
    return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($lat . ',' . $lng);
}

function teksJam(?string $jam): string
{
    if ($jam === null || $jam === '' || $jam === 'Tidak diketahui') {
        return 'Jam buka tidak diketahui';
    }
    return str_starts_with($jam, 'Buka') ? $jam : 'Buka ' . $jam;
}

/** Elemen badge satu aspek */
function htmlBadge(?array $b): string
{
    if (!$b || $b['badge'] === '') {
        return '';
    }
    $negatif = $b['sentimen_badge'] === 'negatif';
    $jumlah = $negatif ? $b['n_negatif'] : $b['n_positif'];
    $judul = "{$jumlah} dari {$b['n_total']} kalimat ulasan tentang " . strtolower(ASPEK[$b['aspek']]);
    return '<span class="badge' . ($negatif ? ' badge-negatif' : '') . '" title="' . e($judul) . '">'
        . e($b['badge']) . '</span>';
}

/** Kelas "aktif" untuk menu navigasi yang sedang dibuka */
function navAktif(string $awalan): string
{
    $path = pathSaatIni();
    $cocok = $awalan === '/' ? in_array($path, ['/', '/rekomendasi'], true)
                             : ($path === $awalan || str_starts_with($path, $awalan . '/'));
    return $cocok ? ' aria-current="page" class="aktif"' : '';
}

/** Angka 0 sampai 1 menjadi persen berformat Indonesia, contoh 0.8832 -> "88,3%" */
function persen($nilai, int $desimal = 1): string
{
    return angka((float) $nilai * 100, $desimal) . '%';
}