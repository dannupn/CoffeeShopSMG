<?php
defined('APP_JALAN') or exit;

class Database
{
    private static ?PDO $koneksi = null;

    public static function koneksi(): PDO
    {
        if (self::$koneksi === null) {
            self::$koneksi = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAMA . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }
        return self::$koneksi;
    }
}