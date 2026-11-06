<?php
defined('APP_JALAN') or exit;

class BerandaController extends Controller
{
    public function index(): void
    {
        $cafe = new CafeModel();
        $this->view('beranda/index', [
            'judulHalaman' => 'Cari coffee shop di Semarang',
            'ringkasan'    => $cafe->ringkasan(),
            'galat'        => $_GET['galat'] ?? '',
            'dataPeta'     => ['mode' => 'semua', 'cafe' => $cafe->semuaUntukPeta()],
        ]);
    }
}