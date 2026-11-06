<?php
defined('APP_JALAN') or exit;

class CafeController extends Controller
{
    /** Halaman Jelajahi: cari nama dan saring seluruh coffee shop */
    public function daftar(): void
    {
        $ambil = fn(string $k) => is_string($_GET[$k] ?? null) ? trim($_GET[$k]) : '';
        $filter = [
            'q'      => mb_substr($ambil('q'), 0, 60),
            'budget' => array_key_exists($ambil('budget'), KATEGORI_HARGA) ? $ambil('budget') : '',
            'aspek'  => array_key_exists($ambil('aspek'), ASPEK) ? $ambil('aspek') : '',
            'urut'   => in_array($ambil('urut'), ['rating', 'ulasan', 'nama'], true) ? $ambil('urut') : 'rating',
        ];
        $halaman = max(1, (int) $ambil('halaman'));

        $cafeModel = new CafeModel();
        $hasil = $cafeModel->jelajahi($filter, $halaman);
        $jumlahHalaman = max(1, (int) ceil($hasil['total'] / PER_HALAMAN));
        if ($halaman > $jumlahHalaman) {
            $this->redirect('cafe', array_filter($filter + ['halaman' => $jumlahHalaman]));
        }
        $adaFilter = $filter['q'] !== '' || $filter['budget'] !== '' || $filter['aspek'] !== '';

        $this->view('cafe/daftar', [
            'judulHalaman'  => 'Jelajahi coffee shop',
            'filter'        => $filter,
            'adaFilter'     => $adaFilter,
            'daftar'        => $hasil['data'],
            'total'         => $hasil['total'],
            'halaman'       => $halaman,
            'jumlahHalaman' => $jumlahHalaman,
            'badge'         => (new BadgeModel())->untukCafe(array_keys($hasil['data'])),
            'dataPeta'      => [
                'mode'  => 'daftar',
                'cafe'  => $cafeModel->semuaUntukPeta(),
                'sorot' => $adaFilter ? $hasil['ids'] : null,
            ],
        ]);
    }

    public function detail(string $id): void
    {
        $cafeModel = new CafeModel();
        $cafe = ctype_digit($id) ? $cafeModel->cari((int) $id) : null;
        if (!$cafe) {
            $this->tidakDitemukan();
            return;
        }
        $idCafe = (int) $cafe['id_cafe'];

        $kalimat = new KalimatAspekModel();
        $contoh = [];
        foreach (array_keys(ASPEK) as $aspek) {
            foreach (['positif', 'negatif'] as $sentimen) {
                $contoh[$aspek][$sentimen] = $kalimat->contoh($idCafe, $aspek, $sentimen);
            }
        }

        // coffee shop serupa dengan cosine similarity antarvektor cafe
        $layanan = new RekomendasiService();
        $serupa = $layanan->serupa($idCafe);
        $badgeModel = new BadgeModel();
        $badge = $badgeModel->untukCafe(array_merge([$idCafe], array_keys($serupa)));

        $this->view('cafe/detail', [
            'judulHalaman' => $cafe['nama_cafe'],
            'cafe'         => $cafe,
            'badge'        => $badge[$idCafe] ?? [],
            'contoh'       => $contoh,
            'vektor'       => $layanan->vektorCafe($idCafe),
            'serupa'       => $serupa,
            'cafeSerupa'   => $cafeModel->cariBanyak(array_keys($serupa)),
            'badgeSerupa'  => $badge,
            'dataPeta'     => [
                'mode'  => 'detail',
                'cafe'  => $cafeModel->semuaUntukPeta(),
                'fokus' => ['id' => $idCafe, 'nama' => $cafe['nama_cafe'],
                            'lat' => (float) $cafe['latitude'], 'lng' => (float) $cafe['longitude']],
            ],
        ]);
    }
}