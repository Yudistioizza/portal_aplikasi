# AppHub Admin

Back office untuk katalog AppHub. Dibangun dengan **Laravel 11**, **Spatie Permission**, **Vite**, **Tailwind** dan tata letak bergaya AdminLTE. Berjalan di SQLite tanpa layanan eksternal.

## Fitur

- Akses berbasis role: `superadmin` (semua, termasuk role), `admin` (konten + pengguna), `user` (beranda saja)
- Kelola aplikasi (logo disimpan di database), kategori bisnis, dan tipe website
- Aktifkan / nonaktifkan dan hapus aplikasi, dengan pencarian dan paginasi
- MFA TOTP opsional per akun
- Pendaftaran mandiri ditutup; akun dibuat oleh administrator
- Antarmuka dan pesan dalam bahasa Indonesia (`APP_LOCALE=id`)
- 34 tes fitur (autentikasi, matriks otorisasi, CRUD dengan unggah file)

## Dinonaktifkan sementara

**Login SSO Microsoft (Azure AD)** diberi komentar dan siap diaktifkan kembali: route di `routes/web.php`, controller `app/Http/Controllers/Auth/AzureController.php`, listener di `app/Providers/EventServiceProvider.php`, dan tombol di `resources/views/auth/login.blade.php`.

## Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # Windows: type nul > database\database.sqlite
php artisan migrate --seed
npm install && npm run build
php artisan serve                   # http://localhost:8000
php artisan test
```

Akun demo (fiktif, tampil di halaman login selama `DEMO_MODE=true`):

| Akun | Password | Role |
| --- | --- | --- |
| `admin@example.com` | `Admin#12345` | superadmin |
| `editor@example.com` | `Admin#12345` | admin |
| `viewer@example.com` | `Admin#12345` | user |

Set `DEMO_MODE=false` dan ganti password seed sebelum dipakai di lingkungan nyata.
