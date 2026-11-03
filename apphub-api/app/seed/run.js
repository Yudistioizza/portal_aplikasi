// npm run seed: wipe the database and load the demo data.
const db = require("../models");
const { seedDemo } = require("./demo");

(async () => {
  await db.sequelize.sync({ force: true });
  const counts = await seedDemo();
  console.log("Seeded:", counts);
  await db.sequelize.close();
})().catch((err) => {
  console.error(err);
  process.exit(1);
});
