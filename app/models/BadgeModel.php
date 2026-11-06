<?php
defined('APP_JALAN') or exit;

class BadgeModel extends Model
{
    /** Badge per cafe per aspek: [id_cafe][aspek] => baris badge_aspek */
    public function untukCafe(array $ids): array
    {
        if (!$ids) {
            return [];
        }
        $st = $this->db->prepare('SELECT * FROM badge_aspek WHERE id_cafe IN (' . $this->tandaTanya($ids) . ')');
        $st->execute(array_values($ids));
        $hasil = [];
        foreach ($st as $baris) {
            $hasil[(int) $baris['id_cafe']][$baris['aspek']] = $baris;
        }
        return $hasil;
    }

    /** Jumlah cafe per badge: [aspek][positif|negatif|tanpa] => jumlah */
    public function jumlahPerBadge(): array
    {
        $st = $this->db->query('SELECT aspek, sentimen_badge, COUNT(*) AS n FROM badge_aspek GROUP BY aspek, sentimen_badge');
        $hasil = [];
        foreach (array_keys(ASPEK) as $a) {
            $hasil[$a] = ['positif' => 0, 'negatif' => 0, 'tanpa' => 0];
        }
        foreach ($st as $b) {
            $hasil[$b['aspek']][$b['sentimen_badge'] ?: 'tanpa'] = (int) $b['n'];
        }
        return $hasil;
    }

    public function jumlahCafeBerbadge(): int
    {
        return (int) $this->db->query("SELECT COUNT(DISTINCT id_cafe) FROM badge_aspek WHERE badge <> ''")->fetchColumn();
    }

    /** Coffee shop dengan persentase kalimat positif tertinggi per aspek, minimal $minKalimat kalimat */
    public function teratasPerAspek(int $minKalimat = 30, int $jumlah = 3): array
    {
        $st = $this->db->prepare(
            "SELECT b.id_cafe, b.persen_positif, b.n_total, c.nama_cafe
             FROM badge_aspek b JOIN cafe c ON c.id_cafe = b.id_cafe
             WHERE b.aspek = ? AND b.n_total >= ?
             ORDER BY b.persen_positif DESC, b.n_total DESC
             LIMIT {$jumlah}"
        );
        $hasil = [];
        foreach (array_keys(ASPEK) as $a) {
            $st->execute([$a, $minKalimat]);
            $hasil[$a] = $st->fetchAll();
        }
        return $hasil;
    }
}