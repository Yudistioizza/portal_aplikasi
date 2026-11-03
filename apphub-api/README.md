# AppHub API

REST API untuk katalog aplikasi AppHub. Dibangun dengan **Node.js**, **Express**, **Sequelize** dan **JWT**. Berjalan langsung di SQLite dan bisa pindah ke PostgreSQL lewat variabel lingkungan.

## Fitur

- Endpoint katalog publik: daftar, pencarian, filter kategori bisnis dan tipe website, paginasi
- Login JWT; pendaftaran selalu membuat pengguna biasa (role di body request diabaikan)
- Endpoint tulis khusus admin
- Allow-list CORS, header keamanan, pembatasan percobaan login, Swagger UI di `/api-docs`
- Pesan galat server tidak membocorkan detail internal
- Data demo terisi otomatis saat database kosong
- Tes integrasi dengan test runner bawaan Node
- Semua pesan dalam bahasa Indonesia

## Menjalankan

```bash
npm install
cp .env.example .env      # opsional, nilai bawaan sudah jalan
npm start                 # http://localhost:8084
npm test
```

Akun demo (fiktif): `admin@example.com` / `Admin#12345` dan `demo@example.com` / `Demo#12345`.

| Pengaturan | Fungsi |
| --- | --- |
| `JWT_SECRET` | Wajib di produksi, mis. `openssl rand -hex 32` |
| `CORS_ORIGINS` | Daftar origin browser yang boleh memanggil API, dipisah koma |
| `DB_DIALECT=postgres` + `DB_*` | Pakai PostgreSQL, bukan SQLite |
| `SEED_DEMO=false` | Jangan muat data demo |

## Bagian dari

`apphub-frontend` (etalase React) dan `apphub-admin` (back office Laravel) memakai API / model data yang sama.
