<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\MApps;
use App\Models\WebsiteType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Fictional application catalogue. Mirrors the data in apphub-api so the two demos agree. */
class DemoCatalogueSeeder extends Seeder
{
    private const TYPES = ['Internal', 'Eksternal', 'Mobile'];

    private const CATEGORIES = ['SDM', 'Keuangan', 'Operasional', 'Penjualan & Pemasaran', 'TI & Keamanan', 'Hukum & Kepatuhan'];

    // name, description, type, categories, colour, glyph
    private const APPS = [
        ['HR Connect', 'Layanan mandiri karyawan: pengajuan cuti, slip gaji, absensi, dan direktori pegawai.', 'Internal', ['SDM'], '#2563eb', 'HR'],
        ['e-Sign Hub', 'Tanda tangan dan alur dokumen digital dengan jejak audit lengkap.', 'Internal', ['Hukum & Kepatuhan', 'Operasional'], '#7c3aed', 'eS'],
        ['Procure Pro', 'Permintaan pembelian, penawaran vendor, dan persetujuan dalam satu alur kerja.', 'Internal', ['Keuangan', 'Operasional'], '#0d9488', 'PP'],
        ['FleetTrack', 'Posisi kendaraan langsung, riwayat perjalanan, dan pengingat perawatan.', 'Mobile', ['Operasional'], '#ea580c', 'FT'],
        ['Insight Dashboards', 'Dashboard business intelligence untuk pelaporan manajemen.', 'Internal', ['Keuangan', 'TI & Keamanan'], '#db2777', 'BI'],
        ['Learning Academy', 'Kursus, sertifikasi, dan jalur belajar untuk setiap peran.', 'Internal', ['SDM'], '#16a34a', 'LA'],
        ['Ticket Desk', 'Ajukan dan pantau permintaan dukungan TI dengan target waktu respons.', 'Internal', ['TI & Keamanan'], '#0891b2', 'TD'],
        ['Visitor Pass', 'Pendaftaran tamu di muka dan cetak tanda pengenal tamu di resepsionis.', 'Eksternal', ['Operasional', 'TI & Keamanan'], '#ca8a04', 'VP'],
        ['Contract Vault', 'Arsip kontrak yang bisa dicari, lengkap dengan pengingat perpanjangan.', 'Internal', ['Hukum & Kepatuhan', 'Keuangan'], '#4f46e5', 'CV'],
        ['Expense Go', 'Foto struk, ajukan klaim, dapatkan penggantian.', 'Mobile', ['Keuangan', 'SDM'], '#e11d48', 'EG'],
        ['Asset Tracker', 'Inventaris peralatan dengan label QR dan riwayat penanggung jawab.', 'Internal', ['Operasional', 'Keuangan'], '#65a30d', 'AT'],
        ['Sales Pulse', 'Pipeline, proyeksi, dan tindak lanjut pelanggan untuk tim penjualan.', 'Eksternal', ['Penjualan & Pemasaran'], '#c026d3', 'SP'],
        ['Safety Report', 'Laporkan insiden dan nyaris celaka dari lapangan dalam hitungan detik.', 'Mobile', ['Operasional', 'Hukum & Kepatuhan'], '#dc2626', 'SR'],
        ['Meeting Rooms', 'Cari dan pesan ruang rapat; undangan langsung masuk ke kalender Anda.', 'Internal', ['Operasional', 'TI & Keamanan'], '#0f766e', 'MR'],
    ];

    public function run(): void
    {
        $types = collect(self::TYPES)->mapWithKeys(fn ($n) => [$n => WebsiteType::create(['name' => $n, 'status' => true])]);
        $cats = collect(self::CATEGORIES)->mapWithKeys(fn ($n) => [$n => BusinessCategory::create(['name' => $n, 'status' => true])]);

        foreach (self::APPS as $i => [$name, $desc, $type, $categories, $color, $glyph]) {
            $app = MApps::create([
                'app_name' => $name,
                'app_desc' => $desc,
                'app_logo' => $this->logo($color, $glyph),
                'app_link' => 'https://apps.example.com/' . Str::slug($name),
                'app_order' => $i + 1,
                'is_active' => true,
                'created_by' => 'seed',
                'website_type_id' => $types[$type]->id,
            ]);
            $app->status = 'active';
            $app->save();
            $app->businessCategories()->sync(collect($categories)->map(fn ($c) => $cats[$c]->id)->all());
        }
    }

    private function logo(string $color, string $text): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">'
            . '<rect width="96" height="96" rx="22" fill="' . $color . '"/>'
            . '<text x="48" y="60" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="38" font-weight="700" fill="#fff">' . $text . '</text></svg>';
    }
}
