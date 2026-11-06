<?php
defined('APP_JALAN') or exit;

class Controller
{
    /** Tampilkan view di dalam layout utama */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require ROOT . "/app/views/{$view}.php";
        $konten = ob_get_clean();
        require ROOT . '/app/views/layouts/main.php';
    }

    protected function redirect(string $path, array $params = []): void
    {
        header('Location: ' . url($path, $params));
        exit;
    }

    public function tidakDitemukan(): void
    {
        http_response_code(404);
        $this->view('errors/404', [
            'judulHalaman' => 'Halaman tidak ditemukan',
            'dataPeta'     => ['mode' => 'semua', 'cafe' => (new CafeModel())->semuaUntukPeta()],
        ]);
    }
}