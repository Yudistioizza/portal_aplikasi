const bcrypt = require("bcryptjs");
const db = require("../models");

// Semua isi di bawah ini adalah data demo fiktif untuk grup perusahaan rekaan.

const TYPES = ["Internal", "Eksternal", "Mobile"];

const CATEGORIES = ["SDM", "Keuangan", "Operasional", "Penjualan & Pemasaran", "TI & Keamanan", "Hukum & Kepatuhan"];

// name, description, type, categories, colour, glyph
const APPS = [
  ["HR Connect", "Layanan mandiri karyawan: pengajuan cuti, slip gaji, absensi, dan direktori pegawai.", "Internal", ["SDM"], "#2563eb", "HR"],
  ["e-Sign Hub", "Tanda tangan dan alur dokumen digital dengan jejak audit lengkap.", "Internal", ["Hukum & Kepatuhan", "Operasional"], "#7c3aed", "eS"],
  ["Procure Pro", "Permintaan pembelian, penawaran vendor, dan persetujuan dalam satu alur kerja.", "Internal", ["Keuangan", "Operasional"], "#0d9488", "PP"],
  ["FleetTrack", "Posisi kendaraan langsung, riwayat perjalanan, dan pengingat perawatan.", "Mobile", ["Operasional"], "#ea580c", "FT"],
  ["Insight Dashboards", "Dashboard business intelligence untuk pelaporan manajemen.", "Internal", ["Keuangan", "TI & Keamanan"], "#db2777", "BI"],
  ["Learning Academy", "Kursus, sertifikasi, dan jalur belajar untuk setiap peran.", "Internal", ["SDM"], "#16a34a", "LA"],
  ["Ticket Desk", "Ajukan dan pantau permintaan dukungan TI dengan target waktu respons.", "Internal", ["TI & Keamanan"], "#0891b2", "TD"],
  ["Visitor Pass", "Pendaftaran tamu di muka dan cetak tanda pengenal tamu di resepsionis.", "Eksternal", ["Operasional", "TI & Keamanan"], "#ca8a04", "VP"],
  ["Contract Vault", "Arsip kontrak yang bisa dicari, lengkap dengan pengingat perpanjangan.", "Internal", ["Hukum & Kepatuhan", "Keuangan"], "#4f46e5", "CV"],
  ["Expense Go", "Foto struk, ajukan klaim, dapatkan penggantian.", "Mobile", ["Keuangan", "SDM"], "#e11d48", "EG"],
  ["Asset Tracker", "Inventaris peralatan dengan label QR dan riwayat penanggung jawab.", "Internal", ["Operasional", "Keuangan"], "#65a30d", "AT"],
  ["Sales Pulse", "Pipeline, proyeksi, dan tindak lanjut pelanggan untuk tim penjualan.", "Eksternal", ["Penjualan & Pemasaran"], "#c026d3", "SP"],
  ["Safety Report", "Laporkan insiden dan nyaris celaka dari lapangan dalam hitungan detik.", "Mobile", ["Operasional", "Hukum & Kepatuhan"], "#dc2626", "SR"],
  ["Meeting Rooms", "Cari dan pesan ruang rapat; undangan langsung masuk ke kalender Anda.", "Internal", ["Operasional", "TI & Keamanan"], "#0f766e", "MR"],
];

const slug = (s) => s.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/(^-|-$)/g, "");

// A flat rounded square with the app's initials, as a self-contained data URI.
const logo = (color, text) => {
  const svg =
    `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">` +
    `<rect width="96" height="96" rx="22" fill="${color}"/>` +
    `<text x="48" y="60" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="38" font-weight="700" fill="#fff">${text}</text></svg>`;
  return "data:image/svg+xml;base64," + Buffer.from(svg).toString("base64");
};

async function seedDemo() {
  const types = await Promise.all(TYPES.map((name) => db.websiteType.create({ name })));
  const cats = await Promise.all(CATEGORIES.map((name) => db.businessCategory.create({ name })));

  for (const [i, [name, desc, type, categories, color, glyph]] of APPS.entries()) {
    const app = await db.m_apps.create({
      app_name: name,
      app_desc: desc,
      app_logo: logo(color, glyph),
      app_link: `https://apps.example.com/${slug(name)}`,
      app_order: i + 1,
      is_active: true,
      created_by: "seed",
      modified_by: "seed",
      website_type_id: types.find((t) => t.name === type).id,
    });
    await app.setBusinessCategories(cats.filter((c) => categories.includes(c.name)));
  }

  const roles = await Promise.all(["user", "moderator", "admin"].map((name, i) => db.role.create({ id: i + 1, name })));
  const admin = await db.user.create({ username: "admin", email: "admin@example.com", password: bcrypt.hashSync("Admin#12345", 10) });
  await admin.setRoles(roles);
  const demo = await db.user.create({ username: "demo", email: "demo@example.com", password: bcrypt.hashSync("Demo#12345", 10) });
  await demo.setRoles([roles[0]]);

  return { apps: APPS.length, categories: cats.length, types: types.length };
}

module.exports = { seedDemo };
