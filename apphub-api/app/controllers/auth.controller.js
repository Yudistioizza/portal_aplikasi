const db = require("../models");
const config = require("../config/auth.config");
const jwt = require("jsonwebtoken");
const bcrypt = require("bcryptjs");

const { user: User, role: Role } = db;

// Self-service sign up always creates a plain "user". Elevated roles are granted by an admin,
// never by a field in the request body.
exports.signup = async (req, res) => {
  const { username, email, password } = req.body || {};
  if (!username || !email || !password || String(password).length < 8) {
    return res.status(400).send({ message: "Username, email, dan password minimal 8 karakter wajib diisi." });
  }

  try {
    const user = await User.create({ username, email, password: bcrypt.hashSync(password, 10) });
    const role = await Role.findOne({ where: { name: "user" } });
    if (role) await user.setRoles([role]);
    res.send({ message: "Pengguna berhasil didaftarkan." });
  } catch (err) {
    console.error(err);
    res.status(500).send({ message: "Terjadi kesalahan pada server." });
  }
};

exports.signin = async (req, res) => {
  const { username, password } = req.body || {};
  if (!username || !password) return res.status(400).send({ message: "Username dan password wajib diisi." });

  try {
    const user = await User.findOne({ where: { username } });
    // Same answer for "no such user" and "wrong password" so usernames cannot be probed.
    if (!user || !bcrypt.compareSync(password, user.password)) {
      return res.status(401).send({ accessToken: null, message: "Username atau password salah." });
    }

    const token = jwt.sign({ id: user.id }, config.secret, { algorithm: "HS256", expiresIn: config.expiresIn });
    const roles = await user.getRoles();

    res.status(200).send({
      id: user.id,
      username: user.username,
      email: user.email,
      roles: roles.map((r) => "ROLE_" + r.name.toUpperCase()),
      accessToken: token,
    });
  } catch (err) {
    console.error(err);
    res.status(500).send({ message: "Terjadi kesalahan pada server." });
  }
};
