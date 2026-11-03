import React, { useState, useEffect } from "react";
import { getWebsiteTypes, getBusinessCategories } from "../services/api";

const FilterBar = ({ onFilter, resetFilters }) => {
  const [websiteTypes, setWebsiteTypes] = useState([]);
  const [businessCategories, setBusinessCategories] = useState([]);
  const [selectedWebsiteType, setSelectedWebsiteType] = useState("");
  const [selectedBusinessCategory, setSelectedBusinessCategory] = useState("");

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [websiteTypeData, businessCategoryData] = await Promise.all([
          getWebsiteTypes(),
          getBusinessCategories(),
        ]);
        
        setWebsiteTypes(websiteTypeData);
        setBusinessCategories(businessCategoryData);
      } catch (error) {
        console.error("Gagal memuat opsi filter:", error);
      }
    };

    fetchData();
  }, []);

  const handleWebsiteTypeChange = (e) => {
    const value = e.target.value;
    setSelectedWebsiteType(value);
    onFilter(value, selectedBusinessCategory); // Memanggil onFilter dari parent
  };

  const handleBusinessCategoryChange = (e) => {
    const value = e.target.value;
    setSelectedBusinessCategory(value);
    onFilter(selectedWebsiteType, value); // Memanggil onFilter dari parent
  };

  const handleResetFilters = () => {
    setSelectedWebsiteType(""); // Reset Website Type
    setSelectedBusinessCategory(""); // Reset Business Category
    resetFilters(); // Memanggil fungsi reset dari parent untuk reset pencarian dan filter
  };

  return (
    <>
      <div
        className="filter-bar"
        style={{
          display: "flex",
          gap: "0.5rem",
          alignItems: "center",
        }}
      >
        {/* Dropdown dan Tombol Reset */}
        <select
          value={selectedWebsiteType}
          onChange={handleWebsiteTypeChange}
          className="gotham-regular"
          style={{
            padding: "0.2rem",
            borderRadius: "5px",
            minWidth: "150px",
          }}
        >
          <option value="">Semua tipe website</option>
          {websiteTypes.map((type) => (
            <option key={type.id} value={type.id}>
              {type.name || "N/A"}
            </option>
          ))}
        </select>
  
        <select
          value={selectedBusinessCategory}
          onChange={handleBusinessCategoryChange}
          className="gotham-regular"
          style={{
            padding: "0.2rem",
            borderRadius: "5px",
            minWidth: "150px",
          }}
        >
          <option value="">Semua kategori bisnis</option>
          {businessCategories.map((category) => (
            <option key={category.id} value={category.id}>
              {category.name || "N/A"}
            </option>
          ))}
        </select>
  
        <button
          onClick={handleResetFilters}
          className="btn-reset gotham-regular"
          style={{
            padding: "0.2rem",
            borderRadius: "5px",
            minWidth: "120px",
            fontSize: "x-small",
          }}
        >
          Atur Ulang Filter
        </button>
      </div>
  
      {/* DITARUH DI SINI! Filter Aktif */}
      {(selectedWebsiteType || selectedBusinessCategory) && (
        <div className="filter-description">
          <p style={{
            fontSize: "12px",
          }}>
            {selectedWebsiteType && (
              <>
                {" "}
                <span className="gotham-regular"></span>
                Tipe website{" "}
                <strong className="gotham-bold">
                  {
                    websiteTypes.find(
                      (w) => String(w.id) === selectedWebsiteType
                    )?.name || "Tidak Diketahui"
                  }
                </strong>
              </>
            )}
            {selectedBusinessCategory && (
              <>
                {selectedWebsiteType && ", "}
                <span className="gotham-regular"></span>
                Kategori bisnis{" "}
                <strong className="gotham-bold">
                  {
                    businessCategories.find(
                      (b) => String(b.id) === selectedBusinessCategory
                    )?.name || "Tidak Diketahui"
                  }
                </strong>
              </>
            )}
          </p>
        </div>
      )}
    </>
  );  
};

export default FilterBar;