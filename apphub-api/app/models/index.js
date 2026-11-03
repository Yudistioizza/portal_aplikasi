const Sequelize = require("sequelize");
const dbConfig = require("../config/db.config.js");
const fs = require("fs");
const path = require("path");

if (dbConfig.dialect === "sqlite") {
  fs.mkdirSync(path.dirname(path.resolve(dbConfig.storage)), { recursive: true });
}

const sequelize =
  dbConfig.dialect === "sqlite"
    ? new Sequelize({ dialect: "sqlite", storage: dbConfig.storage, logging: false })
    : new Sequelize(dbConfig.DB, dbConfig.USER, dbConfig.PASSWORD, {
        host: dbConfig.HOST,
        port: dbConfig.PORT,
        dialect: dbConfig.dialect,
        logging: false,
        pool: dbConfig.pool,
      });

const db = { Sequelize, sequelize };

// Every model is loaded exactly once.
db.websiteType = require("./website_type.js")(sequelize, Sequelize);
db.businessCategory = require("./business_category.js")(sequelize, Sequelize.DataTypes);
db.applications = require("./m_apps.model.js")(sequelize, Sequelize);
db.m_apps = db.applications;
db.user = require("./users.model.js")(sequelize, Sequelize);
db.role = require("./roles.model.js")(sequelize, Sequelize);

new Set(Object.values(db)).forEach((model) => {
  if (model && typeof model.associate === "function") model.associate(db);
});

// Users <-> roles, needed by sign in and role checks.
db.role.belongsToMany(db.user, { through: "user_roles", foreignKey: "roleId", otherKey: "userId" });
db.user.belongsToMany(db.role, { through: "user_roles", foreignKey: "userId", otherKey: "roleId" });

db.ROLES = ["user", "moderator", "admin"];

module.exports = db;
