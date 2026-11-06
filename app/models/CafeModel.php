<?php
defined('APP_JALAN') or exit;

class CafeModel extends Model
{
    public function cari(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM cafe WHERE id_cafe = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    /** Beberapa cafe sekaligus, hasil di-index dengan id_cafe */
    public function cariBanyak(array $ids): array
    {
        if (!$ids) {
            return [];
        }
        $st = $this->db->prepare('SELECT * FROM cafe WHERE id_cafe IN (' . $this->tandaTanya($ids) . ')');
        $st->execute(array_values($ids));
        $hasil = [];
        foreach ($st as $baris) {
            $hasil[(int) $baris['id_cafe']] = $baris;
        }
        return $hasil;
    }

    /**
     * Pencarian dan penyaringan untuk halaman Jelajahi.
     * $filter: q (nama/alamat), budget (kategori_harga), aspek (punya badge positif), urut.
     * @return array ['data' => baris halaman ini, 'total' => jumlah cocok, 'ids' => seluruh id yang cocok]
     */
    public function jelajahi(array $filter, int $halaman, int $perHalaman = PER_HALAMAN): array
    {
        $syarat = [];
        $param = [];
        if ($filter['q'] !== '') {
            $kata = '%' . addcslashes($filter['q'], '%_\\') . '%';
            $syarat[] = '(c.nama_cafe LIKE ? OR c.alamat LIKE ?)';
            array_push($param, $kata, $kata);
        }
        if ($filter['budget'] !== '') {
            $syarat[] = 'c.kategori_harga = ?';
            $param[] = $filter['budget'];
        }
        if ($filter['aspek'] !== '') {
            $syarat[] = "EXISTS (SELECT 1 FROM badge_aspek b WHERE b.id_cafe = c.id_cafe
                                 AND b.aspek = ? AND b.sentimen_badge = 'positif')";
            $param[] = $filter['aspek'];
        }
        $where = $syarat ? 'WHERE ' . implode(' AND ', $syarat) : '';
        $urutan = [
            'rating' => 'c.rating_rata2 IS NULL, c.rating_rata2 DESC, c.jumlah_ulasan_total DESC',
            'ulasan' => 'c.jumlah_ulasan_total DESC, c.nama_cafe',
            'nama'   => 'c.nama_cafe',
        ][$filter['urut']] ?? 'c.nama_cafe';

        $st = $this->db->prepare("SELECT c.id_cafe FROM cafe c {$where} ORDER BY {$urutan}, c.id_cafe");
        $st->execute($param);
        $ids = array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));

        $bagian = array_slice($ids, ($halaman - 1) * $perHalaman, $perHalaman);
        $baris = $this->cariBanyak($bagian);
        $data = [];
        foreach ($bagian as $id) {
            $data[$id] = $baris[$id];
        }
        return ['data' => $data, 'total' => count($ids), 'ids' => $ids];
    }

    /** Data ringkas seluruh cafe untuk titik di peta */
    public function semuaUntukPeta(): array
    {
        $st = $this->db->query(
            'SELECT id_cafe, nama_cafe, latitude, longitude, harga_indikasi, rating_rata2 FROM cafe ORDER BY id_cafe'
        );
        $hasil = [];
        foreach ($st as $c) {
            $hasil[] = [
                'id'     => (int) $c['id_cafe'],
                'nama'   => $c['nama_cafe'],
                'lat'    => (float) $c['latitude'],
                'lng'    => (float) $c['longitude'],
                'harga'  => $c['harga_indikasi'],
                'rating' => $c['rating_rata2'] !== null ? angka($c['rating_rata2'], 1) : '-',
            ];
        }
        return $hasil;
    }

    public function ringkasan(): array
    {
        return [
            'cafe'   => (int) $this->db->query('SELECT COUNT(*) FROM cafe')->fetchColumn(),
            'ulasan' => (int) $this->db->query('SELECT COUNT(*) FROM ulasan')->fetchColumn(),
        ];
    }

    /** Jumlah cafe per kategori harga: [kategori => jumlah] */
    public function jumlahPerKategori(): array
    {
        $st = $this->db->query('SELECT kategori_harga, COUNT(*) FROM cafe GROUP BY kategori_harga');
        $hasil = array_fill_keys(array_keys(KATEGORI_HARGA), 0);
        foreach ($st->fetchAll(PDO::FETCH_KEY_PAIR) as $k => $n) {
            $hasil[$k] = (int) $n;
        }
        return $hasil;
    }
}