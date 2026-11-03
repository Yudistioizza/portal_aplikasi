# AGENTS.md: portal_aplikasi

Panduan untuk AI agent. Baca file ini, lalu README di tiap folder.
Penjelasan lengkap fungsi aplikasi: `docs/TENTANG_APLIKASI.md`.

## Ringkasan

Monorepo katalog aplikasi perusahaan dengan merek fiktif **AppHub** (data dan akun semuanya fiktif, domain `example.com`). Seluruh UI dan pesan Bahasa Indonesia. Dibuat dari aplikasi nyata yang sudah dibersihkan dari merek dan rahasia perusahaan; berjalan lokal tanpa layanan eksternal.

## Isi

| Folder | Peran | Stack | Port | Tes |
| --- | --- | --- | --- | --- |
| `apphub-frontend` | Etalase publik: cari dan filter aplikasi | React (CRA), Axios | 3000 | `npm run build` |
| `apphub-api` | REST API + JWT, Swagger `/api-docs` | Node, Express, Sequelize, SQLite/PostgreSQL | 8084 | `npm test` |
| `apphub-admin` | Back office: aplikasi, kategori, tipe, pengguna, role | Laravel 11, Spatie Permission, Tailwind | 8000 | `php artisan test` (34) |

Alur data: frontend memanggil `apphub-api`; bila API mati ia memakai `src/data/demoApps.json`. `apphub-admin` punya database sendiri (SQLite) dengan model data setara.

## Menjalankan

```bash
# API
cd apphub-api && npm install && npm start
# Frontend
cd apphub-frontend && npm install && npm start
# Admin
cd apphub-admin && composer install && cp .env.example .env && php artisan key:generate \
  && touch database/database.sqlite && php artisan migrate --seed && npm install && npm run build && php artisan serve
```

Akun demo: `admin@example.com` / `Admin#12345` (API dan admin). Admin juga: `editor@example.com`, `viewer@example.com` (sandi sama). API: `demo@example.com` / `Demo#12345`.

## Peta kode

- `apphub-api/app/{controllers,routes,middleware,models,seed}`, `server.js`, `test/`
- `apphub-frontend/src/{components,services,data,pages}`
- `apphub-admin/app/Http/Controllers`, `routes/web.php`, `resources/views`, `lang/id*`, `database/seeders`, `tests/Feature`

## Keputusan yang sudah diambil

- Login SSO Microsoft **dinonaktifkan** (diberi komentar): `routes/web.php`, `Auth/AzureController.php`, `EventServiceProvider.php`, `auth/login.blade.php`. Aktifkan kembali dengan membuka komentar.
- Pendaftaran mandiri ditutup; signup API selalu role `user`.
- Pesan error 500 tidak membocorkan detail internal.

## Aturan kerja

- Teks UI dan pesan: Bahasa Indonesia. Jangan menambahkan merek, nama perusahaan nyata, IP internal, atau rahasia.
- `DEMO_MODE`/seed hanya untuk demo; matikan dan ganti sandi di produksi.
- Jalankan tes tiap folder yang diubah sebelum menyatakan selesai.
- Jangan push ke `main` tanpa konfirmasi pemilik.

## Repo terkait

`portal_dashboard_api` (portal dashboard). Penjelasan lengkap: `docs/TENTANG_APLIKASI.md`; prompt onboarding: `docs/ONBOARDING_PROMPT.md`.
