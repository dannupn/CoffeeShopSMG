<?php
defined('APP_JALAN') or exit;

class RekomendasiController extends Controller
{
    public function index(): void
    {
        $budget = $_GET['budget'] ?? '';
        if (!is_string($budget) || !array_key_exists($budget, BUDGET)) {
            $this->redirect('', ['galat' => 'budget']);
        }
        $pilihan = (array) ($_GET['prioritas'] ?? []);
        $prioritas = array_values(array_intersect(array_keys(ASPEK), $pilihan));

        $layanan = new RekomendasiService();
        $vektor = $layanan->vektorPreferensi($budget, $prioritas);
        $skor = $layanan->rekomendasikan($vektor);

        $cafeModel = new CafeModel();
        $cafe = $cafeModel->cariBanyak(array_keys($skor));
        $badge = (new BadgeModel())->untukCafe(array_keys($skor));

        $penanda = [];
        $peringkat = 1;
        foreach ($skor as $id => $nilai) {
            $penanda[] = [
                'id' => $id, 'peringkat' => $peringkat++, 'nama' => $cafe[$id]['nama_cafe'],
                'lat' => (float) $cafe[$id]['latitude'], 'lng' => (float) $cafe[$id]['longitude'],
                'harga' => $cafe[$id]['harga_indikasi'], 'skor' => angka($nilai * 100, 1) . '%',
            ];
        }

        $this->view('rekomendasi/index', [
            'judulHalaman' => 'Rekomendasi coffee shop',
            'budget'       => $budget,
            'prioritas'    => $prioritas,
            'vektor'       => $vektor,
            'skor'         => $skor,
            'cafe'         => $cafe,
            'badge'        => $badge,
            'dataPeta'     => ['mode' => 'rekomendasi', 'cafe' => $cafeModel->semuaUntukPeta(), 'rekomendasi' => $penanda],
        ]);
    }
}