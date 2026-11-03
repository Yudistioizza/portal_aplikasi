import React from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import CardList from "../components/CardList";

const Home = () => (
  <div>
    <Header />
    <main className="main">
      <CardList />
    </main>
    <Footer />
  </div>
);

export default Home;
