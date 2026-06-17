<?php
/* ============================================================
   AIRMED LABS — HOME PAGE (UPDATED v2)
   - Popular Blood Tests: image icons + highlighted cards
   - Special Offers section (replacing Full Body)
   - GBM-style Google Reviews
   All original PHP logic & modals preserved
   ============================================================ */
?>

<!-- ===== SEO / AEO / GEO STRUCTURED DATA ===== -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "MedicalOrganization",
      "@id": "https://www.airmedlabs.com/#organization",
      "name": "Airmed Patholab",
      "alternateName": "Airmed Labs",
      "url": "https://www.airmedlabs.com",
      "logo": "https://www.airmedlabs.com/user_assets/images/logo.png",
      "description": "NABL accredited pathology laboratory in Ahmedabad and Gandhinagar offering 300+ blood tests, home collection in 30 minutes, and fastest TAT in the city.",
      "telephone": "+919725504245",
      "address": [
        { "@type": "PostalAddress", "addressLocality": "Ahmedabad", "addressRegion": "Gujarat", "addressCountry": "IN" },
        { "@type": "PostalAddress", "addressLocality": "Gandhinagar", "addressRegion": "Gujarat", "addressCountry": "IN" }
      ],
      "openingHoursSpecification": { "@type": "OpeningHoursSpecification", "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"], "opens": "06:00", "closes": "22:00" },
      "medicalSpecialty": "Pathology",
      "aggregateRating": { "@type": "AggregateRating", "ratingValue": "4.9", "reviewCount": "1280", "bestRating": "5" }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        { "@type": "Question", "name": "Does Airmed Patholab offer home blood collection in Ahmedabad?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Airmed Patholab provides certified phlebotomist home visits across Ahmedabad and Gandhinagar within 30 minutes of booking." } },
        { "@type": "Question", "name": "Is Airmed Labs NABL accredited?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Airmed Patholab is NABL accredited with 16+ certified tests." } }
      ]
    }
  ]
}
</script>

<!-- ===== STYLES ===== -->
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap');

/* ===== DESIGN TOKENS ===== */
:root {
  --red: #c0392b;
  --red-dark: #962d22;
  --red-mid: #e74c3c;
  --red-light: #fdf0ef;
  --red-glow: rgba(192,57,43,.12);
  --navy: #0d1b2a;
  --navy-2: #1b2b3b;
  --teal: #1abc9c;
  --teal-light: #e8f8f5;
  --white: #ffffff;
  --off-white: #fafaf9;
  --gray-50: #f7f8fa;
  --gray-100: #eef0f4;
  --gray-200: #dde1e9;
  --gray-400: #9aa0b0;
  --gray-600: #5a6275;
  --gray-800: #1e2330;
  --gold: #e8a020;
  --font-display: 'DM Serif Display', Georgia, serif;
  --font-body: 'DM Sans', -apple-system, sans-serif;
  --shadow-xs: 0 1px 4px rgba(0,0,0,.06);
  --shadow-sm: 0 2px 8px rgba(0,0,0,.08);
  --shadow-md: 0 6px 24px rgba(0,0,0,.10);
  --shadow-lg: 0 12px 40px rgba(0,0,0,.13);
  --shadow-xl: 0 24px 64px rgba(0,0,0,.16);
  --shadow-red: 0 8px 32px rgba(192,57,43,.22);
  --radius-sm: 8px;
  --radius: 14px;
  --radius-lg: 20px;
  --radius-xl: 28px;
  --wa: #25D366;
  --wa-dark: #1da851;
  --transition: all .22s cubic-bezier(.4,0,.2,1);
}

/* ===== ORIGINAL LOADER (PRESERVED) ===== */
.modal2 { position:fixed; z-index:1000; top:0; left:0; height:100%; width:100%; background:rgba(0,0,0,.5) url('<?php echo base_url(); ?>user_assets/images/loader12.gif') 50% 50% no-repeat; }
.full_bg { background:rgba(0,0,0,0.5); width:100%; height:100%; float:left; position:fixed; z-index:9; top:0; bottom:0; transition:opacity .4s ease,visibility .4s ease; opacity:0; visibility:hidden; }
.full_bg.show-loader { opacity:1; visibility:visible; }
.full_bg .loader { margin-top:300px; }
.full_bg .loader img { width:70px; height:70px; }

*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body { font-family:var(--font-body); color:var(--gray-800); background:var(--white); line-height:1.65; overflow-x:hidden; -webkit-font-smoothing:antialiased; }
img { max-width:100%; height:auto; display:block; }

/* ===== SECTION COMMON ===== */
.al-section { padding:76px 0; }
.al-section-alt { padding:76px 0; background:var(--gray-50); }
.al-container { max-width:1180px; margin:0 auto; padding:0 24px; }
.al-section-tag {
  display:inline-flex; align-items:center; gap:6px;
  background:var(--red-light); color:var(--red);
  font-size:11px; font-weight:700;
  padding:4px 13px; border-radius:20px;
  letter-spacing:.9px; text-transform:uppercase; margin-bottom:14px;
}
.al-section-tag::before { content:''; width:5px; height:5px; background:var(--red); border-radius:50%; }
.al-section-title {
  font-family:var(--font-display);
  font-size:clamp(1.7rem, 3.2vw, 2.5rem);
  color:var(--navy); margin-bottom:12px; line-height:1.2;
}
.al-section-sub { color:var(--gray-600); font-size:15.5px; max-width:580px; line-height:1.7; }
.al-section-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:44px; }
.al-view-all {
  display:inline-flex; align-items:center; gap:6px;
  font-size:13.5px; font-weight:700; color:var(--red);
  text-decoration:none; transition:gap .2s;
  white-space:nowrap;
}
.al-view-all:hover { gap:10px; }
.al-view-all svg { width:14px; height:14px; stroke:var(--red); fill:none; stroke-width:2.5; }

/* ============================================================
   HERO — WHITE / LIGHT REDESIGN
   ============================================================ */
.al-hero {
  position:relative; min-height:580px;
  background:var(--navy); overflow:hidden; display:flex; align-items:center;
}
.al-hero-bg {
  position:absolute; inset:0;
  /* background:url('<?php echo base_url(); ?>user_assets/images/bg/slider02.jpg') center/cover no-repeat; */
  background-color: #008080;
  opacity:; transform:scale(1.05);
}
.al-hero::before {
  content:''; position:absolute; top:0; left:0; right:0; height:4px;
  background:linear-gradient(90deg, var(--red) 0%, var(--red-mid) 50%, var(--red) 100%); z-index:3;
}
.al-hero-deco { position:absolute; right:-60px; top:-60px; width:460px; height:460px; border-radius:50%; border:80px solid rgba(192,57,43,.04); pointer-events:none; z-index:0; }
.al-hero-deco-2 { position:absolute; right:120px; bottom:-100px; width:280px; height:280px; border-radius:50%; border:40px solid rgba(192,57,43,.06); pointer-events:none; z-index:0; }
.al-hero-inner {
  position:relative; z-index:2; max-width:1180px; margin:0 auto;
  padding:75px 24px 52px; display:grid; grid-template-columns:1fr 400px;
  gap:52px; align-items:center; width:100%;
}
.al-hero-nabl-strip { display:inline-flex; align-items:center; gap:8px; background:var(--teal-light); border:1px solid rgba(26,188,156,.2); color:#0e6655; font-size:11.5px; font-weight:600; padding:2px 2px; border-radius:30px; letter-spacing:.3px; margin-bottom:22px; }
.al-hero-nabl-dot { width:7px; height:7px; background:var(--teal); border-radius:50%; }
.al-hero-title { font-family:var(--font-display); font-size:clamp(2.1rem, 4.2vw, 3.4rem); color:#fff; line-height:1.12; margin-bottom:18px; animation:fadeUp .6s .1s ease both; }
.al-hero-title em { color:var(--red-mid); font-style:normal; }
.al-hero-desc { color:rgba(255,255,255,.68); font-size:16px; font-weight:400; margin-bottom:32px; max-width:460px; line-height:1.75; animation:fadeUp .6s .2s ease both; }
.al-hero-actions { display:flex; flex-wrap:wrap; gap:12px; margin-bottom:40px; }
.btn-hero-primary { display:inline-flex; align-items:center; gap:9px; background:var(--red); color:#fff; padding:13px 26px; border-radius:50px; font-weight:700; font-size:14px; text-decoration:none; border:none; cursor:pointer; transition:var(--transition); box-shadow:var(--shadow-red); font-family:var(--font-body); }
.btn-hero-primary:hover { background:var(--red-dark); transform:translateY(-2px); box-shadow:0 10px 32px rgba(192,57,43,.35); }
.btn-hero-secondary { display:inline-flex; align-items:center; gap:9px; background:#fff; color:var(--navy); padding:12px 24px; border-radius:50px; font-weight:600; font-size:14px; text-decoration:none; cursor:pointer; border:1.5px solid var(--gray-200); transition:var(--transition); font-family:var(--font-body); }
.btn-hero-secondary:hover { border-color:var(--red); color:var(--red); background:var(--red-light); }
.btn-hero-wa { display:inline-flex; align-items:center; gap:9px; background:var(--wa); color:#fff; padding:12px 24px; border-radius:50px; font-weight:700; font-size:14px; text-decoration:none; border:none; cursor:pointer; transition:var(--transition); box-shadow:0 4px 16px rgba(37,211,102,.3); font-family:var(--font-body); }
.btn-hero-wa:hover { background:var(--wa-dark); transform:translateY(-2px); }
.al-hero-stats { display:flex; gap:0; background:var(--gray-50); border:1px solid var(--gray-100); border-radius:var(--radius); overflow:hidden; }
.al-hero-stat { flex:1; text-align:center; padding:16px 12px; border-right:1px solid var(--gray-100); }
.al-hero-stat:last-child { border-right:none; }
.al-hero-stat strong { display:block; font-size:clamp(1.2rem,2vw,1.5rem); font-weight:700; color:var(--red); font-family:var(--font-display); line-height:1; }
.al-hero-stat span { font-size:11px; color:var(--gray-400); font-weight:500; margin-top:4px; display:block; letter-spacing:.3px; text-transform:uppercase; }
.al-search-card { background:var(--navy); border-radius:var(--radius-xl); padding:30px; box-shadow:var(--shadow-xl); }
.al-search-card-label { color:#fff; font-size:15px; font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:8px; }
.al-search-card-sub { color:rgba(255,255,255,.5); font-size:12px; margin-bottom:18px; }
.al-city-tabs { display:flex; gap:6px; margin-bottom:14px; }
.al-city-tab { flex:1; padding:9px 6px; border-radius:var(--radius-sm); border:1.5px solid rgba(255,255,255,.12); font-size:12px; font-weight:600; color:rgba(255,255,255,.6); text-align:center; cursor:pointer; transition:var(--transition); background:rgba(255,255,255,.06); font-family:var(--font-body); }
.al-city-tab.active, .al-city-tab:hover { border-color:var(--red-mid); color:#fff; background:rgba(192,57,43,.25); }
.al-city-select { width:100%; padding:11px 14px; border-radius:var(--radius-sm); border:1.5px solid rgba(255,255,255,.12); font-family:var(--font-body); font-size:14px; background:rgba(255,255,255,.08); color:#fff; outline:none; margin-bottom:10px; cursor:pointer; transition:var(--transition); }
.al-city-select:focus { border-color:var(--red-mid); }
.al-city-select option { background:var(--navy); color:#fff; }
.al-searchbar-wrap { background:rgba(255,255,255,.08); border-radius:var(--radius-sm); min-height:48px; border:1.5px solid rgba(255,255,255,.12); margin-bottom:12px; overflow:hidden; transition:var(--transition); }
.al-searchbar-wrap:focus-within { border-color:var(--red-mid); }
.al-search-btn { width:100%; display:flex; align-items:center; justify-content:center; gap:8px; background:var(--red); color:#fff; border:none; border-radius:var(--radius-sm); padding:13px; font-size:14.5px; font-weight:700; cursor:pointer; font-family:var(--font-body); transition:var(--transition); margin-bottom:16px; }
.al-search-btn:hover { background:var(--red-dark); }
.al-quick-label { color:rgba(255,255,255,.4); font-size:11px; text-transform:uppercase; letter-spacing:.8px; margin-bottom:9px; }
.al-quick-tags { display:flex; flex-wrap:wrap; gap:7px; }
.al-quick-tag { background:rgba(255,255,255,.07); color:rgba(255,255,255,.75); border:1px solid rgba(255,255,255,.12); padding:5px 12px; border-radius:20px; font-size:12px; cursor:pointer; transition:var(--transition); text-decoration:none; }
.al-quick-tag:hover { background:var(--red); border-color:var(--red); color:#fff; }

/* ============================================================
   TRUST STRIP
   ============================================================ */
.al-trust-strip { background:#fff; border-bottom:1px solid var(--gray-100); box-shadow:0 2px 12px rgba(0,0,0,.04); }
.al-trust-inner { max-width:1180px; margin:0 auto; padding:0 24px; display:grid; grid-template-columns:repeat(6,1fr); }
.al-trust-item { display:flex; flex-direction:column; align-items:center; text-align:center; padding:20px 10px; border-right:1px solid var(--gray-100); transition:var(--transition); cursor:default; }
.al-trust-item:last-child { border-right:none; }
.al-trust-item:hover { background:var(--gray-50); }
.al-trust-icon { width:44px; height:44px; border-radius:10px; background:var(--red-light); display:flex; align-items:center; justify-content:center; margin-bottom:8px; transition:var(--transition); }
.al-trust-item:hover .al-trust-icon { background:var(--red); }
.al-trust-icon svg { width:20px; height:20px; fill:var(--red); transition:var(--transition); }
.al-trust-item:hover .al-trust-icon svg { fill:#fff; }
.al-trust-label { font-size:11.5px; font-weight:600; color:var(--gray-600); line-height:1.4; }

/* ============================================================
   SECTION 1 — POPULAR BLOOD TESTS (UPDATED WITH IMAGES)
   ============================================================ */
.al-popular-section { background:#fff; }
.al-tests-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:18px; }

/* Base test card */
.al-test-card {
  border:1.5px solid var(--gray-100);
  border-radius:var(--radius);
  overflow:hidden;
  background:#fff;
  transition:var(--transition);
  cursor:pointer;
  position:relative;
  display:flex; flex-direction:column;
}
.al-test-card::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
  background:var(--red); transform:scaleX(0); transform-origin:left; transition:transform .25s ease;
}
.al-test-card:hover { border-color:rgba(192,57,43,.25); box-shadow:var(--shadow-md); transform:translateY(-3px); }
.al-test-card:hover::after { transform:scaleX(1); }

/* Test image area */
.al-test-img-wrap {
  width:100%; height:130px; overflow:hidden;
  background:var(--gray-50); position:relative;
}
.al-test-img-wrap img {
  width:100%; height:100%; object-fit:cover;
  transition:transform .4s ease;
}
.al-test-card:hover .al-test-img-wrap img { transform:scale(1.06); }

/* Highlighted card — featured style */
.al-test-card-highlight {
  border-color:var(--red);
  background:linear-gradient(160deg, #fff 60%, var(--red-light) 100%);
  box-shadow:0 4px 20px rgba(192,57,43,.12);
}
.al-test-card-highlight::before {
  content:'Most Popular';
  position:absolute; top:10px; right:10px; z-index:2;
  background:var(--red); color:#fff;
  font-size:10px; font-weight:800; letter-spacing:.5px; text-transform:uppercase;
  padding:3px 9px; border-radius:4px;
}
.al-test-card-highlight .al-test-img-wrap {
  background:linear-gradient(135deg, var(--red-light), #fff);
}

/* Urgent highlight */
.al-test-card-urgent {
  border-color:#f59e0b;
  background:linear-gradient(160deg, #fff 60%, #fffbeb 100%);
}
.al-test-card-urgent::before {
  content:'Urgent';
  position:absolute; top:10px; right:10px; z-index:2;
  background:#f59e0b; color:#7c4400;
  font-size:10px; font-weight:800; letter-spacing:.5px; text-transform:uppercase;
  padding:3px 9px; border-radius:4px;
}

.al-test-body { padding:14px 16px 16px; flex:1; display:flex; flex-direction:column; }
.al-test-name { font-size:13.5px; font-weight:700; color:var(--navy); margin-bottom:4px; line-height:1.35; }
.al-test-params { font-size:11.5px; color:var(--gray-400); margin-bottom:14px; flex:1; }
.al-test-footer { display:flex; align-items:center; justify-content:space-between; }
.al-test-tag { background:var(--teal-light); color:#0e6655; font-size:10.5px; font-weight:700; padding:3px 9px; border-radius:4px; text-transform:uppercase; letter-spacing:.4px; }
.al-test-tag-urgent { background:#fef3c7; color:#92400e; }
.al-test-book {
  background:var(--red-light); color:var(--red);
  border:none; border-radius:6px; padding:6px 14px; font-size:12px; font-weight:700;
  cursor:pointer; font-family:var(--font-body); transition:var(--transition);
}
.al-test-book:hover { background:var(--red); color:#fff; }

/* ============================================================
   SECTION 2 — HEALTH PACKAGES
   ============================================================ */
.al-packages-section { background:var(--gray-50); }
.al-pkg-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:36px; }
.al-pkg-tab { padding:8px 18px; border-radius:30px; border:1.5px solid var(--gray-200); font-size:13px; font-weight:600; color:var(--gray-600); cursor:pointer; transition:var(--transition); background:#fff; font-family:var(--font-body); }
.al-pkg-tab.active, .al-pkg-tab:hover { background:var(--navy); border-color:var(--navy); color:#fff; }
.al-pkg-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.al-pkg-card { background:#fff; border:1.5px solid var(--gray-100); border-radius:var(--radius-lg); overflow:hidden; transition:var(--transition); display:flex; flex-direction:column; position:relative; }
.al-pkg-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-lg); border-color:rgba(192,57,43,.2); }
.al-pkg-card-featured { border-color:var(--red); box-shadow:0 0 0 1px var(--red), var(--shadow-md); }
.al-pkg-header { padding:22px 22px 18px; background:var(--navy); position:relative; overflow:hidden; }
.al-pkg-header::after { content:''; position:absolute; bottom:-20px; right:-20px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,.04); pointer-events:none; }
.al-pkg-header-teal { background:linear-gradient(135deg, #0d4f42, #1abc9c); }
.al-pkg-header-wine { background:linear-gradient(135deg, #4a0e1e, #c0392b); }
.al-pkg-header-midnight { background:linear-gradient(135deg, #0d1b2a, #243447); }
.al-pkg-badge-wrap { margin-bottom:10px; }
.al-pkg-badge { display:inline-block; background:var(--gold); color:var(--navy); font-size:10px; font-weight:800; padding:3px 10px; border-radius:4px; letter-spacing:.5px; text-transform:uppercase; }
.al-pkg-badge-green { background:#4ddb88; }
.al-pkg-badge-blue { background:#74b9ff; }
.al-pkg-icon { font-size:32px; margin-bottom:10px; }
.al-pkg-name { font-size:16px; font-weight:700; color:#fff; margin-bottom:3px; line-height:1.3; }
.al-pkg-param-count { font-size:12.5px; color:rgba(255,255,255,.55); }
.al-pkg-body { padding:20px 22px; flex:1; display:flex; flex-direction:column; }
.al-pkg-includes { list-style:none; padding:0; margin-bottom:auto; }
.al-pkg-includes li { font-size:12.5px; color:var(--gray-600); padding:5px 0; display:flex; align-items:flex-start; gap:8px; border-bottom:1px solid var(--gray-50); line-height:1.45; }
.al-pkg-includes li:last-child { border:none; }
.al-pkg-includes li::before { content:'✓'; color:var(--teal); font-size:11px; font-weight:800; flex-shrink:0; margin-top:2px; }
.al-pkg-footer { padding:16px 22px 20px; border-top:1px solid var(--gray-50); }
.al-btn-wa-pkg { width:100%; display:flex; align-items:center; justify-content:center; gap:8px; background:linear-gradient(135deg, var(--wa) 0%, var(--wa-dark) 100%); color:#fff; border:none; border-radius:var(--radius-sm); padding:12px 16px; font-size:13.5px; font-weight:700; cursor:pointer; font-family:var(--font-body); transition:var(--transition); text-decoration:none; }
.al-btn-wa-pkg:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,211,102,.35); }
.al-btn-wa-pkg svg { width:17px; height:17px; fill:#fff; flex-shrink:0; }
.al-view-all-cta { text-align:center; margin-top:44px; }
.al-btn-navy { display:inline-flex; align-items:center; gap:10px; background:var(--navy); color:#fff; padding:14px 32px; border-radius:50px; font-weight:700; font-size:15px; text-decoration:none; border:none; cursor:pointer; font-family:var(--font-body); transition:var(--transition); box-shadow:0 4px 20px rgba(13,27,42,.2); }
.al-btn-navy:hover { background:var(--navy-2); transform:translateY(-2px); }

/* ============================================================
   SECTION 3 — SPECIAL OFFERS (NEW SECTION)
   ============================================================ */
.al-offers-section {
  background:var(--navy);
  position:relative; overflow:hidden;
}
.al-offers-section::before {
  content:''; position:absolute; inset:0;
  /*background:radial-gradient(ellipse at 70% 30%, rgba(192,57,43,.22) 0%, transparent 55%);*/
  background-color: #008080;
}
/* Decorative dots pattern */
.al-offers-section::after {
  content:''; position:absolute; top:0; right:0;
  width:350px; height:350px;
  background-image: radial-gradient(rgba(255,255,255,.06) 1.5px, transparent 1.5px);
  background-size: 20px 20px;
  pointer-events:none; z-index:0;
}
.al-offers-inner { position:relative; z-index:2; }
.al-offers-section .al-section-tag { background:rgba(232,160,32,.2); color:#f5c842; }
.al-offers-section .al-section-title { color:#fff; }
.al-offers-section .al-section-sub { color:rgba(255,255,255,.6); }

.al-offers-grid {
  display:grid; grid-template-columns:1fr 1fr;
  gap:28px; margin-top:44px;
}

/* Offer Card Base */
.al-offer-card {
  border-radius:var(--radius-xl);
  overflow:hidden; position:relative;
  display:flex; flex-direction:column;
  transition:var(--transition);
}
.al-offer-card:hover { transform:translateY(-6px); }

/* Offer Card 1 — 1+1 Full Body */
.al-offer-card-1 {
  background:linear-gradient(145deg, #1a0a08, #5c1a13);
  border:1.5px solid rgba(192,57,43,.4);
  box-shadow:0 8px 40px rgba(192,57,43,.25);
}
/* Offer Card 2 — Full Body + Dexa */
.al-offer-card-2 {
  background:linear-gradient(145deg, #061929, #0d3d5c);
  border:1.5px solid rgba(26,188,156,.3);
  box-shadow:0 8px 40px rgba(26,188,156,.15);
}

.al-offer-ribbon {
  position:absolute; top:18px; right:-1px;
  background:var(--gold); color:var(--navy);
  font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:.6px;
  padding:5px 16px 5px 12px;
  clip-path: polygon(8px 0%, 100% 0%, 100% 100%, 8px 100%, 0% 50%);
  z-index:2;
}
.al-offer-ribbon-green { background:#4ddb88; }

.al-offer-header {
  padding:28px 28px 0;
  display:flex; align-items:flex-start; justify-content:space-between;
  gap:16px;
}
.al-offer-icon-wrap {
  width:70px; height:70px; border-radius:16px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:36px;
}
.al-offer-icon-red { background:rgba(192,57,43,.25); border:1.5px solid rgba(192,57,43,.3); }
.al-offer-icon-teal { background:rgba(26,188,156,.15); border:1.5px solid rgba(26,188,156,.25); }

.al-offer-badge-big {
  font-family:var(--font-display);
  font-size:2.8rem; font-weight:700; line-height:1;
  letter-spacing:-1px;
}
.al-offer-badge-big-red { color:var(--red-mid); }
.al-offer-badge-big-teal { color:var(--teal); }
.al-offer-badge-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; margin-top:2px; }
.al-offer-badge-label-red { color:rgba(231,76,60,.7); }
.al-offer-badge-label-teal { color:rgba(26,188,156,.7); }

.al-offer-body { padding:20px 28px; flex:1; }
.al-offer-name {
  font-family:var(--font-display);
  font-size:1.45rem; color:#fff; line-height:1.2; margin-bottom:8px;
}
.al-offer-desc { font-size:13px; color:rgba(255,255,255,.55); line-height:1.65; margin-bottom:18px; }

.al-offer-includes {
  list-style:none; padding:0;
  background:rgba(255,255,255,.05);
  border-radius:var(--radius-sm);
  padding:14px 16px; margin-bottom:20px;
}
.al-offer-includes li {
  font-size:12.5px; color:rgba(255,255,255,.8);
  padding:4.5px 0; display:flex; align-items:flex-start; gap:9px;
  border-bottom:1px solid rgba(255,255,255,.06); line-height:1.45;
}
.al-offer-includes li:last-child { border:none; }
.al-offer-includes li::before { content:'✓'; font-weight:800; font-size:11px; flex-shrink:0; margin-top:2px; }
.al-offer-includes-check-red li::before { color:var(--red-mid); }
.al-offer-includes-check-teal li::before { color:var(--teal); }

.al-offer-params-row {
  display:flex; flex-wrap:wrap; gap:8px; margin-bottom:22px;
}
.al-offer-param-pill {
  font-size:11px; font-weight:600; padding:4px 11px; border-radius:20px;
}
.al-offer-param-pill-red { background:rgba(192,57,43,.2); color:#f08080; border:1px solid rgba(192,57,43,.3); }
.al-offer-param-pill-teal { background:rgba(26,188,156,.15); color:#4dddb8; border:1px solid rgba(26,188,156,.25); }

.al-offer-footer { padding:0 28px 28px; }
.al-btn-offer-red {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:var(--red); color:#fff; border:none;
  border-radius:var(--radius-sm); padding:14px 16px;
  font-size:14px; font-weight:700;
  cursor:pointer; font-family:var(--font-body); transition:var(--transition);
  text-decoration:none; letter-spacing:.2px;
}
.al-btn-offer-red:hover { background:var(--red-dark); box-shadow:0 6px 24px rgba(192,57,43,.4); }
.al-btn-offer-teal {
  width:100%; display:flex; align-items:center; justify-content:center; gap:9px;
  background:var(--teal); color:#0a3d30; border:none;
  border-radius:var(--radius-sm); padding:14px 16px;
  font-size:14px; font-weight:700;
  cursor:pointer; font-family:var(--font-body); transition:var(--transition);
  text-decoration:none; letter-spacing:.2px;
}
.al-btn-offer-teal:hover { background:#16a085; color:#fff; box-shadow:0 6px 24px rgba(26,188,156,.35); }
.al-btn-offer-red svg, .al-btn-offer-teal svg { width:18px; height:18px; fill:currentColor; flex-shrink:0; }

/* Offer highlight box (for DEXA card) */
.al-offer-highlight-box {
  background:rgba(26,188,156,.1); border:1px solid rgba(26,188,156,.2);
  border-radius:var(--radius-sm); padding:11px 14px;
  display:flex; align-items:center; gap:12px; margin-bottom:16px;
  font-size:12.5px; color:rgba(255,255,255,.75);
}
.al-offer-highlight-icon { font-size:22px; flex-shrink:0; }

/* ============================================================
   WHY CHOOSE US
   ============================================================ */
.al-why-section { background:#fff; }
.al-why-grid-main { display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:start; }
.al-why-features { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:28px; }
.al-why-feat { background:var(--gray-50); border:1.5px solid var(--gray-100); border-radius:var(--radius); padding:20px 18px; transition:var(--transition); }
.al-why-feat:hover { border-color:rgba(192,57,43,.3); background:var(--red-light); }
.al-why-feat-icon { width:40px; height:40px; border-radius:10px; background:var(--red-light); display:flex; align-items:center; justify-content:center; margin-bottom:12px; transition:var(--transition); }
.al-why-feat:hover .al-why-feat-icon { background:var(--red); }
.al-why-feat-icon svg { width:19px; height:19px; fill:var(--red); transition:var(--transition); }
.al-why-feat:hover .al-why-feat-icon svg { fill:#fff; }
.al-why-feat-title { font-size:13.5px; font-weight:700; color:var(--navy); margin-bottom:4px; }
.al-why-feat-desc { font-size:12px; color:var(--gray-600); line-height:1.55; }
.al-aeo-block { background:var(--gray-50); border-radius:var(--radius); padding:24px 28px; margin-top:32px; border-left:4px solid var(--red); }
.al-aeo-block h3 { font-family:var(--font-display); font-size:1.1rem; color:var(--navy); margin-bottom:8px; }
.al-aeo-block p { font-size:13.5px; color:var(--gray-600); line-height:1.75; }
.al-aeo-block p + p { margin-top:8px; }
.al-why-right-img { border-radius:var(--radius-xl); overflow:hidden; background:var(--gray-50); position:relative; }
.al-why-right-img img { width:100%; height:auto; border-radius:var(--radius-xl); }
.al-why-img-stat { position:absolute; bottom:-16px; left:-16px; background:#fff; border-radius:var(--radius); padding:16px 20px; box-shadow:var(--shadow-lg); border:1.5px solid var(--gray-100); min-width:175px; }
.al-why-img-stat-num { font-family:var(--font-display); font-size:2.2rem; color:var(--red); line-height:1; }
.al-why-img-stat-text { font-size:12px; color:var(--gray-600); margin-top:3px; font-weight:500; }

/* ============================================================
   HOW IT WORKS
   ============================================================ */
.al-how-section { background: #008080; position:relative; overflow:hidden; }
.al-how-section::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at 80% 40%, rgba(192,57,43,.18) 0%, transparent 55%); }
.al-how-inner { position:relative; z-index:2; }
.al-how-section .al-section-tag { background:rgba(192,57,43,.2); color:#f5a8a2; }
.al-how-section .al-section-title { color:#fff; }
.al-how-section .al-section-sub { color:rgba(255,255,255,.6); }
.al-steps { display:grid; grid-template-columns:repeat(4,1fr); gap:24px; margin-top:52px; position:relative; }
.al-steps::before { content:''; position:absolute; top:36px; left:12.5%; right:12.5%; height:1px; background:linear-gradient(90deg, transparent, rgba(255,255,255,.15) 20%, rgba(255,255,255,.15) 80%, transparent); }
.al-step { text-align:center; padding:28px 16px; }
.al-step-num { width:72px; height:72px; border-radius:50%; margin:0 auto 20px; background:rgba(255,255,255,.06); border:1.5px solid rgba(255,255,255,.12); display:flex; align-items:center; justify-content:center; font-size:1.9rem; color:var(--red-mid); transition:var(--transition); }
.al-step:hover .al-step-num { background:var(--red); border-color:var(--red); transform:scale(1.08); }
.al-step-title { color:#fff; font-weight:700; font-size:14.5px; margin-bottom:8px; }
.al-step-desc { color:rgba(255,255,255,.55); font-size:13px; line-height:1.65; }

/* ============================================================
   LOGISTICS / INFRASTRUCTURE
   ============================================================ */
.al-logistic-section { background:var(--off-white); }
.al-logistic-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-top:48px; }
.al-logistic-card { border-radius:var(--radius-lg); overflow:hidden; position:relative; aspect-ratio:4/3.2; display:flex; align-items:flex-end; }
.al-logistic-card img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:transform .5s ease; }
.al-logistic-card:hover img { transform:scale(1.04); }
.al-logistic-overlay { position:absolute; inset:0; background:linear-gradient(to top, rgba(13,27,42,.92) 0%, rgba(13,27,42,.4) 55%, transparent 100%); }
.al-logistic-body { position:relative; z-index:2; padding:22px 24px; width:100%; }
.al-logistic-icon { width:38px; height:38px; border-radius:8px; background:var(--red); display:flex; align-items:center; justify-content:center; margin-bottom:10px; }
.al-logistic-icon svg { width:17px; height:17px; fill:#fff; }
.al-logistic-title { color:#fff; font-size:16px; font-weight:700; margin-bottom:4px; }
.al-logistic-desc { color:rgba(255,255,255,.65); font-size:12.5px; line-height:1.6; }

/* ============================================================
   NABL ACCREDITATION
   ============================================================ */
.al-nabl-section { background: #008080; position:relative; overflow:hidden; }
.al-nabl-section::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at 65% 50%, rgba(192,57,43,.15) 0%, transparent 55%); }
.al-nabl-grid { display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center; position:relative; z-index:2; }
.al-nabl-section .al-section-tag { background:rgba(192,57,43,.2); color:#f5a8a2; }
.al-nabl-section .al-section-title { color:#fff; }
.al-nabl-section .al-section-sub { color:rgba(255,255,255,.6); max-width:440px; }
.al-nabl-cert { display:inline-flex; align-items:center; gap:16px; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.14); border-radius:var(--radius); padding:16px 20px; margin:24px 0 20px; }
.al-nabl-cert img { height:52px; flex-shrink:0; }
.al-nabl-cert-info strong { display:block; font-size:14.5px; font-weight:700; color:#fff; }
.al-nabl-cert-info small { font-size:12px; color:rgba(255,255,255,.5); }
.al-nabl-tags { display:flex; flex-wrap:wrap; gap:8px; }
.al-nabl-tag { background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.13); color:rgba(255,255,255,.75); padding:5px 12px; border-radius:6px; font-size:12px; font-weight:500; transition:var(--transition); }
.al-nabl-tag:hover { background:rgba(192,57,43,.3); border-color:rgba(192,57,43,.5); color:#fff; }
.al-stat-cards { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.al-stat-card { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); border-radius:var(--radius); padding:22px; transition:var(--transition); }
.al-stat-card:hover { background:rgba(255,255,255,.1); border-color:rgba(192,57,43,.35); }
.al-stat-card-num { font-family:var(--font-display); font-size:2.4rem; color: #000 }
.al-stat-card-label { color:rgba(255,255,255,.85); font-size:14px; font-weight:600; margin-top:6px; }
.al-stat-card-sub { color:rgba(255,255,255,.4); font-size:12px; margin-top:3px; }

/* ============================================================
   FAQ
   ============================================================ */
.al-faq-section { background:#fff; }
.al-faq-grid { display:grid; grid-template-columns:1fr 1.2fr; gap:64px; align-items:start; }
.al-faq-left { position:sticky; top:24px; }
.al-faq-item { border:1.5px solid var(--gray-100); border-radius:var(--radius); margin-bottom:12px; overflow:hidden; transition:var(--transition); }
.al-faq-item.open { border-color:rgba(192,57,43,.3); box-shadow:var(--shadow-sm); }
.al-faq-q { width:100%; text-align:left; background:none; border:none; padding:17px 20px; cursor:pointer; font-family:var(--font-body); font-size:14px; font-weight:600; color:var(--navy); display:flex; justify-content:space-between; align-items:center; gap:12px; transition:var(--transition); }
.al-faq-q:hover,.al-faq-item.open .al-faq-q { color:var(--red); }
.al-faq-arrow { width:22px; height:22px; border-radius:50%; background:var(--gray-100); flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:var(--transition); }
.al-faq-item.open .al-faq-arrow { background:var(--red); transform:rotate(180deg); }
.al-faq-arrow svg { width:11px; height:11px; fill:var(--gray-600); transition:var(--transition); }
.al-faq-item.open .al-faq-arrow svg { fill:#fff; }
.al-faq-a { max-height:0; overflow:hidden; transition:max-height .3s ease; }
.al-faq-item.open .al-faq-a { max-height:220px; }
.al-faq-a-inner { padding:0 20px 16px; font-size:13.5px; color:var(--gray-600); line-height:1.75; border-top:1px solid var(--gray-100); padding-top:14px; }

/* ============================================================
   GOOGLE-STYLE REVIEWS (GBM SECTION — UPDATED)
   ============================================================ */
.al-reviews-section { background:#fff; }
.al-reviews-header-inner {
  display:flex; align-items:center; justify-content:space-between;
  flex-wrap:wrap; gap:20px; margin-bottom:44px;
}
.al-reviews-score-box {
  display:flex; align-items:center; gap:20px;
  background:var(--gray-50); border:1.5px solid var(--gray-100);
  border-radius:var(--radius-lg); padding:20px 28px;
}
.al-reviews-score-num {
  font-family:var(--font-display); font-size:3.2rem; color:var(--navy);
  line-height:1;
}
.al-reviews-score-right {}
.al-reviews-stars-big { color:#f59e0b; font-size:20px; letter-spacing:2px; line-height:1; margin-bottom:4px; }
.al-reviews-score-count { font-size:12px; color:var(--gray-400); font-weight:500; }
.al-reviews-google-badge {
  display:flex; align-items:center; gap:8px;
  background:#fff; border:1px solid var(--gray-200);
  border-radius:var(--radius-sm); padding:10px 16px;
  font-size:13px; font-weight:600; color:var(--gray-600);
  text-decoration:none; transition:var(--transition);
}
.al-reviews-google-badge:hover { border-color:var(--gray-400); color:var(--gray-800); box-shadow:var(--shadow-sm); }
.al-google-logo { width:20px; height:20px; flex-shrink:0; }

/* Review grid */
.al-reviews-grid {
  display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));
  gap:18px;
}
.al-review-card {
  background:#fff;
  border:1.5px solid var(--gray-100);
  border-radius:var(--radius-lg);
  padding:20px 22px;
  transition:var(--transition);
  display:flex; flex-direction:column;
  position:relative;
}
.al-review-card:hover { box-shadow:var(--shadow-md); border-color:rgba(192,57,43,.15); transform:translateY(-2px); }

/* Google-style top row */
.al-review-top {
  display:flex; align-items:flex-start; justify-content:space-between;
  margin-bottom:12px; gap:10px;
}
.al-review-author-row { display:flex; align-items:center; gap:10px; }
.al-review-avatar {
  width:42px; height:42px; border-radius:50%; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:16px; font-weight:700; color:#fff;
  font-family:var(--font-body);
}
.al-review-author-info {}
.al-review-name { font-size:14px; font-weight:700; color:var(--navy); margin-bottom:1px; }
.al-review-date { font-size:11.5px; color:var(--gray-400); }
/* Google G icon */
.al-review-google-g {
  width:24px; height:24px; flex-shrink:0; margin-top:2px;
}
.al-review-stars { color:#f59e0b; font-size:13px; letter-spacing:1px; margin-bottom:10px; }
.al-review-text { font-size:13.5px; color:var(--gray-600); line-height:1.72; flex:1; }
.al-review-text.clamped { display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
.al-review-service-tag {
  margin-top:12px; display:inline-flex; align-items:center; gap:5px;
  background:var(--gray-50); color:var(--gray-600);
  border:1px solid var(--gray-100);
  padding:4px 10px; border-radius:20px;
  font-size:11px; font-weight:600;
}
.al-review-service-tag::before { content:'🔬'; font-size:10px; }
.al-review-verified { display:flex; align-items:center; gap:4px; margin-top:10px; font-size:11px; color:#0e6655; font-weight:600; }
.al-review-verified::before { content:'✓'; font-weight:900; }

/* View on Google CTA */
.al-reviews-cta { text-align:center; margin-top:40px; }
.al-btn-google-review {
  display:inline-flex; align-items:center; gap:10px;
  background:#fff; color:var(--gray-800);
  border:1.5px solid var(--gray-200);
  padding:13px 28px; border-radius:50px;
  font-weight:700; font-size:14px; text-decoration:none;
  transition:var(--transition); box-shadow:var(--shadow-sm);
  font-family:var(--font-body);
}
.al-btn-google-review:hover { border-color:var(--gray-400); box-shadow:var(--shadow-md); transform:translateY(-2px); }

/* ============================================================
   FLOATING WHATSAPP
   ============================================================ */
.al-float-wa { position:fixed; bottom:28px; right:28px; z-index:9999; }
.al-wa-popup { background:#fff; border-radius:var(--radius); box-shadow:var(--shadow-xl); padding:14px 16px; width:230px; margin-bottom:12px; font-size:13px; color:var(--gray-600); line-height:1.55; position:relative; }
.al-wa-popup::after { content:''; position:absolute; bottom:-8px; right:20px; border:8px solid transparent; border-top-color:#fff; border-bottom:0; }
.al-wa-popup-close { position:absolute; top:6px; right:8px; background:none; border:none; font-size:17px; color:var(--gray-400); cursor:pointer; line-height:1; }
.al-wa-btn { width:58px; height:58px; background:var(--wa); border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#fff; margin-left:auto; box-shadow:0 4px 16px rgba(37,211,102,.45); animation:waPulse 2s infinite; transition:transform .2s; position:relative; }
.al-wa-btn:hover { transform:scale(1.08); }
.al-wa-btn svg { width:28px; height:28px; fill:#fff; }
.al-wa-dot { position:absolute; top:-2px; right:-2px; width:18px; height:18px; background:var(--red); border-radius:50%; border:2px solid #fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; }
@keyframes waPulse { 0%,100%{box-shadow:0 4px 16px rgba(37,211,102,.45)}50%{box-shadow:0 4px 32px rgba(37,211,102,.7)} }

/* ============================================================
   BOOKING MODAL
   ============================================================ */
.al-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:10000; display:flex; align-items:center; justify-content:center; opacity:0; visibility:hidden; transition:all .25s; }
.al-modal-overlay.open { opacity:1; visibility:visible; }
.al-modal-box { background:#fff; border-radius:var(--radius-xl); padding:36px; width:90%; max-width:440px; position:relative; transform:scale(.95); transition:transform .25s; box-shadow:var(--shadow-xl); }
.al-modal-overlay.open .al-modal-box { transform:scale(1); }
.al-modal-close { position:absolute; top:14px; right:16px; background:var(--gray-100); border:none; font-size:16px; color:var(--gray-600); cursor:pointer; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; }
.al-modal-close:hover { background:var(--gray-200); }
.al-modal-title { font-family:var(--font-display); font-size:1.5rem; color:var(--navy); margin-bottom:5px; }
.al-modal-sub { color:var(--gray-600); font-size:14px; margin-bottom:22px; }
.al-modal-pkg { background:var(--red-light); border-radius:var(--radius-sm); padding:10px 14px; font-weight:700; color:var(--red); font-size:14px; margin-bottom:20px; border-left:3px solid var(--red); }
.al-form-field { margin-bottom:15px; }
.al-form-field label { display:block; font-size:13px; font-weight:600; color:var(--gray-800); margin-bottom:6px; }
.al-form-field input { width:100%; border:1.5px solid var(--gray-200); border-radius:10px; padding:12px 14px; font-size:15px; font-family:var(--font-body); outline:none; transition:border-color .2s; }
.al-form-field input:focus { border-color:var(--red); }
.al-btn-wa-submit { width:100%; display:flex; align-items:center; justify-content:center; gap:10px; background:linear-gradient(135deg, var(--wa), var(--wa-dark)); color:#fff; border:none; border-radius:12px; padding:14px; font-size:16px; font-weight:700; cursor:pointer; font-family:var(--font-body); transition:var(--transition); }
.al-btn-wa-submit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,211,102,.35); }
.al-modal-note { text-align:center; font-size:12px; color:var(--gray-400); margin-top:12px; }

/* ============================================================
   ORIGINAL MODALS
   ============================================================ */
.alert .close { position:unset !important; opacity:1 !important; }
.alert .close:hover,.alert .close:focus { color:#7b7b7b !important; opacity:1 !important; }

/* ============================================================
   SCROLL REVEAL
   ============================================================ */
.sr { opacity:0; transform:translateY(24px); transition:opacity .55s ease, transform .55s ease; }
.sr.visible { opacity:1; transform:translateY(0); }
.sr-delay-1 { transition-delay:.1s; }
.sr-delay-2 { transition-delay:.18s; }
.sr-delay-3 { transition-delay:.26s; }
.sr-delay-4 { transition-delay:.34s; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width:1080px) {
  .al-hero-inner { grid-template-columns:1fr; }
  .al-search-card { display:none; }
  .al-trust-inner { grid-template-columns:repeat(3,1fr); }
  .al-tests-grid { grid-template-columns:repeat(2,1fr); }
  .al-pkg-grid { grid-template-columns:1fr 1fr; }
  .al-offers-grid { grid-template-columns:1fr; }
  .al-why-grid-main { grid-template-columns:1fr; }
  .al-why-right { display:none; }
  .al-nabl-grid { grid-template-columns:1fr; gap:36px; }
  .al-faq-grid { grid-template-columns:1fr; }
  .al-faq-left { position:static; }
  .al-steps { grid-template-columns:repeat(2,1fr); }
  .al-steps::before { display:none; }
  .al-logistic-grid { grid-template-columns:1fr 1fr; }
}
@media (max-width:768px) {
  .al-section,.al-section-alt { padding:52px 0; }
  .al-hero-inner { padding:70px 20px; }
  .al-hero-stats { border-radius:var(--radius-sm); }
  .al-hero-stat strong { font-size:1.2rem; }
  .al-trust-inner { grid-template-columns:repeat(2,1fr); }
  .al-why-features { grid-template-columns:1fr; }
  .al-tests-grid { grid-template-columns:1fr 1fr; }
  .al-pkg-grid { grid-template-columns:1fr; }
  .al-logistic-grid { grid-template-columns:1fr; }
  .al-reviews-grid { grid-template-columns:1fr; }
  .al-steps { grid-template-columns:1fr 1fr; }
  .al-stat-cards { grid-template-columns:1fr 1fr; }
  .al-section-header { flex-direction:column; align-items:flex-start; gap:12px; }
  .al-reviews-header-inner { flex-direction:column; }
}
@media (max-width:480px) {
  .al-hero-actions { flex-direction:column; }
  .btn-hero-primary,.btn-hero-secondary,.btn-hero-wa { width:100%; justify-content:center; }
  .al-tests-grid { grid-template-columns:1fr 1fr; }
  .al-pkg-tabs { gap:6px; }
  .al-steps { grid-template-columns:1fr; }
  .al-offers-grid { grid-template-columns:1fr; }
}
@media print { .al-float-wa,.al-modal-overlay { display:none; } }
</style>

<!-- ===== ORIGINAL LOADER DIVS ===== -->
<div class="modal2" id="arrowLoader" style="display:none;"></div>
<div class="full_bg" id="loader_div">
  <div class="loader">
    <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif" alt="Loading"></center>
  </div>
</div>

<!-- ===== ORIGINAL PAYMENT MODALS (ALL PRESERVED) ===== -->
<div class="modal fade" id="myModal1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <div class="form-group">
          <label for="text">Mobile No.:</label>
          <input type="text" id="get_inquiry_id" class="form-control">
          <div id="get_inquiry_id_error"></div>
        </div>
        <div class="form-group">
          <button type="button" id="get_inquiry_id_error_btn" onclick="get_inquiry();" class="btn btn-default">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content srch_popup_full">
      <div class="modal-header srch_popup_full srch_head_clr">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title clr_fff">Upload Prescription</h4>
      </div>
      <form action="<?php echo base_url(); ?>user_master/home_upload_prescription" method="post" enctype="multipart/form-data" id="uploadform">
      <input type="hidden" name="city" id="tst_city">
      <div class="modal-body srch_popup_full">
        <div class="uplod_prec_full">
          <div class="col-sm-12 pdng_0 full_div">
            <label class="pull-left full_div text-left">Mobile No.<span style="color:red;">*</span></label>
            <div class="input-group">
              <span class="input-group-addon pkgdtl_spn_91">+91</span>
              <input class="srch_pop_inpt nobrdr_rds_tplft decimal" type="text" name="mobile"
                <?php if (isset($user->full_name)) { echo "readonly='readonly'"; } ?>
                id="mobile" placeholder="Mobile"
                value="<?php if (isset($user->full_name)) { echo $user->mobile; } ?>"
                onchange="validmobile()"/>
            </div>
            <div id="error_mobile" style="color:red;float:left;"></div>
          </div>
          <div class="col-sm-12 pdng_0 full_div">
            <label class="pull-left full_div text-left">Description <span style="color:red;">*</span></label>
            <textarea class="upld_txtara" required placeholder="Description" id="desc" name="desc"></textarea>
            <div id="error_desc" style="color:red;float:left;"></div>
          </div>
          <div class="col-sm-12 pdng_0 full_div">
            <label class="pull-left col-sm-12 text-left pdng_0 full_div">Upload Document <span style="color:red;">*</span></label>
            <div class="input-group">
              <span class="input-group-btn">
                <span class="btn btn-primary btn-file upld_btm">Browse&hellip;<input type="file" name="userfile" id="id_browse"></span>
              </span>
              <input type="text" id="file_name" class="form-control upld_inpt" readonly>
            </div>
            <div id="error_file" style="color:red;float:left;"></div>
          </div>
          <div class='col-sm-12 pdng_0 full_div'>
            <br/>
            <script src='https://www.google.com/recaptcha/api.js'></script>
            <div class="g-recaptcha" id="RecaptchaField_2" data-callback="recaptchaCallback" data-sitekey="6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr"></div>
            <span id="captch_error" style="color:red;"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer uplod_prec_full">
        <button type="button" onclick='vlidation_btn();' class="btn btn-default">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">AIRMED PATHOLAB</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php echo $payment_success[0]; ?>
        <div style="width:100%;text-align:center;"><img src="<?php echo base_url(); ?>user_assets/right-images.jpg" alt="Success"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<div role="dialog" id="myModal_payment" class="modal fade">
  <div class="modal-dialog"><div class="modal-content"><div class="modal-body">
    <div class="col-sm-12"><h4 style="width:-moz-max-content;float:left;text-align:center;width:100%;" class="modal-title"><?= $payment_success[0]; ?></h4></div>
    <div id="model_body"><div style="width:100%;text-align:center;"><img src="<?php echo base_url(); ?>user_assets/right-images.jpg" alt="Success"></div></div>
  </div><button style="display:none;" id="close_model" data-dismiss="modal" class="btn btn-primary" type="button"></button></div></div>
</div>

<div role="dialog" id="myModal_payment1" class="modal fade">
  <div class="modal-dialog"><div class="modal-content"><div class="modal-body">
    <div class="col-sm-12"><h4 style="width:-moz-max-content;float:left;text-align:center;width:100%;" class="modal-title"><?= $payment_unsuccess[0]; ?></h4></div>
    <div id="model_body"><div style="width:100%;text-align:center;"><img src="<?php echo base_url(); ?>user_assets/warning.jpg" alt="Warning" style="height:70px;"></div></div>
  </div><button style="display:none;" id="close_model" data-dismiss="modal" class="btn btn-primary" type="button"></button></div></div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<main class="al-main" itemscope itemtype="https://schema.org/MedicalOrganization">
  <meta itemprop="name" content="Airmed Patholab">
  <meta itemprop="telephone" content="+919725504245">
  <meta itemprop="medicalSpecialty" content="Pathology">

  <?php if ($success[0] != NULL) { ?>
  <div class="alert alert-success alert-dismissable" style="margin:15px 20px 0;">
    <a aria-hidden="true" data-dismiss="alert" class="close">×</a>
    <?php echo $success['0']; ?>
  </div>
  <?php } ?>

  <!-- ===================================================
       HERO SECTION
       =================================================== -->
  <section class="al-hero" aria-label="Airmed Patholab — NABL Accredited Blood Tests Ahmedabad">
    <div class="al-hero-deco" aria-hidden="true"></div>
    <div class="al-hero-deco-2" aria-hidden="true"></div>
    <div class="al-hero-bg" aria-hidden="true"></div>
    <div class="al-hero-inner">
      <div class="al-hero-left">
        <div class="al-hero-nabl-strip">
          <span class="al-hero-nabl-dot"></span>
          NABL Accredited Lab
        </div>
        <h1 class="al-hero-title">
          Ahmedabad's Fastest<br/>
          <em>Home Blood Collection</em><br/>
          &amp; Pathology Lab
        </h1>
        <p class="al-hero-desc">
          NABL certified blood tests with certified phlebotomist at your doorstep in 30 minutes. 300+ tests, digital reports in 4–6 hours. Book via WhatsApp anytime.
        </p>
        <div class="al-hero-actions">
          <a href="<?php echo base_url(); ?>user_master/all_packages" class="btn-hero-primary" aria-label="View health packages">
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff;flex-shrink:0;"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
            View Health Packages
          </a>
          <a href="https://wa.me/919725504245?text=Hi,%20I%20want%20to%20book%20a%20blood%20test%20at%20Airmed%20Patholab." target="_blank" rel="noopener" class="btn-hero-wa" aria-label="Book on WhatsApp">
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff;flex-shrink:0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
            Book on WhatsApp
          </a>
          <button onclick="open_browse();" type="button" class="btn-hero-secondary" data-toggle="modal" data-target="#myModal">
            <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
            Upload Prescription
          </button>
        </div>
        <div class="al-hero-stats" role="list">
          <div class="al-hero-stat" role="listitem"><strong>50,000+</strong><span>Happy Patients</span></div>
          <div class="al-hero-stat" role="listitem"><strong>300+</strong><span>Tests Available</span></div>
          <div class="al-hero-stat" role="listitem"><strong>30 Min</strong><span>Home Collection</span></div>
          <!--<div class="al-hero-stat" role="listitem"><strong>Fastest</strong><span>Report Delivery</span></div>-->
        </div>
      </div>
      <div class="al-search-card" aria-label="Quick test search">
        <p class="al-search-card-label">
          <svg viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="#e74c3c" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          Search a Test or Package
        </p>
        <p class="al-search-card-sub">Ahmedabad &amp; Gandhinagar</p>
        <select id="tst_city_list" onchange="get_packages(this.value);" class="al-city-select" aria-label="Select city">
          <?php foreach ($test_cities as $key) {
            if ($key["id"] == 1 || $key["id"] == 6) { ?>
          <option value="<?= $key["id"] ?>" <?php if ($test_city_session != '' && $test_city_session == $key["id"]) { echo "selected"; } ?>>
            <?= strtoupper($key["name"]) ?>
          </option>
          <?php } } ?>
        </select>
        <div id="searchbar" class="menuBtn al-searchbar-wrap" placeholder="Enter test name" onclick="add_class();"></div>
        <button onclick="get_select_value();" class="al-search-btn" aria-label="Search test">
          <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke="#fff" stroke-width="2" fill="none"/></svg>
          <span id="btn_search">Search Test</span>
        </button>
        <p class="al-quick-label">Popular Searches</p>
        <div class="al-quick-tags">
          <?php $qtests = ['CBC','Thyroid','Diabetes','Heart','Full Body','Women']; foreach($qtests as $qt) { ?>
          <a href="<?php echo base_url(); ?>user_master/all_packages" class="al-quick-tag"><?= $qt ?></a>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>

  <!-- TRUST STRIP -->
  <div class="al-trust-strip" role="complementary">
    <div class="al-trust-inner">
      <div class="al-trust-item"><div class="al-trust-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg></div><span class="al-trust-label">On-Time Home Visits</span></div>
      <div class="al-trust-item"><div class="al-trust-icon"><svg viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div><span class="al-trust-label">Fastest TAT in City</span></div>
      <div class="al-trust-item"><div class="al-trust-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div><span class="al-trust-label">Painless Collection</span></div>
      <div class="al-trust-item"><div class="al-trust-icon"><svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div><span class="al-trust-label">Accurate Results</span></div>
      <div class="al-trust-item"><div class="al-trust-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .92h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></div><span class="al-trust-label">24/7 Assistance</span></div>
      <div class="al-trust-item"><div class="al-trust-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div><span class="al-trust-label">Digital Reports</span></div>
    </div>
  </div>

  <!-- ===================================================
       SECTION 1 — POPULAR BLOOD TESTS (UPDATED)
       Images path: user_assets/images/tests/
       Add images: cbc.jpg, thyroid.jpg, hba1c.jpg, lipid.jpg,
                   vitd.jpg, lft.jpg, kft.jpg, dengue.jpg
       =================================================== -->
  <section class="al-section al-popular-section" id="popular-tests" aria-labelledby="popular-title">
    <div class="al-container">
      <div class="al-section-header">
        <div>
          <span class="al-section-tag">Popular Tests</span>
          <h2 class="al-section-title" id="popular-title">Most Booked Blood Tests</h2>
          <p class="al-section-sub">Fast NABL certified results with home collection in 30 minutes.</p>
        </div>
        <a href="<?php echo base_url(); ?>user_master/all_packages" class="al-view-all" aria-label="View all tests">
          View All Tests
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="al-tests-grid">

        <!-- CBC — highlighted as most popular -->
        <div class="al-test-card al-test-card-highlight sr sr-delay-1">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Complete Blood Count CBC Test Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--red-light);font-size:52px;\'>🩸</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Complete Blood Count (CBC)</div>
            <div class="al-test-params">28 parameters · RBC, WBC, Haemoglobin, Platelets</div>
            <div class="al-test-footer">
              <span class="al-test-tag">NABL</span>
              <button class="al-test-book" onclick="openBooking('Complete Blood Count - CBC (28 Parameters)', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Thyroid -->
        <div class="al-test-card sr sr-delay-2">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Thyroid Profile T3 T4 TSH Test Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0fffe;font-size:52px;\'>🦋</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Thyroid Profile (T3, T4, TSH)</div>
            <div class="al-test-params">3 parameters · Thyroid function assessment</div>
            <div class="al-test-footer">
              <span class="al-test-tag">NABL</span>
              <button class="al-test-book" onclick="openBooking('Thyroid Profile - T3, T4, TSH (3 Parameters)', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- HbA1c -->
        <div class="al-test-card sr sr-delay-3">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="HbA1c Glycated Haemoglobin Test Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#fff8f0;font-size:52px;\'>💉</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">HbA1c (Glycated Haemoglobin)</div>
            <div class="al-test-params">3-month blood sugar average · Diabetes monitoring</div>
            <div class="al-test-footer">
              <span class="al-test-tag">NABL</span>
              <button class="al-test-book" onclick="openBooking('HbA1c - Glycated Haemoglobin', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Lipid Profile — highlighted -->
        <div class="al-test-card al-test-card-highlight sr sr-delay-4">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Lipid Profile Cholesterol Test Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--red-light);font-size:52px;\'>❤️</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Lipid Profile</div>
            <div class="al-test-params">10 parameters · Cholesterol, HDL, LDL, Triglycerides</div>
            <div class="al-test-footer">
              <span class="al-test-tag">NABL</span>
              <button class="al-test-book" onclick="openBooking('Lipid Profile (10 Parameters)', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Vitamin D3 -->
        <div class="al-test-card sr sr-delay-1">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Vitamin D3 25-OH Test Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#fffbeb;font-size:52px;\'>🌞</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Vitamin D3 (25-OH)</div>
            <div class="al-test-params">Vitamin D deficiency screening · Bone &amp; immunity health</div>
            <div class="al-test-footer">
              <span class="al-test-tag" style="background:var(--red-light);color:var(--red);">Popular</span>
              <button class="al-test-book" onclick="openBooking('Vitamin D3 - 25 OH', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- LFT -->
        <div class="al-test-card sr sr-delay-2">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Liver Function Test LFT Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0fff9;font-size:52px;\'>🫁</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Liver Function Test (LFT)</div>
            <div class="al-test-params">12 parameters · SGPT, SGOT, Bilirubin, Albumin, ALP</div>
            <div class="al-test-footer">
              <span class="al-test-tag">NABL</span>
              <button class="al-test-book" onclick="openBooking('Liver Function Test - LFT (12 Parameters)', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- KFT -->
        <div class="al-test-card sr sr-delay-3">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Kidney Function Test KFT Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f5f0ff;font-size:52px;\'>🧪</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Kidney Function Test (KFT)</div>
            <div class="al-test-params">7 parameters · Creatinine, Urea, Uric Acid, BUN</div>
            <div class="al-test-footer">
              <span class="al-test-tag">NABL</span>
              <button class="al-test-book" onclick="openBooking('Kidney Function Test - KFT (7 Parameters)', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Dengue — urgent highlight -->
        <div class="al-test-card al-test-card-urgent sr sr-delay-4">
          <div class="al-test-img-wrap">
            <img src="<?php echo base_url(); ?>upload/package/blood-test-section-airmedlabs.jpg"
                 alt="Dengue NS1 Antigen Test Ahmedabad" loading="lazy"
                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#fffbeb;font-size:52px;\'>🌡️</div>'">
          </div>
          <div class="al-test-body">
            <div class="al-test-name">Dengue NS1 Antigen</div>
            <div class="al-test-params">Early dengue detection · High-sensitivity card test</div>
            <div class="al-test-footer">
              <span class="al-test-tag al-test-tag-urgent">Urgent</span>
              <button class="al-test-book" onclick="openBooking('Dengue NS1 Antigen Test', 'Call for Price')">Book Now</button>
            </div>
          </div>
        </div>

      </div>
      <!-- Image note for developer -->
      <!-- NOTE: Add test images at: user_assets/images/tests/
           Files: cbc.jpg, thyroid.jpg, hba1c.jpg, lipid.jpg, vitd.jpg, lft.jpg, kft.jpg, dengue.jpg
           Recommended size: 400x260px (3:2 ratio). Fallback emoji shown if image missing. -->
    </div>
  </section>

  <!-- ===================================================
       SECTION 2 — HEALTH PACKAGES (PRESERVED)
       =================================================== -->
  <section class="al-section-alt al-packages-section" id="packages" aria-labelledby="pkg-title">
    <div class="al-container">
      <div class="al-section-header">
        <div>
          <span class="al-section-tag">Health Packages</span>
          <h2 class="al-section-title" id="pkg-title">Comprehensive Health Packages</h2>
          <p class="al-section-sub">Curated diagnostic panels for every health need. NABL certified, best prices in Ahmedabad.</p>
        </div>
        <a href="<?php echo base_url(); ?>user_master/all_packages" class="al-view-all">
          All Packages
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <!-- Fever Packages Row -->
      <div style="margin-bottom:48px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;padding-bottom:14px;border-bottom:2px solid #eef0f4;">
          <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,var(--red),var(--red-mid));display:flex;align-items:center;justify-content:center;font-size:20px;">🌡️</div>
          <div>
            <div style="font-family:var(--font-display);font-size:1.3rem;color:var(--navy);">Fever Profile</div>
            <div style="font-size:12px;color:var(--gray-400);">Comprehensive fever panel — fast diagnosis, faster recovery</div>
          </div>
        </div>
        <div class="al-pkg-grid">
          <article class="al-pkg-card sr sr-delay-1">
            <div class="al-pkg-header">
              <div class="al-pkg-icon">🌡️</div>
              <div class="al-pkg-name">Fever Profile 1</div>
              <div class="al-pkg-param-count">34 Tests Included</div>
            </div>
            <div class="al-pkg-body">
              <ul class="al-pkg-includes">
                <li>Malaria Antigen — Plasmodium Vivax &amp; Falciparum</li>
                <li>Typhoid Widal — Salmonella Typhi H &amp; O, Paratyphi A &amp; B</li>
                <li>CBC — 28 Parameters (RBC, WBC, Platelets, Hb)</li>
              </ul>
            </div>
            <div class="al-pkg-footer">
              <button class="al-btn-wa-pkg" onclick="openBooking('Fever Profile 1 (34 Tests)', 'Call for Price')">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                Book via WhatsApp
              </button>
            </div>
          </article>

          <article class="al-pkg-card al-pkg-card-featured sr sr-delay-2">
            <div class="al-pkg-header al-pkg-header-wine">
              <div class="al-pkg-badge-wrap"><span class="al-pkg-badge">Popular</span></div>
              <div class="al-pkg-icon">🔥</div>
              <div class="al-pkg-name">Fever Profile 2</div>
              <div class="al-pkg-param-count">38 Tests Included</div>
            </div>
            <div class="al-pkg-body">
              <ul class="al-pkg-includes">
                <li>Malaria Antigen — Vivax &amp; Falciparum</li>
                <li>Typhoid IgG &amp; IgM, Dengue NS1 Antigen</li>
                <li>Liver Profile, ESR, CRP, CBC (28 Parameters)</li>
              </ul>
            </div>
            <div class="al-pkg-footer">
              <button class="al-btn-wa-pkg" onclick="openBooking('Fever Profile 2 (38 Tests)', 'Call for Price')">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                Book via WhatsApp
              </button>
            </div>
          </article>

          <article class="al-pkg-card sr sr-delay-3">
            <div class="al-pkg-header">
              <div class="al-pkg-icon">💊</div>
              <div class="al-pkg-name">Fever Profile 3</div>
              <div class="al-pkg-param-count">42 Tests Included</div>
            </div>
            <div class="al-pkg-body">
              <ul class="al-pkg-includes">
                <li>Malaria Antigen, Typhoid IgG &amp; IgM</li>
                <li>Dengue IgG, IgM &amp; NS1 Antigen</li>
                <li>Chikungunya IgG &amp; IgM, Liver Profile, ESR, CRP, CBC (28)</li>
              </ul>
            </div>
            <div class="al-pkg-footer">
              <button class="al-btn-wa-pkg" onclick="openBooking('Fever Profile 3 (42 Tests)', 'Call for Price')">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                Book via WhatsApp
              </button>
            </div>
          </article>
        </div>
      </div>

      <!-- Specialty Packages Row -->
      <div style="margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;padding-bottom:14px;border-bottom:2px solid #eef0f4;">
          <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#0d4f42,#1abc9c);display:flex;align-items:center;justify-content:center;font-size:20px;">🩺</div>
          <div>
            <div style="font-family:var(--font-display);font-size:1.3rem;color:var(--navy);">Specialty Health Packages</div>
            <div style="font-size:12px;color:var(--gray-400);">Targeted panels for diabetes, women's health, men's health &amp; more</div>
          </div>
        </div>
        <div class="al-pkg-grid">
          <article class="al-pkg-card al-pkg-card-featured sr sr-delay-1">
            <div class="al-pkg-header al-pkg-header-teal">
              <div class="al-pkg-badge-wrap"><span class="al-pkg-badge al-pkg-badge-green">Recommended</span></div>
              <div class="al-pkg-icon">🩸</div>
              <div class="al-pkg-name">Diabetes Profile</div>
              <div class="al-pkg-param-count">6 Tests Included</div>
            </div>
            <div class="al-pkg-body">
              <ul class="al-pkg-includes">
                <li>HbA1c, FBS &amp; PPBS (Fasting + Post-meal)</li>
                <li>Creatinine, Urine Routine</li>
                <li>Microalbumin (Kidney screening)</li>
              </ul>
            </div>
            <div class="al-pkg-footer">
              <button class="al-btn-wa-pkg" onclick="openBooking('Diabetes Profile (6 Tests)', 'Call for Price')">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                Book via WhatsApp
              </button>
            </div>
          </article>

          <article class="al-pkg-card sr sr-delay-2">
            <div class="al-pkg-header" style="background:linear-gradient(135deg,#4a0e1e,#9b2335);">
              <div class="al-pkg-badge-wrap"><span class="al-pkg-badge">Trending</span></div>
              <div class="al-pkg-icon">🌸</div>
              <div class="al-pkg-name">Female Profile with UTSH</div>
              <div class="al-pkg-param-count">110 Parameters</div>
            </div>
            <div class="al-pkg-body">
              <ul class="al-pkg-includes">
                <li>Thyroid (3), Lipid (10), Liver (12), Kidney (7), CBC (28)</li>
                <li>Infertility: FSH, LH, Prolactin; Cancer: CA-125 &amp; CEA</li>
                <li>Cardiac Risk (5), Iron Deficiency (4), Vitamins (2), Estradiol</li>
              </ul>
            </div>
            <div class="al-pkg-footer">
              <button class="al-btn-wa-pkg" onclick="openBooking('Female Profile with UTSH (110 Parameters)', 'Call for Price')">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                Book via WhatsApp
              </button>
            </div>
          </article>

          <article class="al-pkg-card sr sr-delay-3">
            <div class="al-pkg-header al-pkg-header-midnight">
              <div class="al-pkg-badge-wrap"><span class="al-pkg-badge al-pkg-badge-blue">Recommended</span></div>
              <div class="al-pkg-icon">💪</div>
              <div class="al-pkg-name">Male Profile with UTSH</div>
              <div class="al-pkg-param-count">107 Parameters</div>
            </div>
            <div class="al-pkg-body">
              <ul class="al-pkg-includes">
                <li>Thyroid (3), Lipid (10), Liver (12), Kidney (7), CBC (28)</li>
                <li>Diabetes (2), Testosterone, Cancer Profile: CA-125 &amp; CEA</li>
                <li>Cardiac Risk (5), Iron Deficiency (4), Vitamins (2), Folate</li>
              </ul>
            </div>
            <div class="al-pkg-footer">
              <button class="al-btn-wa-pkg" onclick="openBooking('Male Profile with UTSH (107 Parameters)', 'Call for Price')">
                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                Book via WhatsApp
              </button>
            </div>
          </article>
        </div>
      </div>

      <div class="al-view-all-cta sr">
        <a href="<?php echo base_url(); ?>user_master/all_packages" class="al-btn-navy">
          <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:#fff;"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
          View All Health Packages
        </a>
      </div>
    </div>
  </section>

  <!-- ===================================================
       SECTION 3 — SPECIAL OFFERS (REPLACES FULL BODY)
       =================================================== -->
  <section class="al-section al-offers-section" id="special-offers" aria-labelledby="offers-title">
    <div class="al-container al-offers-inner">
      <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:0;">
        <div>
          <span class="al-section-tag">🎁 Limited Time</span>
          <h2 class="al-section-title" id="offers-title" style="margin-top:14px;">Special Offers<br/>Only at Airmed Patholab</h2>
          <p class="al-section-sub">Exclusive health checkup bundles at unbeatable value. Book now — limited slots available every day.</p>
        </div>
        <!-- Urgency strip -->
        <div style="background:rgba(232,160,32,.15);border:1px solid rgba(232,160,32,.3);border-radius:var(--radius-sm);padding:12px 20px;text-align:center;flex-shrink:0;">
          <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(232,160,32,.9);margin-bottom:4px;">⏰ Limited Daily Slots</div>
          <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,.8);">Book Today · Same Day Collection</div>
        </div>
      </div>

      <div class="al-offers-grid">

        <!-- OFFER 1: 1+1 Full Body Checkup -->
        <div class="al-offer-card al-offer-card-1 sr sr-delay-1">
          <div class="al-offer-ribbon">🔥 Best Value</div>
          <div class="al-offer-header">
            <div class="al-offer-icon-wrap al-offer-icon-red">🧬</div>
            <div style="text-align:right;">
              <div class="al-offer-badge-big al-offer-badge-big-red">1+1</div>
              <div class="al-offer-badge-label al-offer-badge-label-red">Buy One · Get One FREE</div>
            </div>
          </div>
          <div class="al-offer-body">
            <div class="al-offer-name">Full Body Checkup<br/>1+1 Offer</div>
            <div class="al-offer-desc">Book one Full Body Checkup — get a second one absolutely FREE. Perfect for couples or family pairs. Share with a loved one!</div>
            <div class="al-offer-params-row">
              <span class="al-offer-param-pill al-offer-param-pill-red">70+ Parameters</span>
              <span class="al-offer-param-pill al-offer-param-pill-red">Home Collection</span>
              <span class="al-offer-param-pill al-offer-param-pill-red">NABL Certified</span>
              <span class="al-offer-param-pill al-offer-param-pill-red">Same Day Report</span>
            </div>
            <ul class="al-offer-includes al-offer-includes-check-red">
              <li>CBC 28 Parameters + Thyroid Profile (T3, T4, TSH)</li>
              <li>Liver Function Test (12) + Kidney Function Test (7)</li>
              <li>Lipid Profile (10) + Diabetes Panel (HbA1c, FBS)</li>
              <li>Vitamin D3, Vitamin B12, Iron Deficiency (4)</li>
              <li>Cardiac Risk Panel — hsCRP, Homocysteine (5)</li>
              <li style="font-weight:700;color:rgba(231,76,60,.9);border-top:1px solid rgba(192,57,43,.2);padding-top:8px;">🎁 Second Full Body Checkup — FREE for family/friend</li>
            </ul>
          </div>
          <div class="al-offer-footer">
            <button onclick="openBooking('Special Offer: 1+1 Full Body Checkup (Buy One Get One FREE)', 'Call for Offer Price')" class="al-btn-offer-red">
              <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:#fff;flex-shrink:0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
              Claim This Offer on WhatsApp
            </button>
          </div>
        </div>

        <!-- OFFER 2: Full Body Blood Checkup + DEXA Scan -->
        <div class="al-offer-card al-offer-card-2 sr sr-delay-2">
          <div class="al-offer-ribbon al-offer-ribbon-green">✨ New Offer</div>
          <div class="al-offer-header">
            <div class="al-offer-icon-wrap al-offer-icon-teal">🦴</div>
            <div style="text-align:right;">
              <div class="al-offer-badge-big al-offer-badge-big-teal">2-in-1</div>
              <div class="al-offer-badge-label al-offer-badge-label-teal">Blood + Bone Health Bundle</div>
            </div>
          </div>
          <div class="al-offer-body">
            <div class="al-offer-name">Full Body Blood Checkup<br/>+ DEXA Scan</div>
            <div class="al-offer-desc">The most complete health screening combo — full blood panel (70+ parameters) paired with DEXA Bone Density Scan. Know your blood and your bones.</div>
            <div class="al-offer-highlight-box">
              <span class="al-offer-highlight-icon">🔬</span>
              <div>
                <div style="font-size:13px;font-weight:700;color:#fff;">What is a DEXA Scan?</div>
                <div style="font-size:12px;color:rgba(255,255,255,.55);margin-top:2px;">Bone mineral density test — detects early osteoporosis risk. Especially important for women 40+ and seniors.</div>
              </div>
            </div>
            <div class="al-offer-params-row">
              <span class="al-offer-param-pill al-offer-param-pill-teal">70+ Blood Parameters</span>
              <span class="al-offer-param-pill al-offer-param-pill-teal">DEXA Bone Scan</span>
              <span class="al-offer-param-pill al-offer-param-pill-teal">NABL Lab</span>
              <span class="al-offer-param-pill al-offer-param-pill-teal">Home Blood Collection</span>
            </div>
            <ul class="al-offer-includes al-offer-includes-check-teal">
              <li>Full Body Blood Test — 70+ Parameters (all systems)</li>
              <li>DEXA Bone Mineral Density Scan (at centre)</li>
              <li>Vitamin D3 &amp; Calcium — critical for bone health</li>
              <li>Thyroid + Parathyroid screening included</li>
              <li>Detailed bone density report with T-score &amp; Z-score</li>
              <li style="font-weight:700;color:#4dddb8;border-top:1px solid rgba(26,188,156,.15);padding-top:8px;">📋 Combined health report — shareable with your doctor</li>
            </ul>
          </div>
          <div class="al-offer-footer">
            <button onclick="openBooking('Special Offer: Full Body Blood Checkup + DEXA Scan Bundle', 'Call for Offer Price')" class="al-btn-offer-teal">
              <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:currentColor;flex-shrink:0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
              Get Offer Price on WhatsApp
            </button>
          </div>
        </div>

      </div>

      <!-- Trust note under offers -->
      <div style="display:flex;align-items:center;justify-content:center;gap:24px;margin-top:36px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.5);">
          <span style="color:#4ddb88;font-weight:700;">✓</span> NABL Accredited Lab
        </div>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.5);">
          <span style="color:#4ddb88;font-weight:700;">✓</span> 30-Min Home Collection
        </div>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.5);">
          <span style="color:#4ddb88;font-weight:700;">✓</span> Report in 4–6 Hours
        </div>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.5);">
          <span style="color:#4ddb88;font-weight:700;">✓</span> No Hidden Charges
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       WHY CHOOSE AIRMED
       =================================================== -->
  <section class="al-section al-why-section" id="why-airmed" aria-labelledby="why-title">
    <div class="al-container">
      <div class="al-why-grid-main">
        <div>
          <span class="al-section-tag">Why Choose Us</span>
          <h2 class="al-section-title" id="why-title">Why Ahmedabad Trusts Airmed Patholab</h2>
          <p class="al-section-sub">Ahmedabad's most trusted NABL accredited pathology lab — clinical precision, fastest turnaround time, and true home convenience.</p>
          <div class="al-why-features">
            <div class="al-why-feat sr sr-delay-1">
              <div class="al-why-feat-icon"><svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg></div>
              <div class="al-why-feat-title">NABL Accredited Lab</div>
              <div class="al-why-feat-desc">16+ tests certified under NABL. Highest standards of laboratory accuracy and quality assurance.</div>
            </div>
            <div class="al-why-feat sr sr-delay-2">
              <div class="al-why-feat-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg></div>
              <div class="al-why-feat-title">30-Min Home Collection</div>
              <div class="al-why-feat-desc">Certified phlebotomist at your doorstep within 30 minutes across Ahmedabad &amp; Gandhinagar.</div>
            </div>
            <div class="al-why-feat sr sr-delay-3">
              <div class="al-why-feat-icon"><svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
              <div class="al-why-feat-title">Fastest Report TAT</div>
              <div class="al-why-feat-desc">Reports delivered in 4–6 hours on WhatsApp and email. Lowest TAT in Ahmedabad.</div>
            </div>
            <div class="al-why-feat sr sr-delay-4">
              <div class="al-why-feat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8z"/></svg></div>
              <div class="al-why-feat-title">Trained Phlebotomists</div>
              <div class="al-why-feat-desc">Certified &amp; experienced with Coolpack &amp; Barcode technology for safe sample transport.</div>
            </div>
            <div class="al-why-feat sr sr-delay-1">
              <div class="al-why-feat-icon"><svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/></svg></div>
              <div class="al-why-feat-title">WhatsApp Booking</div>
              <div class="al-why-feat-desc">Instant booking in 60 seconds. No app needed. Confirmations, reminders, reports — all on chat.</div>
            </div>
            <div class="al-why-feat sr sr-delay-2">
              <div class="al-why-feat-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
              <div class="al-why-feat-title">Digital Reports</div>
              <div class="al-why-feat-desc">View, download, and share all your reports digitally. Complete blood report history online.</div>
            </div>
          </div>
          <div class="al-aeo-block sr">
            <h3>Best Pathology Lab in Ahmedabad for Home Blood Collection</h3>
            <p itemprop="description">Airmed Patholab is the most preferred NABL accredited diagnostic laboratory in Ahmedabad offering home blood collection. Certified phlebotomists serve all major areas including Satellite, Bopal, Navrangpura, Prahlad Nagar, Vastrapur, Thaltej, SG Highway, Maninagar, Nikol, and Chandkheda.</p>
            <p>We serve Gandhinagar Sector 11, 21, 28 and surrounding areas. Same-day reports, transparent pricing, and painless collection make us the first choice for families and senior citizens in Gujarat.</p>
          </div>
        </div>
        <div class="al-why-right sr">
          <div class="al-why-right-img">
            <img src="<?php echo base_url(); ?>user_assets/images/home/airmedlabs.png" alt="Airmed Patholab — NABL Accredited Pathology Lab Ahmedabad" loading="lazy" width="560" height="420"/>
            <div class="al-why-img-stat">
              <div class="al-why-img-stat-num">50K+</div>
              <div class="al-why-img-stat-text">Satisfied Patients<br>Ahmedabad &amp; Gandhinagar</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       HOW IT WORKS
       =================================================== -->
  <section class="al-section al-how-section" id="how-it-works" aria-labelledby="how-title">
    <div class="al-container al-how-inner">
      <span class="al-section-tag">Process</span>
      <h2 class="al-section-title" id="how-title">How Home Blood Collection Works</h2>
      <p class="al-section-sub">From WhatsApp booking to digital report delivery — 4 simple steps.</p>
      <div class="al-steps" role="list">
        <div class="al-step sr sr-delay-1" role="listitem"><div class="al-step-num">💬</div><div class="al-step-title">Book via WhatsApp</div><div class="al-step-desc">Send a WhatsApp to +919725504245. Share your name, address, and test required. Done in 60 seconds.</div></div>
        <div class="al-step sr sr-delay-2" role="listitem"><div class="al-step-num">📅</div><div class="al-step-title">Choose Your Slot</div><div class="al-step-desc">Pick your preferred morning or evening time. We confirm within minutes. Early morning fasting slots available.</div></div>
        <div class="al-step sr sr-delay-3" role="listitem"><div class="al-step-num">🏠</div><div class="al-step-title">Home Collection</div><div class="al-step-desc">Our certified phlebotomist arrives in 30 minutes. Painless, hygienic, barcode-tagged collection.</div></div>
        <div class="al-step sr sr-delay-4" role="listitem"><div class="al-step-num">📲</div><div class="al-step-title">Digital Reports</div><div class="al-step-desc">NABL certified reports on WhatsApp &amp; email within 4–6 hours. Shareable directly with your doctor.</div></div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       LOGISTICS / INFRASTRUCTURE
       =================================================== -->
  <section class="al-section al-logistic-section" id="logistics" aria-labelledby="logistics-title">
    <div class="al-container">
      <span class="al-section-tag">Infrastructure</span>
      <h2 class="al-section-title" id="logistics-title">Strong Logistics &amp; Technology Platform</h2>
      <p class="al-section-sub">State-of-the-art instruments, cold-chain transport, and IT-backed quality control at every step.</p>
      <div class="al-logistic-grid">
        <div class="al-logistic-card sr sr-delay-1">
          <img src="<?php echo base_url(); ?>user_assets/images/home/banner_img2.jpg" alt="Advanced pathology technology — Airmed Patholab Ahmedabad" loading="lazy"/>
          <div class="al-logistic-overlay"></div>
          <div class="al-logistic-body"><div class="al-logistic-icon"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></div><div class="al-logistic-title">Advanced Technology</div><div class="al-logistic-desc">Precision automated analysers with real-time quality control for accurate, reproducible results.</div></div>
        </div>
        <div class="al-logistic-card sr sr-delay-2">
          <img src="<?php echo base_url(); ?>user_assets/images/home/banner_img3.png" alt="Fastest report TAT — Airmed Patholab" loading="lazy"/>
          <div class="al-logistic-overlay"></div>
          <div class="al-logistic-body"><div class="al-logistic-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="al-logistic-title">Lowest TAT in City</div><div class="al-logistic-desc">Reports in 4–6 hours. Fastest turnaround among pathology labs in Ahmedabad and Gandhinagar.</div></div>
        </div>
        <div class="al-logistic-card sr sr-delay-3">
          <img src="<?php echo base_url(); ?>user_assets/images/home/banner_img1.jpg" alt="Trained phlebotomist team — home blood collection Ahmedabad" loading="lazy"/>
          <div class="al-logistic-overlay"></div>
          <div class="al-logistic-body"><div class="al-logistic-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div><div class="al-logistic-title">Trained Collection Team</div><div class="al-logistic-desc">Certified phlebotomists with Coolpack &amp; Barcode tracking ensure sample integrity from home to lab.</div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       NABL ACCREDITATION
       =================================================== -->
  <section class="al-section al-nabl-section" id="accreditation" aria-labelledby="nabl-title">
    <div class="al-container">
      <div class="al-nabl-grid">
        <div class="al-nabl-left sr">
          <span class="al-section-tag">Accreditation</span>
          <h2 class="al-section-title" id="nabl-title">NABL Accredited Pathology Lab — Ahmedabad</h2>
          <p class="al-section-sub">Airmed Patholab is accredited by NABL ensuring international standards of test accuracy, reliability, and safety.</p>
          <div class="al-nabl-cert">
            <img src="<?php echo base_url(); ?>user_assets/images/nabl_logo.png" alt="NABL Accreditation Logo" loading="lazy" width="80" height="56"/>
            <div class="al-nabl-cert-info"><strong>NABL Certified Tests</strong><small>16+ Tests Quality Certified · ISO 15189</small></div>
          </div>
          <div class="al-nabl-tags">
            <?php
            $nablTests = ['Albumin','BUN','Alkaline Phosphatase','Bilirubin','Calcium','Creatinine','Cholesterol','Glucose','HDL','SGPT','SGOT','Triglycerides','Uric Acid','Urea','Total Protein'];
            foreach($nablTests as $t) { ?><span class="al-nabl-tag"><?= $t ?></span><?php } ?>
          </div>
        </div>
        <div class="al-nabl-right">
          <div class="al-stat-cards">
            <div class="al-stat-card sr sr-delay-1"><div class="al-stat-card-num">50K+</div><div class="al-stat-card-label">Satisfied Patients</div><div class="al-stat-card-sub">Ahmedabad &amp; Gandhinagar</div></div>
            <div class="al-stat-card sr sr-delay-2"><div class="al-stat-card-num">300+</div><div class="al-stat-card-label">Tests Available</div><div class="al-stat-card-sub">Routine to specialized</div></div>
            <div class="al-stat-card sr sr-delay-3"><div class="al-stat-card-num">30 min</div><div class="al-stat-card-label">Home Collection</div><div class="al-stat-card-sub">Across Ahmedabad</div></div>
            <!--<div class="al-stat-card sr sr-delay-4"><div class="al-stat-card-num">4–6 hr</div><div class="al-stat-card-label">Report Delivery</div><div class="al-stat-card-sub">WhatsApp &amp; Email</div></div>-->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       FAQ
       =================================================== -->
  <section class="al-section al-faq-section" id="faq" aria-labelledby="faq-title">
    <div class="al-container">
      <div class="al-faq-grid">
        <div class="al-faq-left sr">
          <span class="al-section-tag">FAQs</span>
          <h2 class="al-section-title" id="faq-title">Frequently Asked Questions</h2>
          <p class="al-section-sub">Quick answers about blood tests, home collection, reports, and more.</p>
          <div style="margin-top:28px;padding:22px 24px;background:var(--red-light);border-radius:var(--radius);border-left:4px solid var(--red);">
            <p style="font-size:14px;color:var(--gray-600);line-height:1.7;"><strong style="color:var(--navy);">Still have questions?</strong><br/>WhatsApp us at <a href="https://wa.me/919725504245" style="color:var(--red);font-weight:700;" target="_blank" rel="noopener">+91 97255 04245</a> — we reply within minutes, 7 days a week.</p>
          </div>
        </div>
        <div class="al-faq-list sr sr-delay-2">
          <div class="al-faq-item" itemscope itemtype="https://schema.org/Question">
            <button class="al-faq-q" onclick="toggleFaq(this)" aria-expanded="false"><span itemprop="name">Does Airmed Patholab offer home blood collection in Ahmedabad?</span><div class="al-faq-arrow"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div></button>
            <div class="al-faq-a" itemscope itemtype="https://schema.org/Answer"><div class="al-faq-a-inner" itemprop="text">Yes. Airmed Patholab provides certified phlebotomist home visits across Ahmedabad and Gandhinagar within 30 minutes. Book via WhatsApp at +919725504245. We cover Satellite, Bopal, Navrangpura, Prahlad Nagar, Vastrapur, Thaltej, SG Highway, Maninagar, Nikol, Chandkheda and more.</div></div>
          </div>
          <div class="al-faq-item" itemscope itemtype="https://schema.org/Question">
            <button class="al-faq-q" onclick="toggleFaq(this)" aria-expanded="false"><span itemprop="name">Is Airmed Labs NABL accredited?</span><div class="al-faq-arrow"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div></button>
            <div class="al-faq-a" itemscope itemtype="https://schema.org/Answer"><div class="al-faq-a-inner" itemprop="text">Yes. Airmed Patholab is NABL accredited (ISO 15189) with 16+ certified tests including CBC, Lipid Profile, Liver Function Tests, Kidney Function Tests, Glucose, SGPT, SGOT, Bilirubin, Creatinine, and more.</div></div>
          </div>
          <div class="al-faq-item" itemscope itemtype="https://schema.org/Question">
            <button class="al-faq-q" onclick="toggleFaq(this)" aria-expanded="false"><span itemprop="name">How quickly do I get my blood test reports?</span><div class="al-faq-arrow"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div></button>
            <div class="al-faq-a" itemscope itemtype="https://schema.org/Answer"><div class="al-faq-a-inner" itemprop="text">Airmed Labs has the fastest TAT in Ahmedabad. Most routine reports — CBC, Liver Profile, Kidney Profile, Lipid Profile, Thyroid — are delivered within 4–6 hours directly on your WhatsApp and email. Specialized tests may take 24–48 hours.</div></div>
          </div>
          <div class="al-faq-item" itemscope itemtype="https://schema.org/Question">
            <button class="al-faq-q" onclick="toggleFaq(this)" aria-expanded="false"><span itemprop="name">What is the 1+1 Full Body Checkup offer?</span><div class="al-faq-arrow"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div></button>
            <div class="al-faq-a" itemscope itemtype="https://schema.org/Answer"><div class="al-faq-a-inner" itemprop="text">Our 1+1 Special Offer lets you buy one Full Body Checkup (70+ parameters) and get a second one absolutely FREE. Both checkups include home blood collection, NABL certified testing, and digital report delivery in 4–6 hours. Ideal for couples or a family member pair. WhatsApp us for current offer pricing.</div></div>
          </div>
          <div class="al-faq-item" itemscope itemtype="https://schema.org/Question">
            <button class="al-faq-q" onclick="toggleFaq(this)" aria-expanded="false"><span itemprop="name">Do I need to fast before a blood test?</span><div class="al-faq-arrow"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div></button>
            <div class="al-faq-a" itemscope itemtype="https://schema.org/Answer"><div class="al-faq-a-inner" itemprop="text">Fasting (8–12 hours) is required for FBS, Lipid Profile, and certain liver tests. Tests like CBC, TSH, and HbA1c do not require fasting. Our team will guide you on fasting requirements when you book on WhatsApp.</div></div>
          </div>
          <div class="al-faq-item" itemscope itemtype="https://schema.org/Question">
            <button class="al-faq-q" onclick="toggleFaq(this)" aria-expanded="false"><span itemprop="name">What areas in Ahmedabad do you cover for home collection?</span><div class="al-faq-arrow"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div></button>
            <div class="al-faq-a" itemscope itemtype="https://schema.org/Answer"><div class="al-faq-a-inner" itemprop="text">We cover all major areas including Satellite, Bopal, Navrangpura, Prahlad Nagar, Vastrapur, Thaltej, SG Highway, Maninagar, Nikol, Chandkheda, Gota, Motera, Naranpura, Ambawadi, and all Gandhinagar sectors.</div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================================================
       PATIENT REVIEWS — GBM / GOOGLE STYLE (UPDATED)
       =================================================== -->
  <section class="al-section al-reviews-section" id="reviews" aria-labelledby="reviews-title">
    <div class="al-container">
      <div class="al-reviews-header-inner">
        <div>
          <span class="al-section-tag">Patient Reviews</span>
          <h2 class="al-section-title" id="reviews-title">What Patients Say on Google</h2>
          <p class="al-section-sub">Thousands of real patient reviews from across Ahmedabad &amp; Gandhinagar on Google Maps.</p>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:14px;">
          <!-- Aggregate rating box -->
          <div class="al-reviews-score-box">
            <div class="al-reviews-score-num">4.9</div>
            <div class="al-reviews-score-right">
              <div class="al-reviews-stars-big">★★★★★</div>
              <div class="al-reviews-score-count">Based on 1,280+ Google Reviews</div>
              <!-- Google Maps link — replace with your actual GBM URL -->
              <a href="https://g.page/r/airmedpatholab/review" target="_blank" rel="noopener" class="al-reviews-google-badge" style="margin-top:8px;">
                <svg class="al-google-logo" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                  <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                  <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                  <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                View on Google Maps
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Google-style review cards -->
      <!-- NOTE: Replace static reviews below with your PHP $testimonial loop.
           The static cards below are examples — map your $testimonial array to this format. -->
      <div class="al-reviews-grid">

        <!-- Dynamic reviews from DB — using PHP $testimonial array -->
        <?php
        // Avatar background colors cycle
        $avatarColors = ['#c0392b','#1abc9c','#2980b9','#8e44ad','#e67e22','#27ae60','#e74c3c','#16a085'];
        $serviceLabels = ['Full Body Checkup','CBC + Thyroid','Fever Profile','Home Collection','Lipid Profile','Diabetes Profile'];
        if (!empty($testimonial)) :
          foreach ($testimonial as $idx => $key) :
            $bgColor = $avatarColors[$idx % count($avatarColors)];
            $initial = strtoupper(substr($key['name'], 0, 1));
            $service = $serviceLabels[$idx % count($serviceLabels)];
        ?>
        <article class="al-review-card sr" itemscope itemtype="https://schema.org/Review" style="transition-delay:<?= ($idx % 4) * 0.08 ?>s;">
          <div class="al-review-top">
            <div class="al-review-author-row">
              <?php if (!empty($key['image'])) : ?>
              <img src="<?php echo base_url(); ?>thumb_helper.php?h=96&w=96&src=upload/<?php echo $key['image']; ?>"
                   alt="<?php echo htmlspecialchars($key['name']); ?>"
                   class="al-review-avatar" loading="lazy" width="42" height="42"
                   style="object-fit:cover;border:2px solid var(--gray-100);"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="al-review-avatar" style="background:<?= $bgColor ?>;display:none;"><?= $initial ?></div>
              <?php else : ?>
              <div class="al-review-avatar" style="background:<?= $bgColor ?>;"><?= $initial ?></div>
              <?php endif; ?>
              <div class="al-review-author-info">
                <div class="al-review-name" itemprop="author"><?php echo htmlspecialchars($key['name']); ?><?php if (!empty($key['address'])) echo ', ' . htmlspecialchars($key['address']); ?></div>
                <div class="al-review-date">Ahmedabad, Gujarat</div>
              </div>
            </div>
            <!-- Google G SVG -->
            <svg class="al-review-google-g" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-label="Google Review">
              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
          </div>
          <div class="al-review-stars" aria-label="5 stars">★★★★★</div>
          <p class="al-review-text clamped" itemprop="reviewBody"><?php echo htmlspecialchars($key['description']); ?></p>
          <div class="al-review-service-tag"><?= $service ?></div>
          <div class="al-review-verified">Verified Google Review</div>
        </article>
        <?php endforeach; endif; ?>

        <!-- Fallback static reviews if $testimonial is empty — remove when DB has reviews -->
        <?php if (empty($testimonial)) : ?>
        <article class="al-review-card sr sr-delay-1">
          <div class="al-review-top">
            <div class="al-review-author-row">
              <div class="al-review-avatar" style="background:#c0392b;">P</div>
              <div class="al-review-author-info">
                <div class="al-review-name">Priya Shah, Satellite</div>
                <div class="al-review-date">1 week ago</div>
              </div>
            </div>
            <svg class="al-review-google-g" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          </div>
          <div class="al-review-stars">★★★★★</div>
          <p class="al-review-text clamped">Excellent service! The phlebotomist came within 20 minutes of booking. Collection was painless and my CBC + Thyroid report was on WhatsApp by afternoon. Best lab in Satellite area.</p>
          <div class="al-review-service-tag">CBC + Thyroid</div>
          <div class="al-review-verified">Verified Google Review</div>
        </article>
        <article class="al-review-card sr sr-delay-2">
          <div class="al-review-top">
            <div class="al-review-author-row">
              <div class="al-review-avatar" style="background:#1abc9c;">R</div>
              <div class="al-review-author-info">
                <div class="al-review-name">Rahul Mehta, Bopal</div>
                <div class="al-review-date">2 weeks ago</div>
              </div>
            </div>
            <svg class="al-review-google-g" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          </div>
          <div class="al-review-stars">★★★★★</div>
          <p class="al-review-text clamped">Booked the 1+1 Full Body Checkup offer for me and my wife. Amazing value! Both reports came in 5 hours. NABL certified lab and very professional team. Highly recommended to everyone in Ahmedabad.</p>
          <div class="al-review-service-tag">Full Body Checkup</div>
          <div class="al-review-verified">Verified Google Review</div>
        </article>
        <article class="al-review-card sr sr-delay-3">
          <div class="al-review-top">
            <div class="al-review-author-row">
              <div class="al-review-avatar" style="background:#8e44ad;">S</div>
              <div class="al-review-author-info">
                <div class="al-review-name">Sunita Patel, Gandhinagar</div>
                <div class="al-review-date">3 weeks ago</div>
              </div>
            </div>
            <svg class="al-review-google-g" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          </div>
          <div class="al-review-stars">★★★★★</div>
          <p class="al-review-text clamped">Got my Diabetes Profile done with home collection. The technician was on time and the collection was completely painless. My HbA1c and FBS reports were accurate and came before expected time. Will always use Airmed!</p>
          <div class="al-review-service-tag">Diabetes Profile</div>
          <div class="al-review-verified">Verified Google Review</div>
        </article>
        <?php endif; ?>

      </div>

      <!-- View on Google CTA -->
      <div class="al-reviews-cta sr">
        <a href="https://g.page/r/airmedpatholab/review" target="_blank" rel="noopener" class="al-btn-google-review">
          <svg style="width:20px;height:20px;flex-shrink:0;" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Read All Reviews on Google Maps
        </a>
      </div>
    </div>
  </section>

</main>

<!-- FLOATING WHATSAPP -->
<div class="al-float-wa" role="complementary" aria-label="Book via WhatsApp">
  <div class="al-wa-popup" id="alWaPopup" role="alert">
    <button class="al-wa-popup-close" onclick="document.getElementById('alWaPopup').style.display='none'" aria-label="Close">×</button>
    👋 Hi! Book any blood test at Airmed Patholab on WhatsApp. We reply in minutes!
  </div>
  <a href="https://wa.me/919725504245?text=Hi,%20I%20want%20to%20book%20a%20blood%20test%20at%20Airmed%20Patholab." target="_blank" rel="noopener" class="al-wa-btn" aria-label="Book on WhatsApp">
    <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
    <span class="al-wa-dot">1</span>
  </a>
</div>

<!-- BOOKING MODAL -->
<div class="al-modal-overlay" id="alBookingModal" role="dialog" aria-modal="true" aria-labelledby="al-modal-title">
  <div class="al-modal-box">
    <button class="al-modal-close" onclick="closeBooking()" aria-label="Close">×</button>
    <h3 class="al-modal-title" id="al-modal-title">Book Your Test</h3>
    <p class="al-modal-sub">Enter details &amp; we'll connect you on WhatsApp instantly.</p>
    <div class="al-modal-pkg" id="alModalPkgDisplay"></div>
    <div class="al-form-field">
      <label for="alBookName">Full Name <span style="color:var(--red)">*</span></label>
      <input type="text" id="alBookName" placeholder="e.g. Ramesh Patel" autocomplete="name"/>
    </div>
    <div class="al-form-field">
      <label for="alBookPhone">Mobile Number <span style="color:var(--red)">*</span></label>
      <input type="tel" id="alBookPhone" placeholder="10-digit mobile number" maxlength="10" autocomplete="tel"/>
    </div>
    <button class="al-btn-wa-submit" onclick="sendWhatsApp()">
      <svg viewBox="0 0 24 24" style="width:20px;height:20px;fill:#fff;flex-shrink:0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.116 1.523 5.847L0 24l6.32-1.507A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.919 0-3.718-.499-5.28-1.373l-.378-.221-3.751.894.924-3.659-.244-.388A9.951 9.951 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
      Send Booking Request
    </button>
    <p class="al-modal-note">Your info is only used to process your booking.</p>
  </div>
</div>

<!-- SCRIPTS -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
<script>var CaptchaCallback = function() { grecaptcha.render('RecaptchaField_2', {'sitekey':'6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr'}); };</script>

<script>
/* ============================================================
   ALL ORIGINAL JS PRESERVED
   ============================================================ */
var $tst = 0;
function recaptchaCallback() { $('#send_btn').removeAttr('disabled'); $tst = 1; }
$('.decimal').keyup(function() { var val = $(this).val(); if (isNaN(val)) { val = val.replace(/[^0-9\.]/g, ''); if (val.split('.').length > 2) val = val.replace(/\.+$/, ""); } $(this).val(val); });
document.getElementById('id_browse').onchange = function() { $('#file_name').val(this.value); };
$('#uploadform').submit(function() { $('#arrowLoader').show(); return true; });
function open_browse() { var city_ids = $("#tst_city_list").val(); $("#tst_city").val(city_ids); document.getElementById('id_browse').click(); }
<?php if ($payment_success[0] != '') { ?> $("#myModal_payment").modal('show'); <?php } ?>
<?php if ($payment_unsuccess[0] != '') { ?> $("#myModal_payment1").modal('show'); <?php } ?>
var emial_type = 0;
function validmobile() {
  var mobile = document.getElementById("mobile").value;
  if (mobile == '') { $('#error_mobile').html("Please Enter Mobile Number!"); }
  if (checkmobile(mobile) == true) { $('#error_mobile').html(" "); }
  else if (mobile.length != 10) { emial_type = 0; $('#error_mobile').html('Enter Valid Number.'); }
  else { emial_type = 0; $('#error_mobile').html("Invalid Mobile Number."); }
}
function vlidation_btn() {
  var all = [];
  $("#captch_error").html("");
  $('#mobile').each(function() { var mobile = $(this).val(); $('#error_mobile').html(""); if (mobile == '') { $('#error_mobile').html('Please Enter Mobile Number!'); } else if (checkmobile(mobile) == false) { $('#error_mobile').html('Invalid Number'); } else { $('#error_mobile').html(" "); } });
  $('#desc').each(function() { var desc = $(this).val(); $('#error_desc').html(""); if (desc != '') { $('#error_desc').html(" "); } else { all = 1; $('#error_desc').html("Description is required."); } });
  $('#id_browse').each(function() { var id_browse = $(this).val(); $('#error_file').html(""); if (id_browse != '') { $('#error_file').html(" "); } else { all = 1; $('#error_file').html("Please Select File To Upload"); } });
  var mb_no = $("#mobile").val();
  if (checkmobile(mb_no) == false) { all = 1; $('#error_mobile').html('Enter Valid Number.'); }
  if ($tst == 0) { $("#captch_error").html("Required"); return false; }
  if (all != '1') { $("#uploadform").submit(); } else { return false; }
}
function checkemail(mail) { var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; return filter.test(mail); }
function checkmobile(mobile) { var filter = /^[0-9-+]+$/; var pattern = /^\d{10}$/; return filter.test(mobile) && pattern.test(mobile); }
function add_class() {}
function get_inquiry() {
  var get_phone_no = $("#get_inquiry_id").val();
  $("#get_inquiry_id_error").html("");
  if (checkmobile(get_phone_no) == true) {
    $.ajax({ url: "<?php echo base_url(); ?>user_master/save_inquiry", data: {phone: get_phone_no}, type: "POST",
      beforeSend: function() { $("#loader_div").addClass("show-loader"); $("#get_inquiry_id_error_btn").attr("disabled", "disabled"); },
      success: function(data) { $("#get_inquiry_id").val(""); $("#get_inquiry_id_error").html("<span style='color:green;'>Your inquiry successfully sent.</span>"); },
      complete: function() { $("#loader_div").removeClass("show-loader"); $("#get_inquiry_id_error_btn").removeAttr("disabled"); }
    });
  } else { $("#get_inquiry_id_error").html("<span style='color:red;'>Invalid</span>"); }
}
function get_packages(city_fk) { get_packages1(city_fk); }
function get_packages1(city_fk) {
  var d = new Date(); var n = d.getTime();
  $.ajax({
    url: '<?= base_url(); ?>User_master/package_list1?' + n,
    type: 'post', tryCount: 0, cache: false, retryLimit: 3,
    data: {city: city_fk},
    beforeSend: function() { $("#loader_div").addClass("show-loader"); },
    success: function(result1) { $("#searchbar").removeData("test1").empty().html(result1); $("#searchbar").css({opacity:0}).animate({opacity:1}, 400); },
    error: function(xhr, textStatus) { if (textStatus == 'timeout') { this.tryCount++; if (this.tryCount <= this.retryLimit) { $.ajax(this); return; } } },
    complete: function() { setTimeout(function() { $("#loader_div").removeClass("show-loader"); }, 800); }
  });
}
var city_ids = $("#tst_city_list").val();
get_packages(city_ids);
<?php if (isset($payment_success) != NULL) { ?> $('#exampleModal').modal('show'); <?php } ?>

/* BOOKING MODAL */
var _currentPkg = '', _currentPrice = '';
function openBooking(pkg, price) {
  _currentPkg = pkg; _currentPrice = price;
  document.getElementById('alModalPkgDisplay').textContent = pkg;
  document.getElementById('alBookingModal').classList.add('open');
  document.getElementById('alBookName').focus();
  document.body.style.overflow = 'hidden';
}
function closeBooking() { document.getElementById('alBookingModal').classList.remove('open'); document.body.style.overflow = ''; }
document.getElementById('alBookingModal').addEventListener('click', function(e) { if (e.target === this) closeBooking(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeBooking(); });
function sendWhatsApp() {
  var name = document.getElementById('alBookName').value.trim();
  var phone = document.getElementById('alBookPhone').value.trim();
  if (!name) { alert('Please enter your name.'); document.getElementById('alBookName').focus(); return; }
  if (!/^\d{10}$/.test(phone)) { alert('Please enter a valid 10-digit mobile number.'); document.getElementById('alBookPhone').focus(); return; }
  var msg = encodeURIComponent('Hello Airmed Patholab! 🙏\n\nI want to book a test.\n\n📋 *Test/Package:* ' + _currentPkg + '\n👤 *Name:* ' + name + '\n📞 *Mobile:* ' + phone + '\n\nPlease confirm my booking. Thank you!');
  window.open('https://wa.me/919725504245?text=' + msg, '_blank', 'noopener');
  closeBooking();
}

/* FAQ ACCORDION */
function toggleFaq(btn) {
  var item = btn.closest('.al-faq-item');
  var isOpen = item.classList.contains('open');
  document.querySelectorAll('.al-faq-item.open').forEach(function(el) { el.classList.remove('open'); el.querySelector('.al-faq-q').setAttribute('aria-expanded','false'); });
  if (!isOpen) { item.classList.add('open'); btn.setAttribute('aria-expanded','true'); }
}

/* SCROLL REVEAL */
(function() {
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
  }, {threshold: 0.1, rootMargin: '0px 0px -40px 0px'});
  document.querySelectorAll('.sr').forEach(function(el) { observer.observe(el); });
})();

/* Auto-close WA popup */
setTimeout(function() { var p = document.getElementById('alWaPopup'); if (p) p.style.display = 'none'; }, 8000);
</script>