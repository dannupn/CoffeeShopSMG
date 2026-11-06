<?php
defined('APP_JALAN') or exit;

class Router
{
    private array $rute = [];

    public function get(string $pola, string $aksi): void
    {
        // "/cafe/{id}" -> regex "#^/cafe/(?P<id>[^/]+)$#"
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pola) . '$#';
        $this->rute[] = [$regex, $aksi];
    }

    public function jalankan(string $path): void
    {
        foreach ($this->rute as [$regex, $aksi]) {
            if (preg_match($regex, $path, $cocok)) {
                [$namaController, $method] = explode('@', $aksi);
                $parameter = array_filter($cocok, 'is_string', ARRAY_FILTER_USE_KEY);
                (new $namaController())->$method(...array_values($parameter));
                return;
            }
        }
        (new Controller())->tidakDitemukan();
    }
}