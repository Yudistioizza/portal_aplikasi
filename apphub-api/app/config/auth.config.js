const crypto = require("crypto");
require("dotenv").config();

let secret = process.env.JWT_SECRET;

if (!secret) {
  if (process.env.NODE_ENV === "production") {
    throw new Error("JWT_SECRET must be set in production.");
  }
  // Development: a random secret per process. Tokens stop working after a restart, which is fine locally.
  secret = crypto.randomBytes(32).toString("hex");
  console.warn("JWT_SECRET not set; using a temporary secret for this run.");
}

module.exports = { secret, expiresIn: Number(process.env.JWT_EXPIRES_IN || 86400) };
