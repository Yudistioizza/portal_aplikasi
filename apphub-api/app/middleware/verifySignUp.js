const db = require("../models");
const { Op } = db.Sequelize;
const User = db.user;

const checkDuplicateUsernameOrEmail = async (req, res, next) => {
  const { username, email } = req.body || {};
  if (!username || !email) return next(); // validation happens in the controller

  try {
    const existing = await User.findOne({ where: { [Op.or]: [{ username }, { email }] } });
    if (existing) {
      const field = existing.username === username ? "Username" : "Email";
      return res.status(400).send({ message: `${field} sudah digunakan.` });
    }
    next();
  } catch (err) {
    console.error(err);
    res.status(500).send({ message: "Terjadi kesalahan pada server." });
  }
};

module.exports = { checkDuplicateUsernameOrEmail };
