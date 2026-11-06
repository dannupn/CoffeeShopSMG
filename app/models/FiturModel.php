<?php
defined('APP_JALAN') or exit;

class FiturModel extends Model
{
    /** Nilai IDF: [fitur => idf] */
    public function idf(array $fitur): array
    {
        $st = $this->db->prepare('SELECT fitur, idf FROM fitur_idf WHERE fitur IN (' . $this->tandaTanya($fitur) . ')');
        $st->execute($fitur);
        return array_map('floatval', $st->fetchAll(PDO::FETCH_KEY_PAIR));
    }

    /** Seluruh fitur beserta df dan IDF, untuk halaman Tentang */
    public function semuaIdf(): array
    {
        return $this->db->query('SELECT fitur, df, idf FROM fitur_idf ORDER BY fitur')->fetchAll();
    }

    /** Bobot TF-IDF ternormalisasi seluruh cafe untuk fitur tertentu, beserta rating sebagai pembeda */
    public function bobot(array $fitur): array
    {
        $st = $this->db->prepare(
            'SELECT b.id_cafe, b.fitur, b.bobot_normal, c.rating_rata2
             FROM bobot_tfidf b JOIN cafe c ON c.id_cafe = b.id_cafe
             WHERE b.fitur IN (' . $this->tandaTanya($fitur) . ')'
        );
        $st->execute($fitur);
        return $st->fetchAll();
    }

    /** Vektor fitur satu cafe: [fitur => ['tf', 'idf', 'bobot_normal']] */
    public function vektorCafe(int $idCafe): array
    {
        $st = $this->db->prepare('SELECT fitur, tf, idf, bobot_normal FROM bobot_tfidf WHERE id_cafe = ? ORDER BY bobot_normal DESC');
        $st->execute([$idCafe]);
        $hasil = [];
        foreach ($st as $b) {
            $hasil[$b['fitur']] = ['tf' => (float) $b['tf'], 'idf' => (float) $b['idf'], 'bobot_normal' => (float) $b['bobot_normal']];
        }
        return $hasil;
    }
}