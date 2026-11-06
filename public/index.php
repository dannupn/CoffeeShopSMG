<?php
// Front controller: semua request masuk lewat file ini
define('APP_JALAN', true);
define('ROOT', dirname(__DIR__));

require ROOT . '/app/config/config.php';
require ROOT . '/app/core/helpers.php';

// autoload class dari folder core, models, services, dan controllers
spl_autoload_register(function (string $kelas): void {
    foreach (['core', 'models', 'services', 'controllers'] as $folder) {
        $file = ROOT . "/app/{$folder}/{$kelas}.php";
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

$router = new Router();
$router->get('/', 'BerandaController@index');
$router->get('/rekomendasi', 'RekomendasiController@index');
$router->get('/cafe', 'CafeController@daftar');
$router->get('/cafe/{id}', 'CafeController@detail');
$router->get('/statistik', 'StatistikController@index');
$router->get('/tentang', 'TentangController@index');

try {
    $router->jalankan(pathSaatIni());
} catch (PDOException $e) {
    http_response_code(500);
    echo '<h1>Gagal terhubung ke database</h1><p>Pastikan MySQL menyala dan database '
        . e(DB_NAMA) . ' sudah diimpor. Pengaturan koneksi ada di app/config/config.php.</p>';
}