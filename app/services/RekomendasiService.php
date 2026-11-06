<?php
defined('APP_JALAN') or exit;

/**
 * Content-Based Filtering (Subbab 3.5.3)
 * 1. Vektor preferensi: TF = 2 untuk budget dan aspek prioritas, TF = 1 untuk aspek positif lainnya.
 * 2. Bobot = TF x IDF, lalu dinormalisasi (panjang vektor = 1).
 * 3. Cosine similarity (Persamaan 2.1) = jumlah perkalian bobot ternormalisasi pada fitur yang sama.
 */
class RekomendasiService
{
    private FiturModel $fitur;

    public function __construct()
    {
        $this->fitur = new FiturModel();
    }

    public function vektorPreferensi(string $budget, array $prioritas): array
    {
        $tf = [];
        foreach (array_keys(ASPEK) as $aspek) {
            $tf["{$aspek}_positif"] = 1.0;
        }
        $tf["budget_{$budget}"] = 2.0;
        foreach ($prioritas as $aspek) {
            $tf["{$aspek}_positif"] = 2.0;
        }

        $idf = $this->fitur->idf(array_keys($tf));
        $vektor = [];
        $kuadrat = 0.0;
        foreach ($tf as $f => $nilaiTf) {
            $nilaiIdf = $idf[$f] ?? 0.0;
            $bobot = $nilaiTf * $nilaiIdf;
            $vektor[$f] = ['tf' => $nilaiTf, 'idf' => $nilaiIdf, 'bobot' => $bobot];
            $kuadrat += $bobot ** 2;
        }
        $norma = sqrt($kuadrat);
        foreach ($vektor as $f => $v) {
            $vektor[$f]['bobot_normal'] = $norma > 0 ? $v['bobot'] / $norma : 0.0;
        }
        return $vektor;
    }

    /** @return array [id_cafe => nilai cosine similarity], urut menurun */
    public function rekomendasikan(array $vektor, int $jumlah = JUMLAH_REKOMENDASI): array
    {
        return $this->cosine($vektor, $jumlah);
    }

    /**
     * Coffee shop serupa (item-to-item): vektor fitur cafe acuan dibandingkan
     * dengan vektor seluruh cafe lain memakai cosine similarity yang sama.
     */
    public function serupa(int $idCafe, int $jumlah = JUMLAH_SERUPA): array
    {
        $vektor = $this->fitur->vektorCafe($idCafe);
        return $vektor ? $this->cosine($vektor, $jumlah, $idCafe) : [];
    }

    /** Vektor fitur cafe yang dipakai pada perhitungan coffee shop serupa */
    public function vektorCafe(int $idCafe): array
    {
        return $this->fitur->vektorCafe($idCafe);
    }

    private function cosine(array $vektor, int $jumlah, ?int $kecuali = null): array
    {
        $skor = [];
        $rating = [];
        foreach ($this->fitur->bobot(array_keys($vektor)) as $b) {
            $id = (int) $b['id_cafe'];
            if ($id === $kecuali) {
                continue;
            }
            $skor[$id] = ($skor[$id] ?? 0.0) + (float) $b['bobot_normal'] * $vektor[$b['fitur']]['bobot_normal'];
            $rating[$id] = (float) $b['rating_rata2'];
        }
        // skor menurun, bila sama rating menurun, lalu id menaik
        uksort($skor, fn($a, $b) => [$skor[$b], $rating[$b], $a] <=> [$skor[$a], $rating[$a], $b]);
        return array_slice($skor, 0, $jumlah, true);
    }
}