<?php
defined('APP_JALAN') or exit;

class TentangController extends Controller
{
    public function index(): void
    {
        $cafe = new CafeModel();
        $this->view('tentang/index', [
            'judulHalaman' => 'Tentang sistem',
            'ringkasan'    => $cafe->ringkasan(),
            'kalimat'      => array_sum(array_map('array_sum', (new KalimatAspekModel())->distribusi())),
            'fitur'        => (new FiturModel())->semuaIdf(),
            'dataPeta'     => ['mode' => 'semua', 'cafe' => $cafe->semuaUntukPeta()],
        ]);
    }
}