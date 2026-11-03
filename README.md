# Portal Aplikasi

Katalog aplikasi perusahaan (merek fiktif **AppHub**, data demo). Tiga proyek dalam satu repo; seluruh antarmuka berbahasa Indonesia dan berjalan lokal tanpa layanan eksternal.

| Folder | Isi | Teknologi | Port |
| --- | --- | --- | --- |
| `apphub-frontend` | Etalase katalog aplikasi | React | 3000 |
| `apphub-api` | REST API katalog | Node, Express, Sequelize, JWT | 8084 |
| `apphub-admin` | Back office katalog | Laravel 11, Spatie Permission | 8000 |

Tiap folder punya README sendiri berisi cara menjalankan dan akun demo. Jalankan yang ingin dilihat saja; frontend otomatis memakai data demo bila API tidak aktif.

Login SSO Microsoft dinonaktifkan (kode diberi komentar).
