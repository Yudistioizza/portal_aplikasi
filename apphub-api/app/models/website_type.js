// app/models/website_type.js
module.exports = (sequelize, Sequelize) => {
  const WebsiteType = sequelize.define("WebsiteType", {
    id: {
      type: Sequelize.BIGINT,
      autoIncrement: true,
      primaryKey: true
    },
    name: {
      type: Sequelize.STRING(255),
      allowNull: false
    },
    status: {
      type: Sequelize.BOOLEAN,
      defaultValue: true
    },
    created_by: {
      type: Sequelize.BIGINT,
      allowNull: true
    },
    modified_by: {
      type: Sequelize.BIGINT,
      allowNull: true
    },
    deleted_at: {
      type: Sequelize.DATE,
      allowNull: true
    }
  }, {
    tableName: "website_types",
    timestamps: true,
    createdAt: 'created_at',
    updatedAt: 'updated_at',
    paranoid: true,
    deletedAt: 'deleted_at'
  });

  WebsiteType.associate = (models) => {
    WebsiteType.hasMany(models.applications, {
      foreignKey: "website_type_id",
      as: "apps"
    });
  };  

  return WebsiteType;
};