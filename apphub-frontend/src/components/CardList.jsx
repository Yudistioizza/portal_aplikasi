import React, { useState, useEffect } from "react";
import { getCards, getFilteredCards, isDemoMode } from "../services/api";
import FilterBar from "./FilterBar";

const CardList = () => {
  const [filteredCards, setFilteredCards] = useState([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [filters, setFilters] = useState({
    websiteTypeId: "",
    businessCategoryId: "",
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Ambil data awal aplikasi (semua aplikasi)
  useEffect(() => {
    const fetchCards = async () => {
      try {
        const cardsData = await getCards(); // Mengambil semua data kartu
        setFilteredCards(cardsData); // Menyimpan data kartu yang belum difilter
      } catch (err) {
        console.error("Error fetching data:", err);
        setError(err.message || "Gagal memuat data");
      } finally {
        setLoading(false);
      }
    };

    fetchCards();
  }, []);

  // Filter berdasarkan kategori dan pencarian
  useEffect(() => {
    const fetchFilteredCardsData = async () => {
      setLoading(true);
      try {
        const filtered = await getFilteredCards(filters, searchTerm);
        setFilteredCards(filtered);
        setError(null); // hanya reset error jika sukses
      } catch (err) {
        console.error("Failed to fetch filtered apps:", err);
        setError("Tidak ada aplikasi yang cocok dengan filter ini");
      } finally {
        setLoading(false);
      }
    };
  
    fetchFilteredCardsData();
  }, [filters, searchTerm]);
  

  // Handler untuk pencarian keyword
  const handleSearch = (e) => {
    setSearchTerm(e.target.value.toLowerCase());
  };

  // Handler ketika filter dropdown berubah
  const onFilter = (websiteTypeId, businessCategoryId) => {
    setFilters({ websiteTypeId, businessCategoryId });
  };

  // Reset semua filter
  const resetFilters = () => {
    setSearchTerm("");
    setFilters({
      websiteTypeId: "",
      businessCategoryId: "",
    });
  };


  return (
    <section id="services" className="services section">
      <div className="container section-title" data-aos="fade-up">
      <h1 className="brand-title">
          <span className="brand-a">App</span><span className="brand-b">Hub</span>
        </h1>
        <p className="ui-text" style={{ margin: "0 0 1rem" }}>Satu pintu untuk semua aplikasi internal.</p>
        {isDemoMode() && (
          <div className="demo-banner">
            Menampilkan data demo bawaan. Jalankan API (<code>apphub-api</code>) untuk memuat data dari server.
          </div>
        )}
        {/* Input pencarian */}
        <input
          className="gotham-regular"
          type="text"
          placeholder="Cari aplikasi..."
          value={searchTerm}
          onChange={handleSearch}
          style={{
            padding: "0.2rem",
            width: "60%",
            borderRadius: "5px",
            border: "1px solid #ccc",
          }}
        />

        {/* Komponen Filter */}
        <FilterBar onFilter={onFilter} resetFilters={resetFilters} />

        {/* Status: Loading / Error / Tidak Ditemukan */}
        {loading ? (
          <div className="loading-container">
            <div className="spinner" />
            <p style={{ marginTop: "1rem", fontSize: "1.1rem" }}>
              Memuat aplikasi...
            </p>
          </div>
        ) : error ? (
          <div className="center-message error-message">{error}</div>
        ) : filteredCards.length === 0 ? (
          <div className="center-message">
            Tidak ada aplikasi yang cocok dengan filter ini.
          </div>
        ) : null}

        {/* Menampilkan kartu aplikasi */}
        {!loading && !error && filteredCards.length > 0 && (
          <div className="container">
            <div className="row g-5">
              {filteredCards.map((card, index) => (
                <div
                  key={index}
                  className="col-lg-4 col-md-6"
                  data-aos="fade-up"
                  data-aos-delay="100"
                >
                  <a
                    href={card.app_link}
                    target="_blank"
                    rel="noopener noreferrer"
                    style={{ textDecoration: "none", color: "inherit" }}
                  >
                    <div className="service-item item-cyan position-relative">
                      <img
                        src={card.app_logo}
                        alt={card.app_name}
                        style={{
                          width: "100px",
                          height: "40px",
                          objectFit: "contain",
                          marginBottom: "5px",
                        }}
                      />
                      <h5 style={{ fontWeight: "bold" }}>{card.app_name}</h5>
                      <p>{card.app_desc}</p>
                    </div>
                  </a>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </section>
  );
};

export default CardList;