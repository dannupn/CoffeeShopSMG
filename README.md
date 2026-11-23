# CoffeeShopSMG

Sistem rekomendasi coffee shop berbasis web di Kota Semarang dengan *Content-Based Filtering* dan analisis sentimen berbasis aspek (wifi, harga, suasana, pelayanan).

Skripsi: Imanuel Dannu Putra Nugraha (NIM 23N10039).

## Struktur repositori

| Folder | Isi |
|---|---|
| `php/` | Aplikasi web (PHP berpola MVC: `app/`, `public/`, `.htaccess`) |
| `ipynb/` | Notebook Python: scraping, praproses, ekstraksi aspek, klasifikasi sentimen, validasi, perbandingan model, evaluasi relevansi |
| `csv/` | Data mentah, data bersih, data final (`data_final/`), hasil validasi (`hasil_validasi/`), dan data bantu validasi (`validasi_data/`) |
| `xlsx/` | Lembar pelabelan tiga penilai, hasil kappa, perbandingan model, dan lembar penilaian relevansi rekomendasi |
| `sql/` | `db_coffee_shop.sql`, basis data MySQL yang dipakai aplikasi web |

## Menjalankan aplikasi web

1. Impor `sql/db_coffee_shop.sql` ke MySQL (collation `utf8mb4`).
2. Isi `DB_NAMA`, `DB_USER`, dan `DB_PASS` pada `php/app/config/config.php`.
3. Letakkan folder `php/` pada server lokal (misalnya Laragon) dan buka melalui peramban.

## Urutan notebook

`00_scraping_cleaning` dan `00b_scraping_retry_dan_olah_data_tahap_01_07` (scraping, praproses, ekstraksi aspek, klasifikasi sentimen, dokumen fitur dan TF-IDF, data final, evaluasi relevansi), lalu `01` sampai `05` untuk validasi aspek, validasi sentimen, uji kappa, pelabelan seluruh dataset, dan perbandingan model.
