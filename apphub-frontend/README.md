# AppHub Frontend

Etalase publik katalog aplikasi AppHub, dibangun dengan **React**, **React Router**, **Axios** dan tema CSS responsif buatan sendiri.

## Fitur

- Katalog aplikasi dengan pencarian dan filter (kategori bisnis, tipe website), kartu dalam grid, dan paginasi
- Terhubung ke `apphub-api`; **otomatis memakai data demo bawaan** bila API tidak bisa dijangkau, sehingga situs selalu tampil (termasuk di hosting statis)
- Antarmuka dalam bahasa Indonesia

## Menjalankan

```bash
npm install
cp .env.example .env
npm start            # http://localhost:3000
npm run build        # hasil produksi di build/
```

| Pengaturan | Fungsi |
| --- | --- |
| `REACT_APP_API_URL` | Alamat `apphub-api` (bawaan `http://localhost:8084`) |
| `REACT_APP_USE_DEMO_DATA=true` | Tidak memanggil API sama sekali, selalu pakai data demo (GitHub Pages / Netlify) |

Seluruh data fiktif.
