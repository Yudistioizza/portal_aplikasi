module.exports = (sequelize, DataTypes) => {
  const BusinessCategory = sequelize.define("businessCategory", { // Model name kecil camelCase
    id: {
      type: DataTypes.BIGINT,
      autoIncrement: true,
      primaryKey: true
    },
    name: {
      type: DataTypes.STRING(255),
      allowNull: false
    },
    status: {
      type: DataTypes.BOOLEAN,
      defaultValue: true
    },
    created_by: {
      type: DataTypes.BIGINT,
      allowNull: true
    },
    modified_by: {
      type: DataTypes.BIGINT,
      allowNull: true
    },
    deleted_at: {
      type: DataTypes.DATE,
      allowNull: true
    }
  }, {
    tableName: "business_categories",
    timestamps: true,
    createdAt: "created_at",
    updatedAt: "updated_at",
    paranoid: false,
    deletedAt: "deleted_at"
  });

  // Relasi
  BusinessCategory.associate = (models) => {
    BusinessCategory.belongsToMany(models.m_apps, {
      through: "app_business_categories",
      foreignKey: "business_category_id",
      otherKey: "app_id",
      as: "apps"
    });
  };

  return BusinessCategory;
};
