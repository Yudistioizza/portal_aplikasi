require("dotenv").config();

// SQLite by default so the API runs with zero setup.
// Set DB_DIALECT=postgres and the DB_* variables for a real database.
const dialect = process.env.DB_DIALECT || "sqlite";

module.exports = {
  dialect,
  storage: process.env.DB_STORAGE || "./data/apphub.sqlite",
  HOST: process.env.DB_HOST || "127.0.0.1",
  PORT: Number(process.env.DB_PORT || 5432),
  USER: process.env.DB_USER || "postgres",
  PASSWORD: process.env.DB_PASSWORD || "",
  DB: process.env.DB_NAME || "apphub",
  pool: { max: 5, min: 0, acquire: 30000, idle: 10000 },
};
