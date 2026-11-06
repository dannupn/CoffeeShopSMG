<?php
defined('APP_JALAN') or exit;

class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::koneksi();
    }

    /** "?,?,?" sejumlah isi array, untuk klausa IN */
    protected function tandaTanya(array $daftar): string
    {
        return implode(',', array_fill(0, count($daftar), '?'));
    }
}