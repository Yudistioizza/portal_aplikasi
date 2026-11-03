# Tentang aplikasi: Portal Aplikasi (AppHub)

## Fungsi

**Pintu masuk tunggal ke seluruh aplikasi internal perusahaan.** Karyawan membuka satu situs, mencari aplikasi yang mereka butuhkan (cuti, e-sign, pengadaan, tiket TI, ruang rapat, dst.), memfilter menurut kategori bisnis dan tipe website, lalu membukanya dengan satu klik. Tim admin mengelola isi katalog lewat back office.

Masalah yang diselesaikan: karyawan tidak tahu aplikasi apa saja yang tersedia, tautan tersebar di chat/email, dan tidak ada tempat resmi untuk mengelola daftar aplikasi.

Repo ini adalah **versi showcase**: merek fiktif AppHub, data dan akun fiktif (`example.com`), tanpa layanan eksternal. Berasal dari aplikasi nyata yang sudah dibersihkan dari merek dan rahasia perusahaan.

## Tiga bagian

| Bagian | Pengguna | Fungsi |
| --- | --- | --- |
| `apphub-frontend` | Semua karyawan (publik, tanpa login) | Etalase katalog: kartu aplikasi (logo, nama, deskripsi, tautan), pencarian nama/deskripsi, filter kategori bisnis dan tipe website, paginasi. Bila API tidak aktif, otomatis memakai data demo bawaan sehingga selalu tampil. |
| `apphub-api` | Frontend dan klien lain | REST API: daftar, cari, dan filter aplikasi (publik); daftar tipe website dan kategori bisnis; login JWT; endpoint tulis khusus admin. Swagger di `/api-docs`. |
| `apphub-admin` | Tim admin | Back office: tambah/ubah/hapus aplikasi (unggah logo), aktifkan/nonaktifkan, kelola kategori bisnis dan tipe website, kelola pengguna dan role, MFA TOTP opsional. |

## Alur data

1. Admin mengisi katalog di `apphub-admin` (database Laravel sendiri) atau di `apphub-api` (database Sequelize).
2. `apphub-frontend` memanggil `apphub-api` (`REACT_APP_API_URL`, bawaan `http://localhost:8084`).
3. API mati atau `REACT_APP_USE_DEMO_DATA=true`: frontend memakai `src/data/demoApps.json`.

Catatan: `apphub-api` dan `apphub-admin` adalah dua implementasi dengan basis data terpisah dan model data setara; belum disinkronkan otomatis.

## Peran dan hak akses

- **Back office:** `superadmin` (semua, termasuk role), `admin` (konten + pengguna), `user` (hanya beranda). Pendaftaran mandiri ditutup.
- **API:** `user`, `moderator`, `admin`; signup selalu menghasilkan `user` (role di body diabaikan). Endpoint tulis hanya admin.

## Model data

Aplikasi (nama, deskripsi, logo, tautan, urutan, aktif) milik satu **tipe website** (Internal, Eksternal, Mobile) dan banyak **kategori bisnis** (SDM, Keuangan, Operasional, Penjualan & Pemasaran, TI & Keamanan, Hukum & Kepatuhan). Ada 14 aplikasi demo (HR Connect, e-Sign Hub, Procure Pro, FleetTrack, Ticket Desk, Meeting Rooms, dst.).

## Keamanan yang sudah ditangani

Signup tidak bisa memberi role; register admin ditutup; role gate di semua halaman admin; rate limit login; CORS allow-list; header keamanan; MFA trust cookie tertanda HMAC; pesan error 500 tidak membocorkan detail; tidak ada rahasia di kode.

## Teknologi

Frontend: React (CRA), Axios, CSS responsif buatan sendiri. API: Node, Express, Sequelize, JWT, SQLite (PostgreSQL via `DB_*`). Admin: Laravel 11, Spatie Permission, Tailwind, Vite, SQLite.

## Status

Tes: API 4, admin 34 (Laravel), frontend build produksi. Seluruh UI Bahasa Indonesia. Login SSO Microsoft dinonaktifkan (kode dikomentari).

## Menjalankan dan login demo

Lihat `AGENTS.md`. `admin@example.com` / `Admin#12345`.
