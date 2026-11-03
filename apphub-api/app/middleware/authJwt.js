const jwt = require("jsonwebtoken");
const config = require("../config/auth.config.js");
const db = require("../models");
const User = db.user;

// Accepts "Authorization: Bearer <token>" or the legacy "x-access-token" header.
const verifyToken = (req, res, next) => {
  const bearer = (req.headers.authorization || "").replace(/^Bearer\s+/i, "");
  const token = bearer || req.headers["x-access-token"];
  if (!token) return res.status(403).send({ message: "Token tidak ditemukan." });

  jwt.verify(token, config.secret, { algorithms: ["HS256"] }, (err, decoded) => {
    if (err) return res.status(401).send({ message: "Token tidak valid atau sudah kedaluwarsa." });
    req.userId = decoded.id;
    next();
  });
};

const hasRole = (name) => async (req, res, next) => {
  try {
    const user = await User.findByPk(req.userId);
    const roles = user ? await user.getRoles() : [];
    if (roles.some((r) => r.name === name)) return next();
    res.status(403).send({ message: `Dibutuhkan role ${name}.` });
  } catch (err) {
    console.error(err);
    res.status(500).send({ message: "Terjadi kesalahan pada server." });
  }
};

module.exports = { verifyToken, isAdmin: hasRole("admin"), isModerator: hasRole("moderator") };
