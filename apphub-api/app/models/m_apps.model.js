module.exports = (sequelize, Sequelize) => {
  const m_apps = sequelize.define("m_apps", {
    app_name: {
      type: Sequelize.STRING,
      allowNull: false
    },
    app_desc: {
      type: Sequelize.STRING,
      allowNull: true
    },
    app_logo: {
      type: Sequelize.TEXT,
      allowNull: true
    },
    app_order: {
      type: Sequelize.INTEGER,
      allowNull: false
    },
    is_active: {
      type: Sequelize.BOOLEAN,
      allowNull: true
    },
    created_by: {
      type: Sequelize.STRING,
      allowNull: true
    },
    modified_by: {
      type: Sequelize.STRING,
      allowNull: true
    },
    app_link: {
      type: Sequelize.STRING,
      allowNull: false
    }
  }, {
    timestamps: true,
    createdAt: "created_at",
    updatedAt: "updated_at",
    paranoid: false, 
  });

  m_apps.associate = (models) => {
    m_apps.belongsTo(models.websiteType, {
      foreignKey: "website_type_id",
      as: "websiteType"
    });

    m_apps.belongsToMany(models.businessCategory, {
      through: "app_business_categories",
      foreignKey: "app_id",
      otherKey: "business_category_id",
      as: "businessCategories"
    });
  };

  return m_apps;
};