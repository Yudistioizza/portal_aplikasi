import React from 'react';
import ReactDOM from 'react-dom/client'; // Menggunakan react-dom/client untuk React 18
import App from './App';
import './index.css'; // Pastikan file CSS ada

// Membuat root dan merender aplikasi
const root = ReactDOM.createRoot(document.getElementById('root')); // Menggunakan createRoot dari react-dom/client
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

// Jika Anda ingin mengukur performa aplikasi, Anda dapat menambahkan kode untuk melaporkan hasilnya.
// Sebagai contoh:
// reportWebVitals(console.log); // Jika ingin mengukur performa dan log hasilnya
