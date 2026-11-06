<?php
defined('APP_JALAN') or exit;

class KalimatAspekModel extends Model
{
    /**
     * Contoh kalimat ulasan sebagai bukti badge.
     * Diutamakan kalimat berlabel manual, panjang 20 sampai 220 karakter.
     */
    public function contoh(int $idCafe, string $aspek, string $sentimen, int $jumlah = 2): array
    {
        $st = $this->db->prepare(
            "SELECT kalimat_asli FROM kalimat_aspek
             WHERE id_cafe = ? AND aspek = ? AND sentimen = ? AND CHAR_LENGTH(kalimat_asli) BETWEEN 20 AND 220
             ORDER BY sumber_label = 'manual' DESC, id_aspek_kalimat
             LIMIT {$jumlah}"
        );
        $st->execute([$idCafe, $aspek, $sentimen]);
        return $st->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Jumlah kalimat per aspek per sentimen: [aspek][sentimen] => jumlah */
    public function distribusi(): array
    {
        $st = $this->db->query('SELECT aspek, sentimen, COUNT(*) AS n FROM kalimat_aspek GROUP BY aspek, sentimen');
        $hasil = [];
        foreach (array_keys(ASPEK) as $a) {
            $hasil[$a] = ['positif' => 0, 'negatif' => 0, 'netral' => 0];
        }
        foreach ($st as $b) {
            $hasil[$b['aspek']][$b['sentimen']] = (int) $b['n'];
        }
        return $hasil;
    }
}