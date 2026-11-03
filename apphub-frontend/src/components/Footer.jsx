import React from "react";

// Grup dan kontak fiktif untuk demo.
const groupCompanies = ["Nusa Energi", "Samudra Logistik", "Garuda Teknologi", "Borneo Material", "Kencana Properti", "Arunika Finansial"];

const socials = [
  { name: "Instagram", icon: "/assets/img/icons8-instagram.svg" },
  { name: "Facebook", icon: "/assets/img/icons8-facebook.svg" },
  { name: "LinkedIn", icon: "/assets/img/icons8-linkedin (1).svg" },
  { name: "YouTube", icon: "/assets/img/icons8-youtube (1).svg" },
  { name: "X", icon: "/assets/img/icons8-x.svg" },
];

const Footer = () => (
  <footer id="footer" className="footer dark-background">
    <div className="footer-content container mx-auto">
      <div className="left-column">
        <div className="logo mb-4">
          <a href="/" className="d-flex align-items-center">
            <img src="/assets/img/apphub-logo-white.svg" alt="AppHub" />
          </a>
        </div>

        <div className="text-content mb-3">
          <p>
            Nusantara Group<br />
            Jl. Contoh Raya No. 123<br />
            Jakarta Selatan,<br />
            DKI Jakarta 12345, Indonesia
          </p>
        </div>

        <div className="contact-info">
          <p>
            Telp: (+6221) 555-0100<br />
            Email: <a href="mailto:hello@example.com">hello@example.com</a>
          </p>
        </div>
      </div>

      <div className="right-column">
        <div className="member-group mb-5">
          <h3 className="title">Anggota grup</h3>
          <ul className="member-list">
            {groupCompanies.map((name) => (
              <li key={name}><a href="https://example.com" className="member-link">PT {name}</a></li>
            ))}
          </ul>
        </div>

        <div className="social-media">
          <h3 className="title">Media Sosial</h3>
          <ul className="social-icons">
            {socials.map((s) => (
              <li key={s.name}>
                <a href="https://example.com" className="social-link" target="_blank" rel="noopener noreferrer">
                  <img src={s.icon} alt="" /> {s.name}
                </a>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>

    <div className="copyright">
      <div className="container">
        <p className="text-center">Hak cipta © {new Date().getFullYear()} AppHub demo. Seluruh hak dilindungi.</p>
      </div>
    </div>
  </footer>
);

export default Footer;
