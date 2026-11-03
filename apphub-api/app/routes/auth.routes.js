const { verifySignUp } = require("../middleware");
const controller = require("../controllers/auth.controller");
const rateLimit = require("express-rate-limit");

const limiter = (max, message) =>
  rateLimit({
    windowMs: 15 * 60 * 1000,
    max,
    standardHeaders: true,
    legacyHeaders: false,
    message: { status: 429, error: message },
  });

module.exports = function (app) {
  /**
   * @swagger
   * /api/auth/signup:
   *   post:
   *     summary: Daftarkan pengguna (selalu dengan role "user")
   *     tags: [Auth]
   *   /api/auth/signin:
   *     post:
   *       summary: Masuk dan terima JWT
   *       tags: [Auth]
   */
  app.post(
    "/api/auth/signup",
    limiter(10, "Terlalu banyak percobaan pendaftaran, coba lagi nanti."),
    [verifySignUp.checkDuplicateUsernameOrEmail],
    controller.signup
  );

  app.post("/api/auth/signin", limiter(10, "Terlalu banyak percobaan masuk, coba lagi setelah 15 menit."), controller.signin);
};
