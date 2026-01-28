<style>



:root {
  --ink: #0b0c0f;
  --muted: #6b7280;
  --accent: #095292;
  --radius: 18px;
  --bg-gradient: linear-gradient(180deg, #f8fafc 0%, #eef1f5 100%);
}

body {
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  background: var(--bg-gradient);
  color: var(--ink);
  margin: 0;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* HEADER */
header {
  padding: 20px 5%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(255,255,255,0.8);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 100;
}

/* BRAND LOGO SIZE FIX */
.brand-logo {
  height: 40px;          /* 👈 increase size (try 48–60px) */
  width: auto;           /* keep aspect ratio */
  object-fit: contain;
}


/* LOGIN BUTTON — SOLID RED */
.nav-btn.login-btn {
  background-color: #07468e;   /* deep red */
  color: #ffffff !important;
  border: 1px solid #07468e;
  font-weight: 700;
  padding: 8px 18px;
  border-radius: 10px;
  transition: all 0.25s ease;
}

.nav-btn.login-btn:hover {
  background-color: #044065;
  border-color: #044065;
  transform: translateY(-2px);
}

/* REGISTER BUTTON — OUTLINE RED */
.nav-btn.signup-btn {
  background-color: transparent;
  color: #07468e !important;
  border: 1px solid #07468e;
  font-weight: 700;
  padding: 8px 18px;
  border-radius: 10px;
  transition: all 0.25s ease;
}

.nav-btn.signup-btn:hover {
  background-color: #07468e;
  color: #ffffff !important;
  transform: translateY(-2px);
}


/* Optional: slightly bigger on desktop */
@media (min-width: 1024px) {
  .brand-logo {
    height: 62px;
  }
}


.brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 800;
  font-size: 18px;
}

.nav-links a {
  color: var(--ink);
  text-decoration: none;
  font-weight: 600;
  margin-left: 18px;
  padding: 8px 14px;
  border-radius: 10px;
  transition: .25s ease;
}

.nav-links a:hover {
  color: var(--accent);
}

/* NAV BUTTONS */
.nav-btn {
  font-weight: 700;
  background: #044c94;
  color: #fff !important;
  border: 1px solid #0f5ca0;
  padding: 8px 18px;
}

.nav-btn:hover {
  background: #0d263a;
  border-color: #0d263a;
  transform: translateY(-2px);
}

.signup-btn {
  background: transparent;
  color: #ffffff !important;
  border: 1px solid #075fa3;
}

.signup-btn:hover {
  background: #044065;
  color: #fff !important;
}

/* ===== HERO SECTION (Corrected, Left-Aligned) ===== */
.hero {
  position: relative;
  height: 60vh;
  background: url('/background.webp') center center / cover no-repeat;
  display: flex;
  align-items: center;           /* vertical center */
  justify-content: flex-start;   /* push text to the left */
  padding-left: 10%;             /* left margin */
  padding-right: 6%;
  text-align: left;              /* force left alignment */
  overflow: hidden;
  color: #fff;
}
/* LEFT SIDE OVERLAY WITH FADE USING #1c2d4a */




/* HERO CONTENT */
.hero-content {
  position: relative;
  z-index: 2;
  max-width: 520px;
  text-align: left !important;
}

.hero-content h1 {
  font-size: clamp(2.4rem, 4vw, 3.4rem);
  font-weight: 900;
  margin-bottom: 18px;
  line-height: 1.15;
  color: #fff;
}

.hero-content p {
  font-size: 1.15rem;
  opacity: 0.88;
  margin-bottom: 28px;
  line-height: 1.6;
}

/* HERO BUTTONS */
.hero-buttons {
  display: flex;
  gap: 14px;
  justify-content: flex-start;
}

.btn-primary {
  background: #fff;
  color: #0b0c0f;
  border: none;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: .25s ease;
}

.btn-primary:hover {
  background: #e5e7eb;
  transform: translateY(-2px);
}

.btn-outline {
  background: transparent;
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.7);
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: .25s ease;
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: translateY(-2px);
}

/* ===== FEATURES SECTION ===== */
.features {
  padding: 80px 20px;
  background: #fff;
  text-align: center;
}

.features h2 {
  font-size: 2rem;
  font-weight: 900;
  letter-spacing: -0.02em;
  margin-bottom: 40px;
  color: #0b0c0f;
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 32px;
  max-width: 1100px;
  margin: 0 auto;
}

.feature {
  background: #f9fafb;
  border-radius: 16px;
  padding: 28px 18px;
  border: 1px solid #eee;
  transition: .25s ease;
}

.feature:hover {
  transform: translateY(-4px);
  background: #fff;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.feature i {
  font-size: 30px;
  color: #0b0c0f;
  background: #eaf2ff;
  padding: 16px;
  border-radius: 12px;
}

/* FOOTER */
footer {
  text-align: center;
  padding: 20px;
  font-size: 14px;
  color: var(--muted);
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .hero {
    justify-content: center;
    padding-left: 20px;
    text-align: center;
  }

  .hero-left-overlay {
    width: 100%;
  }

  .hero-content {
    max-width: 100%;
    text-align: center !important;
  }

  .hero-buttons {
    justify-content: center;
  }

  header {
    flex-direction: column;
    gap: 10px;
  }
}


/* Base nav link style */
.nav-links a {
    font-size: 14px;
    margin-left: 18px;
    padding: 8px 14px;
    border-radius: 10px;
    text-decoration: none;
    transition: .25s ease;
}

/* Shared button style */
.nav-btn {
    font-weight: 700;
    background: #122b50;
    color: #fff !important;
    border: 1px solid #154b9d;
}

/* Hover effect */
.nav-btn:hover {
    background: #0d1f3a;
    border-color: #0d1f3a;
    transform: translateY(-2px);
}

/* Optional: differentiate Register slightly */
.signup-btn {
    background: transparent;
    color: #122b50 !important;
    border: 1px solid #122b50;
}

.signup-btn:hover {
    background: #07468e;
    color: #fff !important;
}
/* FEATURES SECTION — DARK THEME (#1c2d4a) */
.features {
  padding: 70px 5%;
  background: #0b4f7a;
  text-align: center;
}

.features h2 {
  font-weight: 800;
  font-size: 1.4rem;   /* smaller heading */
  margin-bottom: 35px;
  color: #ffffff;
  letter-spacing: -0.02em;
}

/* Grid layout */
.feature-grid {
  display: grid;
  gap: 22px;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  max-width: 1100px;
  margin: 0 auto;
}

/* Feature card */
.feature {
  background: rgba(255, 255, 255, 0.05);
  padding: 18px 14px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: 0.25s ease;
}

/* Hover effect */
.feature:hover {
  transform: translateY(-4px);
  background: rgba(255, 255, 255, 0.08);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Icons */
.feature i {
  font-size: 20px;       /* smaller icons */
  color: #fff;
  background: rgba(255, 255, 255, 0.15);
  padding: 12px;
  border-radius: 10px;
}

/* Titles */
.feature h5 {
  font-size: 0.95rem;    /* smaller title */
  color: #fff;
  font-weight: 700;
  margin-top: 14px;
}

/* Descriptions */
.feature p {
  font-size: 0.80rem;    /* smaller text */
  color: rgba(255, 255, 255, 0.75);
  margin-top: 6px;
  line-height: 1.45;
}
/* ABOUT SECTION */
.about-section {
  padding: 0;                 /* remove padding to allow image to reach edges */
  background: #ffffff;
  width: 100%;
}

.about-container {
  display: flex;
  align-items: stretch;       /* ensures equal height for text + image */
  justify-content: space-between;
  max-width: 100%;
  margin: 0 auto;
  min-height: 480px;
}

/* LEFT TEXT */
.about-text {
  flex: 1;
  padding: 80px 6%;
}

.about-text h2 {
  font-size: 2.2rem;
  font-weight: 900;
  margin-bottom: 20px;
  color: #1c2d4a;
}

.about-text p {
  font-size: 1rem;
  color: #555;
  line-height: 1.6;
  margin-bottom: 16px;
}

.about-text ul {
  margin-top: 10px;
  padding-left: 16px;
}

.about-text ul li {
  margin-bottom: 8px;
  font-size: 1rem;
  color: #1c2d4a;
  font-weight: 600;
}

/* RIGHT IMAGE — FULL HEIGHT AND FULL WIDTH */
.about-image {
  flex: 1.2;                  /* slightly larger than text */
  overflow: hidden;
}

.about-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;         /* makes image fill entire area */
  display: block;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .about-container {
    flex-direction: column;
  }
  .about-text {
    padding: 50px 20px;
    text-align: center;
  }
  .about-image img {
    height: 280px;
  }
}
/* ========== WHERE ONPAY CAN BE USED SECTION ========== */
.usage-section {
  background: #ffffff;
  padding: 90px 6%;
  text-align: center;
}

.usage-section h2 {
  font-size: 2.3rem;
  font-weight: 900;
  color: #1c2d4a;
  margin-bottom: 10px;
}

.usage-subtitle {
  font-size: 1rem;
  color: #6b7280;
  max-width: 680px;
  margin: 0 auto 50px;
  line-height: 1.6;
}

.usage-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 30px;
  max-width: 1100px;
  margin: 0 auto;
}

.usage-item {
  background: #f8fafc;
  padding: 28px;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  transition: all .25s ease;
}

.usage-item:hover {
  transform: translateY(-6px);
  background: #ffffff;
  box-shadow: 0 12px 25px rgba(0,0,0,0.08);
}

.usage-item i {
  font-size: 32px;
  color: #1c2d4a;
  padding: 14px;
  border-radius: 10px;
  background: #e9eef9;
  margin-bottom: 16px;
}

.usage-item h4 {
  font-size: 1.1rem;
  color: #1c2d4a;
  font-weight: 800;
  margin-bottom: 10px;
}

.usage-item p {
  font-size: 0.95rem;
  color: #555;
  line-height: 1.6;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .usage-section {
    padding: 60px 20px;
  }
}

/* ===============================
   🧠 ABOUT MULTIVERSE
================================ */
.about-section {
  padding: 100px 20px;
  background: #ffffff;
}

.about-container {
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 60px;
  align-items: center;
}

/* Badge */
.about-badge {
  display: inline-block;
  background: #111827;
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 6px 12px;
  border-radius: 999px;
  margin-bottom: 16px;
}

/* Text */
.about-text h2 {
  font-weight: 900;
  font-size: 2rem;
  letter-spacing: -0.02em;
  margin-bottom: 18px;
  color: #0b0c0f;
}

.about-text p {
  font-size: 1.05rem;
  color: #4b5563;
  line-height: 1.65;
  margin-bottom: 18px;
}

/* List */
.about-list {
  list-style: none;
  padding: 0;
  margin-top: 22px;
}

.about-list li {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.95rem;
  color: #374151;
  margin-bottom: 10px;
}

.about-list i {
  color: #525252;
}

/* ===============================
   🪟 VISUAL CARD
================================ */
.about-visual {
  display: flex;
  justify-content: center;
}

.about-card {
  position: relative;
  background: #054faa;
  border-radius: 18px;
  padding: 14px;
  box-shadow: 0 30px 60px rgba(0,0,0,0.25);
  max-width: 720px;
  width: 100%;
}

/* Floating badge on card */
.about-card-badge {
  position: absolute;
  top: -14px;
  left: 20px;
  background: #ffffff;
  color: #0b0c0f;
  font-size: 12px;
  font-weight: 800;
  padding: 6px 12px;
  border-radius: 999px;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 10px 20px rgba(12, 84, 177, 0.15);
}

.about-card img {
  width: 100%;
  height: auto;
  border-radius: 10px;
  display: block;
}

/* Hover polish */
.about-card:hover {
  transform: translateY(-4px);
  transition: transform .25s ease;
}





/* ================================
   Developer Section (Image Left)
   ================================ */

.dev-section {
  background: #ffffff;
  padding: 100px 6%;
}

.dev-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 50px;
  max-width: 1200px;
  margin: 0 auto;
}

/* LEFT IMAGE */
.dev-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 18px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* RIGHT TEXT */
.dev-text {
  max-width: 520px;
}

.dev-text h2 {
  font-size: 2.2rem;
  font-weight: 900;
  color: #1c2d4a;
  margin-bottom: 15px;
}

.dev-text p {
  font-size: 1rem;
  color: #555;
  line-height: 1.65;
  margin-bottom: 16px;
}

.dev-text ul {
  list-style: none;
  padding: 0;
  margin-top: 10px;
}

.dev-text li {
  font-size: 0.95rem;
  color: #1c2d4a;
  font-weight: 600;
  margin-bottom: 8px;
}

.dev-text li::before {
  content: "✔ ";
  color: #1c2d4a;
  font-weight: bold;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 900px) {
  .dev-container {
    flex-direction: column;
    text-align: center;
  }

  .dev-text {
    max-width: 100%;
  }

  .dev-image img {
    width: 100%;
    border-radius: 16px;
  }
}
/* ============= MODERN ONPAY FOOTER ============= */

.onpay-footer {
  background: #1c2d4a;
  color: #ffffff;
  padding: 70px 6% 40px;
  margin-top: 0;
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 50px;
  max-width: 1200px;
  margin: 0 auto;
}

/* Brand */
.footer-brand img {
  width: 180px;
  margin-bottom: 12px;
}

.footer-brand p {
  max-width: 280px;
  font-size: 0.9rem;
  opacity: 0.75;
  line-height: 1.5;
}

/* Links */
.footer-links h4 {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 12px;
  color: #ffffff;
}

.footer-links a {
  display: block;
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.75);
  text-decoration: none;
  margin-bottom: 8px;
  transition: 0.25s ease;
}

.footer-links a:hover {
  color: #ffffff;
  transform: translateX(4px);
}

/* Bottom Section */
.footer-bottom {
  text-align: center;
  margin-top: 50px;
  font-size: 0.85rem;
  opacity: 0.65;
  border-top: 1px solid rgba(255, 255, 255, 0.15);
  padding-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
    text-align: center;
  }

  .footer-links a:hover {
    transform: none;
  }
}

/* ============= MODERN ONPAY FOOTER ============= */

.onpay-footer {
  background: #1c2d4a;
  color: #ffffff;
  padding: 70px 6% 40px;
  margin-top: 0;
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 50px;
  max-width: 1200px;
  margin: 0 auto;
}

/* Brand */
.footer-brand img {
  width: 130px;
  margin-bottom: 12px;
}

.footer-brand p {
  max-width: 260px;
  font-size: 0.9rem;
  opacity: 0.75;
  line-height: 1.5;
}

/* Links */
.footer-links h4 {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 12px;
  color: #ffffff;
}

.footer-links a {
  display: block;
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.75);
  text-decoration: none;
  margin-bottom: 8px;
  transition: 0.25s ease;
}

.footer-links a:hover {
  color: #ffffff;
  transform: translateX(4px);
}

/* Contact section styling */
.contact-item {
  font-size: 0.88rem;
  margin-bottom: 10px;
  color: rgba(255, 255, 255, 0.85);
  display: flex;
  align-items: left;
  gap: 8px;
}

.contact-item i {
  color: #ffffff;
  font-size: 14px;
  opacity: 0.85;
}

/* Bottom Section */
.footer-bottom {
  text-align: left;
  margin-top: 50px;
  font-size: 0.85rem;
  opacity: 0.65;
  border-top: 1px solid rgba(255, 255, 255, 0.15);
  padding-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
    text-align: left;
  }

  .contact-item {
    justify-content: left;
  }

  .footer-links a:hover {
    transform: none;
  }
}

/* ===============================
   🧩 API & INTEGRATIONS
================================ */
.api-section {
  padding: 100px 20px;
  background: #f9fafb;
}

.api-container {
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 50px;
  align-items: center;
}

.api-badge {
  display: inline-block;
  background: #111827;
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 6px 12px;
  border-radius: 999px;
  margin-bottom: 16px;
}

.api-text h2 {
  font-weight: 900;
  font-size: 2rem;
  letter-spacing: -0.02em;
  margin-bottom: 18px;
  color: #0b0c0f;
}

.api-text p {
  font-size: 1.05rem;
  color: #4b5563;
  line-height: 1.6;
  margin-bottom: 22px;
}

.api-list {
  list-style: none;
  padding: 0;
  margin: 0 0 28px;
}

.api-list li {
  font-size: 0.95rem;
  color: #374151;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.api-list i {
  color: #404040;
}

/* Buttons */
.api-actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}

.btn-dark {
  background: #0b0c0f;
  color: #fff;
  padding: 12px 22px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all .2s ease;
}

.btn-dark:hover {
  background: #000;
  transform: translateY(-2px);
}

.btn-outline-dark {
  background: #fff;
  color: #0b0c0f;
  border: 1px solid #d1d5db;
  padding: 12px 22px;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all .2s ease;
}

.btn-outline-dark:hover {
  background: #f3f4f6;
  transform: translateY(-2px);
}

/* Code Block */
.api-code pre {
  background: #0b0c0f;
  color: #e5e7eb;
  padding: 24px;
  border-radius: 16px;
  font-size: 0.9rem;
  line-height: 1.6;
  overflow-x: auto;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}

.api-code .comment {
  color: #9ca3af;
}



/* ===== Multiverse Screening Features ===== */
.ms-features {
  padding: 90px 20px;
  background: #043f70;
  color: #fff;
}

.ms-features-inner {
  max-width: 1200px;
  margin: 0 auto;
}

.ms-features-header {
  text-align: center;
  max-width: 720px;
  margin: 0 auto 60px;
}

.section-badge {
  display: inline-block;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.18);
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 14px;
}

.ms-features-header h2 {
  font-size: 2.2rem;
  font-weight: 900;
  color: #ffffff;
  letter-spacing: -0.02em;
  margin-bottom: 14px;
}

.ms-features-header p {
  color: rgba(255,255,255,0.75);
  font-size: 1.05rem;
  line-height: 1.6;
}

/* Grid */
.ms-feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 28px;
}

/* Cards */
.ms-feature-card {
  background: #022c4f;
  border-radius: 18px;
  padding: 30px 24px;
  border: 1px solid rgba(255, 255, 255, 0.08);
  position: relative;
  transition: all .25s ease;
}

.ms-feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.5);
}

/* Icon */
.ms-feature-icon {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  background: rgba(255, 255, 255, 0.09);
  margin-bottom: 16px;
}

.ms-feature-icon i {
  font-size: 18px;
}

/* Text */
.ms-feature-card h5 {
  font-weight: 800;
    color: #ffffff;
      font-size: 14px;
  margin-bottom: 8px;
}

.ms-feature-card p {
  font-size: 12px;
  color: rgba(255,255,255,0.7);
  line-height: 1.6;
}

/* Chips */
.ms-feature-chip {
  display: inline-block;
  margin-top: 14px;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .05em;
  padding: 6px 12px;
  border-radius: 999px;
  background: rgba(21, 130, 255, 0.08);
  border: 1px solid rgba(255,255,255,0.18);
}

.ms-feature-chip.danger {
  background: rgba(48, 124, 255, 0.15);
  border-color: rgba(48, 131, 255, 0.4);
  color: #ffb4ad;
}

/* ===============================
   🔻 MULTIVERSE FOOTER
================================ */
.ms-footer {
  background: #02101a;
  color: #ffffff;
  padding: 70px 6% 30px;
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
}

.ms-footer-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 50px;
}

/* Brand */
.ms-footer-brand img {
  width: 170px;
  margin-bottom: 14px;
}

.ms-footer-brand p {
  font-size: 0.9rem;
  color: rgba(255,255,255,0.75);
  line-height: 1.6;
  max-width: 320px;
}

/* Links */
.ms-footer-links h4 {
  font-size: 0.95rem;
  font-weight: 800;
  margin-bottom: 14px;
  color: #ffffff;
}

.ms-footer-links a {
  display: block;
  font-size: 0.88rem;
  color: rgba(255,255,255,0.7);
  text-decoration: none;
  margin-bottom: 8px;
  transition: 0.25s ease;
}

.ms-footer-links a:hover {
  color: #ffffff;
  transform: translateX(4px);
}

/* Contact items */
.ms-footer-contact {
  display: flex;
  align-items: left;
  gap: 10px;
  font-size: 0.88rem;
  color: rgba(255,255,255,0.8);
  margin-bottom: 10px;
}

.ms-footer-contact i {
  font-size: 14px;
  color: #addaff;
}

/* Bottom */
.ms-footer-bottom {
  text-align: center;
  margin-top: 50px;
  padding-top: 18px;
  font-size: 0.82rem;
  color: rgba(255,255,255,0.55);
  border-top: 1px solid rgba(255, 255, 255, 0.12);
}

/* Responsive */
@media (max-width: 768px) {
  .ms-footer {
    padding: 50px 20px 30px;
  }

  .ms-footer-container {
    text-align: left;
  }

  .ms-footer-brand p {
    margin: 0 auto;
  }

  .ms-footer-links a:hover {
    transform: none;
  }

  .ms-footer-contact {
    justify-content: left;
  }
}




  </style>

<style>
/* ===============================
   PAGE PRELOADER
================================ */
#pageLoader {
  position: fixed;
  inset: 0;
  background: #ffffff;
  z-index: 9999;
  display: grid;
  place-items: center;
  transition: opacity 0.5s ease;
}

#pageLoader.hidden {
  opacity: 0;
  pointer-events: none;
}

.loader-wrap {
  display: grid;
  place-items: center;
  gap: 12px;
}

.loader-ring {
  width: 46px;
  height: 46px;
  border-radius: 50%;
  border: 3px solid #e5e7eb;
  border-top-color: #0662a3; /* RED accent */
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
