require("dotenv").config();
const express = require("express");
const cors = require("cors");
const helmet = require("helmet");
const rateLimit = require("express-rate-limit");
const swaggerUi = require("swagger-ui-express");
const swaggerJsDoc = require("swagger-jsdoc");
const corsOptions = require("./app/middleware/corsOption");
const db = require("./app/models");
const { seedDemo } = require("./app/seed/demo");

const PORT = process.env.PORT || 8084;

const app = express();

app.use(helmet());
app.use(cors(corsOptions));
app.use(
  rateLimit({
    windowMs: 5 * 60 * 1000,
    max: 1000,
    standardHeaders: true,
    legacyHeaders: false,
    message: { status: 429, error: "Terlalu banyak permintaan, coba lagi nanti." },
  })
);
app.use(express.json({ limit: "100kb" }));
app.use(express.urlencoded({ extended: true }));

app.get("/", (req, res) => res.json({ message: "Selamat datang di API AppHub.", docs: "/api-docs" }));
app.get("/health", (req, res) => res.json({ status: "ok" }));

require("./app/routes/m_apps.routes")(app);
require("./app/routes/auth.routes")(app);

const swaggerDocs = swaggerJsDoc({
  swaggerDefinition: {
    openapi: "3.0.0",
    info: { title: "AppHub API", version: "1.0.0", description: "Katalog aplikasi dan autentikasi untuk portal AppHub." },
    servers: [{ url: `http://localhost:${PORT}` }],
  },
  apis: ["./app/routes/*.js"],
});
app.use("/api-docs", swaggerUi.serve, swaggerUi.setup(swaggerDocs));

app.use((req, res) => res.status(404).json({ message: "Tidak ditemukan." }));

// Last-resort handler: JSON, no stack traces.
app.use((err, req, res, next) => {
  if (err.message === "Not allowed by CORS") return res.status(403).json({ message: "Origin tidak diizinkan." });
  if (err.type === "entity.parse.failed") return res.status(400).json({ message: "Isi JSON tidak valid." });
  console.error(err);
  res.status(500).json({ message: "Terjadi kesalahan pada server." });
});

(async () => {
  await db.sequelize.sync();
  console.log("Database ready.");

  // First run on an empty database: load demo data (disable with SEED_DEMO=false).
  if (process.env.SEED_DEMO !== "false" && (await db.m_apps.count()) === 0) {
    console.log("Empty database, loading demo data:", await seedDemo());
  }

  app.listen(PORT, () => console.log(`AppHub API listening on http://localhost:${PORT}`));
})().catch((err) => {
  console.error("Failed to start:", err.message);
  process.exit(1);
});

module.exports = app;
