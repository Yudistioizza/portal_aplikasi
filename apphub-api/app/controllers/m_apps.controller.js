const db = require("../models");
const Op = db.Sequelize.Op;
const { websiteType: WebsiteType, businessCategory: BusinessCategory } = db;
const Apps = db.m_apps;

// PostgreSQL has a case-insensitive LIKE (ILIKE); SQLite's LIKE already ignores ASCII case.
const likeOp = db.sequelize.getDialect() === "postgres" ? Op.iLike : Op.like;

// Logos are stored as a data URI, or as raw base64 / bytes from older databases.
const logoUri = (value) => {
  if (!value) return null;
  if (typeof value === "string" && value.startsWith("data:")) return value;
  const base64 = Buffer.isBuffer(value) ? value.toString("base64") : String(value);
  return `data:image/png;base64,${base64}`;
};

const present = (row) => {
  const json = row.toJSON ? row.toJSON() : row;
  return { ...json, app_logo: logoUri(json.app_logo) };
};

const fail = (res, err, message) => {
  console.error(message, err);
  return res.status(500).json({ message });
};

// GET /api/apps/getAll
exports.findAll = async (req, res) => {
  try {
    const data = await Apps.findAll({ order: [["app_order", "ASC"]] });
    if (!data.length) return res.status(404).json({ message: "Data tidak ditemukan." });
    res.json(data.map(present));
  } catch (err) {
    fail(res, err, "Terjadi kesalahan saat mengambil data.");
  }
};

// GET /api/apps/getBySearch?app_name=&app_desc=
exports.findAllSearch = async (req, res) => {
  try {
    const { app_name = "", app_desc = "" } = req.query;
    const where = {};
    if (String(app_name).trim()) where.app_name = { [likeOp]: `%${String(app_name).trim()}%` };
    if (String(app_desc).trim()) where.app_desc = { [likeOp]: `%${String(app_desc).trim()}%` };

    const data = await Apps.findAll({
      attributes: ["app_name", "app_desc", "app_logo", "app_link"],
      where,
      order: [["app_order", "ASC"]],
    });
    res.json({ success: true, formattedData: data.map(present) });
  } catch (err) {
    fail(res, err, "Terjadi kesalahan saat mengambil data.");
  }
};

// GET /api/apps/website-types
exports.getWebsiteTypes = async (req, res) => {
  try {
    res.status(200).json(await WebsiteType.findAll({ attributes: ["id", "name"], order: [["id", "ASC"]] }));
  } catch (err) {
    fail(res, err, "Gagal memuat tipe website.");
  }
};

// GET /api/apps/business-categories
exports.getBusinessCategories = async (req, res) => {
  try {
    res.status(200).json(await BusinessCategory.findAll({ attributes: ["id", "name"], order: [["id", "ASC"]] }));
  } catch (err) {
    fail(res, err, "Gagal memuat kategori bisnis.");
  }
};

// GET /api/apps/filter?website_type_id=&business_category_id=
exports.getFilteredApps = async (req, res) => {
  const websiteTypeId = Number.parseInt(req.query.website_type_id, 10);
  const categoryId = Number.parseInt(req.query.business_category_id, 10);

  try {
    let results;

    if (Number.isInteger(categoryId)) {
      // Apps linked to a category go through the join table.
      const sql = `
        SELECT m.* FROM m_apps m
        JOIN app_business_categories abc ON m.id = abc.app_id
        WHERE abc.business_category_id = ?
        ${Number.isInteger(websiteTypeId) ? "AND m.website_type_id = ?" : ""}
        ORDER BY m.app_order ASC`;
      const replacements = [categoryId];
      if (Number.isInteger(websiteTypeId)) replacements.push(websiteTypeId);
      results = await db.sequelize.query(sql, { replacements, type: db.Sequelize.QueryTypes.SELECT });
    } else {
      results = await Apps.findAll({
        where: Number.isInteger(websiteTypeId) ? { website_type_id: websiteTypeId } : {},
        include: [{ model: WebsiteType, as: "websiteType", required: false }],
        order: [["app_order", "ASC"]],
      });
    }

    res.json(results.map(present));
  } catch (err) {
    fail(res, err, "Gagal memfilter aplikasi.");
  }
};
