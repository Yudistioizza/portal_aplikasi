const controller = require("../controllers/m_apps.controller");

/**
 * @swagger
 * tags:
 *   - name: Apps
 *     description: Katalog aplikasi yang tampil di portal
 */
module.exports = (app) => {
  /**
   * @swagger
   * /api/apps/getAll:
   *   get:
   *     summary: Daftar seluruh aplikasi, diurutkan menurut app_order
   *     tags: [Apps]
   *     responses:
   *       200: { description: Daftar aplikasi; logo berupa data URI }
   *       404: { description: Belum ada aplikasi }
   */
  app.get("/api/apps/getAll", controller.findAll);

  /**
   * @swagger
   * /api/apps/getBySearch:
   *   get:
   *     summary: Cari aplikasi berdasarkan nama atau deskripsi
   *     tags: [Apps]
   *     parameters:
   *       - { in: query, name: app_name, schema: { type: string } }
   *       - { in: query, name: app_desc, schema: { type: string } }
   *     responses:
   *       200: { description: "{ success, formattedData: [] }" }
   */
  app.get("/api/apps/getBySearch", controller.findAllSearch);

  /**
   * @swagger
   * /api/apps/filter:
   *   get:
   *     summary: Filter aplikasi berdasarkan tipe website dan/atau kategori bisnis
   *     tags: [Apps]
   *     parameters:
   *       - { in: query, name: website_type_id, schema: { type: integer } }
   *       - { in: query, name: business_category_id, schema: { type: integer } }
   *     responses:
   *       200: { description: Daftar aplikasi }
   */
  app.get("/api/apps/filter", controller.getFilteredApps);

  /**
   * @swagger
   * /api/apps/website-types:
   *   get:
   *     summary: Daftar tipe website
   *     tags: [Apps]
   *     responses:
   *       200: { description: "[{ id, name }]" }
   */
  app.get("/api/apps/website-types", controller.getWebsiteTypes);

  /**
   * @swagger
   * /api/apps/business-categories:
   *   get:
   *     summary: Daftar kategori bisnis
   *     tags: [Apps]
   *     responses:
   *       200: { description: "[{ id, name }]" }
   */
  app.get("/api/apps/business-categories", controller.getBusinessCategories);
};
