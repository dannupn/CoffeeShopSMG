<?php
defined('APP_JALAN') or exit;

class StatistikController extends Controller
{
    public function index(): void
    {
        $cafe = new CafeModel();
        $badge = new BadgeModel();
        $distribusi = (new KalimatAspekModel())->distribusi();

        $this->view('statistik/index', [
            'judulHalaman' => 'Statistik ulasan',
            'ringkasan'    => $cafe->ringkasan() + [
                'kalimat'   => array_sum(array_map('array_sum', $distribusi)),
                'berbadge'  => $badge->jumlahCafeBerbadge(),
            ],
            'distribusi'   => $distribusi,
            'perBadge'     => $badge->jumlahPerBadge(),
            'perKategori'  => $cafe->jumlahPerKategori(),
            'teratas'      => $badge->teratasPerAspek(),
            'dataPeta'     => ['mode' => 'semua', 'cafe' => $cafe->semuaUntukPeta()],
        ]);
    }
}