<?php
/**
 * Airmed Pathlabs — Package Details Page (Redesigned)
 * Matches the new live design at airmedlabs.com/user_master/package_details/334
 * Drop-in replacement for the old package_details view file.
 * Requires: $package, $package_array, $a_price, $d_price, $pid, $success
 */
?>

<!-- =====================  PAGE-SCOPED STYLES  ===================== -->
<style>
/* ---------- Reset & base ---------- */
.apd-wrap *,
.apd-wrap *::before,
.apd-wrap *::after { box-sizing: border-box; }

.apd-wrap {
    font-family: 'DM Sans', 'Plus Jakarta Sans', Arial, sans-serif;
    color: #1a1a2e;
    background: #f8f9fb;
}

/* ---------- Breadcrumb ---------- */
.apd-breadcrumb {
    background: #fff;
    padding: 10px 0;
    border-bottom: 1px solid #e8ecf0;
    font-size: 13px;
    color: #6b7280;
}
.apd-breadcrumb a { color: #c0392b; text-decoration: none; }
.apd-breadcrumb a:hover { text-decoration: underline; }
.apd-breadcrumb span { margin: 0 6px; }

/* ---------- Hero Section ---------- */
.apd-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #c0392b 100%);
    padding: 40px 0 0;
    position: relative;
    overflow: hidden;
}
.apd-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
}

.apd-hero-inner {
    display: flex;
    align-items: flex-end;
    gap: 30px;
    flex-wrap: wrap;
}

.apd-hero-info { flex: 1 1 340px; padding-bottom: 30px; }

.apd-badge-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}
.apd-badge {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.3px;
}
.apd-badge.green { background: rgba(16,185,129,0.25); border-color: rgba(16,185,129,0.4); }

.apd-hero h1 {
    font-family: 'DM Serif Display', 'Playfair Display', Georgia, serif;
    font-size: clamp(28px, 5vw, 42px);
    color: #fff;
    margin: 0 0 14px;
    line-height: 1.15;
}

.apd-price-row {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
}
.apd-price-main {
    font-size: 36px;
    font-weight: 800;
    color: #fff;
}
.apd-price-original {
    font-size: 18px;
    color: rgba(255,255,255,0.55);
    text-decoration: line-through;
}

.apd-trust-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 28px;
}
.apd-trust-pill {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.12);
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 12px;
    color: #fff;
    font-weight: 500;
}
.apd-trust-pill svg { flex-shrink: 0; }

.apd-hero-cta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.apd-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #c0392b;
    font-weight: 700;
    font-size: 15px;
    padding: 13px 28px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: transform 0.15s, box-shadow 0.15s;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
.apd-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); color: #c0392b; }

.apd-btn-whatsapp {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #25D366;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    padding: 13px 24px;
    border-radius: 8px;
    text-decoration: none;
    transition: transform 0.15s, filter 0.15s;
}
.apd-btn-whatsapp:hover { transform: translateY(-2px); filter: brightness(1.08); color: #fff; }

/* Hero image */
.apd-hero-img {
    flex: 0 0 auto;
    width: clamp(200px, 35%, 380px);
    align-self: flex-end;
}
.apd-hero-img img {
    width: 100%;
    display: block;
    border-radius: 12px 12px 0 0;
    object-fit: cover;
    max-height: 320px;
}

/* ---------- Trust strip ---------- */
.apd-trust-strip {
    background: #1a1a2e;
    padding: 14px 0;
}
.apd-trust-strip-inner {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px 24px;
}
.apd-ts-item {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #e2e8f0;
    font-size: 13px;
    font-weight: 500;
}
.apd-ts-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #c0392b;
    flex-shrink: 0;
}

/* ---------- Main layout ---------- */
.apd-main { padding: 40px 0 60px; }
.apd-layout {
    display: flex;
    gap: 28px;
    align-items: flex-start;
}
.apd-content { flex: 1 1 0; min-width: 0; }
.apd-sidebar { flex: 0 0 300px; position: sticky; top: 20px; }

/* ---------- Section cards ---------- */
.apd-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.07);
    margin-bottom: 22px;
    overflow: hidden;
}
.apd-card-header {
    padding: 18px 22px 16px;
    border-bottom: 2px solid #f3f4f6;
}
.apd-card-header h2 {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    color: #1a1a2e;
}
.apd-card-body { padding: 20px 22px; }

/* Tests included */
.apd-tests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    margin: 0;
    padding: 0;
    list-style: none;
}
.apd-tests-grid li {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #374151;
    padding: 8px 10px;
    background: #f8f9fb;
    border-radius: 7px;
    border-left: 3px solid #c0392b;
}
.apd-tests-grid li::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #c0392b;
    flex-shrink: 0;
}

/* Package desc (existing HTML from DB) */
.apd-pkg-desc { font-size: 14px; line-height: 1.8; color: #4b5563; }
.apd-pkg-desc h1,
.apd-pkg-desc h2,
.apd-pkg-desc h3 { font-size: 15px; font-weight: 700; color: #c0392b; margin: 16px 0 6px; }
.apd-pkg-desc ul { padding-left: 18px; }
.apd-pkg-desc li { margin-bottom: 4px; }

/* How it works */
.apd-steps { display: flex; flex-direction: column; gap: 0; }
.apd-step {
    display: flex;
    gap: 16px;
    padding: 14px 0;
    border-bottom: 1px solid #f3f4f6;
}
.apd-step:last-child { border-bottom: none; }
.apd-step-num {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #c0392b;
    color: #fff;
    font-weight: 800;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.apd-step-text h3 { font-size: 14px; font-weight: 700; margin: 0 0 3px; }
.apd-step-text p { font-size: 13px; color: #6b7280; margin: 0; }

/* Why choose */
.apd-why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
}
.apd-why-item {
    background: #f8f9fb;
    border-radius: 10px;
    padding: 16px;
    border-top: 3px solid #c0392b;
}
.apd-why-item strong { display: block; font-size: 14px; color: #1a1a2e; margin-bottom: 5px; }
.apd-why-item p { font-size: 13px; color: #6b7280; margin: 0; line-height: 1.6; }

/* FAQ */
.apd-faq-item {
    border-bottom: 1px solid #f3f4f6;
}
.apd-faq-item:last-child { border-bottom: none; }
.apd-faq-q {
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    padding: 16px 0;
    font-size: 14px;
    font-weight: 600;
    color: #1a1a2e;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.apd-faq-q svg { flex-shrink: 0; transition: transform 0.25s; }
.apd-faq-q.open svg { transform: rotate(180deg); }
.apd-faq-a {
    font-size: 13px;
    color: #4b5563;
    line-height: 1.75;
    padding-bottom: 14px;
    display: none;
}
.apd-faq-a.open { display: block; }

/* ---------- Sidebar ---------- */
.apd-sidebar-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.09);
    padding: 22px;
    margin-bottom: 20px;
    border-top: 4px solid #c0392b;
}
.apd-sidebar-price {
    font-size: 32px;
    font-weight: 800;
    color: #c0392b;
    margin-bottom: 4px;
}
.apd-sidebar-name {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f3f4f6;
}
.apd-sidebar-features {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}
.apd-sidebar-features li {
    font-size: 13px;
    color: #374151;
    padding: 7px 0;
    border-bottom: 1px solid #f9fafb;
    display: flex;
    gap: 8px;
    align-items: flex-start;
}
.apd-sidebar-features li::before {
    content: '✓';
    color: #10b981;
    font-weight: 700;
    flex-shrink: 0;
}
.apd-sidebar-book {
    display: block;
    width: 100%;
    background: #c0392b;
    color: #fff;
    text-align: center;
    font-weight: 700;
    font-size: 15px;
    padding: 14px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    margin-bottom: 10px;
    transition: background 0.15s;
}
.apd-sidebar-book:hover { background: #a93226; color: #fff; }
.apd-sidebar-wa {
    display: block;
    width: 100%;
    background: #f0fdf4;
    color: #059669;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #bbf7d0;
    text-decoration: none;
    margin-bottom: 10px;
    transition: background 0.15s;
}
.apd-sidebar-wa:hover { background: #dcfce7; color: #059669; }
.apd-sidebar-call {
    display: block;
    text-align: center;
    font-size: 14px;
    color: #1a1a2e;
    text-decoration: none;
    font-weight: 500;
}
.apd-sidebar-call span { color: #c0392b; font-weight: 700; }

/* Sidebar info table */
.apd-info-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.apd-info-table tr td { padding: 9px 0; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
.apd-info-table tr:last-child td { border-bottom: none; }
.apd-info-table td:first-child { color: #6b7280; width: 46%; }
.apd-info-table td:last-child { color: #1a1a2e; font-weight: 600; }

/* Accreditations */
.apd-accred {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.07);
    padding: 16px;
    margin-bottom: 20px;
}
.apd-accred h3 { font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 14px; }
.apd-accred-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.apd-accred-item {
    background: #f8f9fb;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
}
.apd-accred-item strong { display: block; font-size: 12px; color: #c0392b; font-weight: 700; margin-bottom: 3px; }
.apd-accred-item span { font-size: 11px; color: #6b7280; line-height: 1.4; }

/* ---------- Similar packages ---------- */
.apd-similar { margin-top: 10px; }
.apd-similar h2 { font-size: 22px; font-weight: 800; margin-bottom: 20px; }
.apd-similar h2 span { color: #c0392b; }
.apd-packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}
.apd-pkg-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.08);
    text-decoration: none;
    color: inherit;
    transition: transform 0.18s, box-shadow 0.18s;
    display: flex;
    flex-direction: column;
    position: relative;
}
.apd-pkg-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
.apd-pkg-price-tag {
    position: absolute;
    top: 10px; right: 10px;
    background: #c0392b;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
}
.apd-pkg-card-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}
.apd-pkg-card-body {
    padding: 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.apd-pkg-card-body h3 { font-size: 15px; font-weight: 600; margin: 0 0 12px; color: #1a1a2e; }
.apd-pkg-book-btn {
    display: inline-block;
    background: #c0392b;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 6px;
    text-align: center;
    text-decoration: none;
    transition: background 0.15s;
}
.apd-pkg-book-btn:hover { background: #a93226; color: #fff; }

.apd-view-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 2px solid #c0392b;
    color: #c0392b;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 24px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
}
.apd-view-all:hover { background: #c0392b; color: #fff; }

/* ---------- App download strip ---------- */
.apd-app-strip {
    background: linear-gradient(135deg, #1a1a2e 0%, #2d1b69 100%);
    border-radius: 12px;
    padding: 30px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin: 30px 0 10px;
}
.apd-app-strip h2 { font-size: 20px; font-weight: 700; color: #fff; margin: 0 0 6px; }
.apd-app-strip p { font-size: 13px; color: rgba(255,255,255,0.7); margin: 0; }
.apd-app-btns { display: flex; gap: 12px; flex-wrap: wrap; }
.apd-app-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.15s;
}
.apd-app-btn:hover { background: rgba(255,255,255,0.22); color: #fff; }

/* ---------- Success Modal ---------- */
#apd-modal-payment {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
#apd-modal-payment.show { display: flex; }
.apd-modal-box {
    background: #fff;
    border-radius: 14px;
    padding: 32px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.apd-modal-box h4 { font-size: 18px; font-weight: 700; margin: 16px 0 0; color: #1a1a2e; }
.apd-modal-box img { width: 80px; }

/* ---------- Container utility ---------- */
.apd-container {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ---------- Mobile ---------- */
@media (max-width: 900px) {
    .apd-layout { flex-direction: column; }
    .apd-sidebar { position: static; flex: none; width: 100%; }
    .apd-hero-img { display: none; }
}
@media (max-width: 600px) {
    .apd-hero { padding: 28px 0 0; }
    .apd-hero h1 { font-size: 26px; }
    .apd-price-main { font-size: 28px; }
    .apd-app-strip { flex-direction: column; align-items: flex-start; }
}
</style>

<!-- =====================  PAGE HTML  ===================== -->

<?php
// Defensive defaults
$pkg        = $package[0] ?? [];
$pkg_title  = $pkg['title']  ?? 'Package';
$pkg_id     = $pkg['id']     ?? '';
$pkg_image  = $pkg['back_image'] ?? '';
$pkg_desc   = $pkg['desc_web']   ?? '';
$actual_price   = $a_price ?? '';
$discount_price = $d_price ?? '';
$success_msg    = $success[0] ?? '';

// WhatsApp number — update to your actual number
$wa_number = '919725504245';
$wa_msg    = urlencode("Hi, I want to book {$pkg_title} ₹{$discount_price}");
?>

<div class="apd-wrap">

<!-- -------- Success modal -------- -->
<div id="apd-modal-payment">
    <div class="apd-modal-box">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTV2P4sfl7-R0VRshYncNf7dJvh-FzqpokC4jaUBP4C6afbB9r" alt="success">
        <h4><?= htmlspecialchars($success_msg) ?></h4>
    </div>
</div>

<!-- -------- Breadcrumb -------- -->
<div class="apd-breadcrumb">
    <div class="apd-container">
        <a href="<?= base_url() ?>">Home</a>
        <span>›</span>
        <a href="<?= base_url() ?>user_master/all_packages">All Packages</a>
        <span>›</span>
        <?= htmlspecialchars($pkg_title) ?>
    </div>
</div>

<!-- -------- Hero -------- -->
<div class="apd-hero">
    <div class="apd-container">
        <div class="apd-hero-inner">
            <div class="apd-hero-info">
                <div class="apd-badge-row">
                    <span class="apd-badge green">Home Collection</span>
                    <span class="apd-badge">NABL Certified</span>
                </div>

                <h1><?= htmlspecialchars($pkg_title) ?></h1>

                <div class="apd-price-row">
                    <span class="apd-price-main">₹<?= htmlspecialchars($discount_price) ?></span>
                    <?php if ($actual_price && $actual_price != $discount_price): ?>
                    <span class="apd-price-original">₹<?= htmlspecialchars($actual_price) ?></span>
                    <?php endif; ?>
                </div>

                <div class="apd-trust-pills">
                    <div class="apd-trust-pill">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Best Price
                    </div>
                    <div class="apd-trust-pill">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                        Reports in 24 Hours
                    </div>
                    <div class="apd-trust-pill">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                        Ahmedabad-wide Collection
                    </div>
                    <div class="apd-trust-pill">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Reports on App &amp; WhatsApp
                    </div>
                </div>

                <div class="apd-hero-cta">
                    <a href="javascript:void(0);" onclick="apdBookNow();" class="apd-btn-primary">
                        Book Now – ₹<?= htmlspecialchars($discount_price) ?>
                    </a>
                    <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_msg ?>" target="_blank" rel="noopener" class="apd-btn-whatsapp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <?php if ($pkg_image): ?>
            <div class="apd-hero-img">
                <img src="<?= base_url() ?>upload/package/<?= htmlspecialchars($pkg_image) ?>"
                     alt="<?= htmlspecialchars($pkg_title) ?> blood test package Ahmedabad">
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- -------- Trust strip -------- -->
<div class="apd-trust-strip">
    <div class="apd-container">
        <div class="apd-trust-strip-inner">
            <div class="apd-ts-item"><span class="apd-ts-dot"></span>NABL Accredited Lab</div>
            <div class="apd-ts-item"><span class="apd-ts-dot"></span>50,000+ Patients</div>
            <div class="apd-ts-item"><span class="apd-ts-dot"></span>Home Collection</div>
            <div class="apd-ts-item"><span class="apd-ts-dot"></span>ICMR Approved</div>
            <div class="apd-ts-item"><span class="apd-ts-dot"></span>24-hr Reports</div>
        </div>
    </div>
</div>

<!-- -------- Main content + sidebar -------- -->
<div class="apd-main">
    <div class="apd-container">
        <div class="apd-layout">

            <!-- ===== CONTENT ===== -->
            <div class="apd-content">

                <!-- Tests Included (if desc_web has structured content, it renders inside apd-pkg-desc) -->
                <?php if (trim($pkg_desc)): ?>
                <div class="apd-card">
                    <div class="apd-card-header">
                        <h2>Tests Included</h2>
                    </div>
                    <div class="apd-card-body">
                        <div class="apd-pkg-desc">
                            <?= $pkg_desc ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- How It Works -->
                <div class="apd-card">
                    <div class="apd-card-header">
                        <h2>How It Works</h2>
                    </div>
                    <div class="apd-card-body">
                        <div class="apd-steps">
                            <div class="apd-step">
                                <div class="apd-step-num">1</div>
                                <div class="apd-step-text">
                                    <h3>Book Online</h3>
                                    <p>Click Book Now or WhatsApp us to schedule your test</p>
                                </div>
                            </div>
                            <div class="apd-step">
                                <div class="apd-step-num">2</div>
                                <div class="apd-step-text">
                                    <h3>Home Collection</h3>
                                    <p>Our trained phlebotomist visits your home at the chosen slot</p>
                                </div>
                            </div>
                            <div class="apd-step">
                                <div class="apd-step-num">3</div>
                                <div class="apd-step-text">
                                    <h3>Sample Processing</h3>
                                    <p>Sample processed in our NABL-accredited lab with barcode tracking</p>
                                </div>
                            </div>
                            <div class="apd-step">
                                <div class="apd-step-num">4</div>
                                <div class="apd-step-text">
                                    <h3>Get Reports</h3>
                                    <p>Reports delivered to your app, WhatsApp &amp; email within 24 hours</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Why Choose -->
                <div class="apd-card">
                    <div class="apd-card-header">
                        <h2>Why Choose Airmed Pathlabs?</h2>
                    </div>
                    <div class="apd-card-body">
                        <div class="apd-why-grid">
                            <div class="apd-why-item">
                                <strong>NABL Accredited</strong>
                                <!--<p>ISO 15189:2012 certified lab with highest quality standards</p>-->
                            </div>
                            <div class="apd-why-item">
                                <strong>Home Collection</strong>
                                <p>Doorstep blood collection across all areas of Ahmedabad</p>
                            </div>
                            <div class="apd-why-item">
                                <strong>Digital Reports</strong>
                                <p>View and share reports via WhatsApp, and email instantly</p>
                            </div>
                            <div class="apd-why-item">
                                <strong>Barcode Tracking</strong>
                                <p>Every sample is barcode-tagged to prevent mix-ups</p>
                            </div>
                            <div class="apd-why-item">
                                <strong>Affordable Pricing</strong>
                                <p>Best-in-class prices with no hidden charges</p>
                            </div>
                            <div class="apd-why-item">
                                <strong>Expert Pathologists</strong>
                                <p>Qualified and experienced doctors verify every report</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="apd-card">
                    <div class="apd-card-header">
                        <h2>Frequently Asked Questions</h2>
                    </div>
                    <div class="apd-card-body">
                        <div class="apd-faq-item">
                            <button class="apd-faq-q" onclick="apdToggleFaq(this)">
                                What is <?= htmlspecialchars($pkg_title) ?> blood test?
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="apd-faq-a">
                                <?= htmlspecialchars($pkg_title) ?> is a comprehensive diagnostic blood test package offered by Airmed Pathlabs in Ahmedabad at ₹<?= htmlspecialchars($discount_price) ?>. It covers multiple health parameters to give a detailed picture of your overall health and detect any underlying issues early.
                            </div>
                        </div>
                        <div class="apd-faq-item">
                            <button class="apd-faq-q" onclick="apdToggleFaq(this)">
                                Is home sample collection available for this package?
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="apd-faq-a">
                                Yes, Airmed Pathlabs provides home blood sample collection across all major areas of Ahmedabad. Our trained phlebotomist will visit your home at your preferred time slot. Simply book by calling +91 8101-161616 or WhatsApp at +91 97255-04245.
                            </div>
                        </div>
                        <div class="apd-faq-item">
                            <button class="apd-faq-q" onclick="apdToggleFaq(this)">
                                How long does it take to receive the report?
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="apd-faq-a">
                                Reports are typically delivered within 24 hours of sample collection. You will receive the report digitally via the Airmed mobile app, WhatsApp, and email — so you can access and share it easily.
                            </div>
                        </div>
                        <div class="apd-faq-item">
                            <button class="apd-faq-q" onclick="apdToggleFaq(this)">
                                Do I need to fast before the test?
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="apd-faq-a">
                                Fasting requirements vary based on the specific tests included in the package. Our team will confirm fasting instructions when you book. For packages including blood glucose or lipid profile tests, 8–12 hours of fasting is typically recommended.
                            </div>
                        </div>
                        <div class="apd-faq-item">
                            <button class="apd-faq-q" onclick="apdToggleFaq(this)">
                                Is Airmed Pathlabs NABL accredited?
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="apd-faq-a">
                                Yes, Airmed Pathlabs is a NABL accredited diagnostic laboratory following ISO 15189 standards, ensuring highest quality and accuracy in all test results.
                            </div>
                        </div>
                        <div class="apd-faq-item">
                            <button class="apd-faq-q" onclick="apdToggleFaq(this)">
                                How do I book the <?= htmlspecialchars($pkg_title) ?> package?
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="apd-faq-a">
                                You can book by: (1) clicking "Book Now" on this page, (2) sending a WhatsApp message to +91 97255-04245, or (3) calling +91 8101-161616. Home collection is available across Ahmedabad with flexible time slots.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- App download strip -->
                <!--<div class="apd-app-strip">
                    <div>
                        <h2>Download the Airmed App</h2>
                        <p>Book tests, view reports, track health history — all in one place. Get <?= $this->cash_back[0]["caseback_per"] ?? '20' ?>% cashback on your first order.</p>
                    </div>
                    <div class="apd-app-btns">
                        <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank" rel="noopener" class="apd-app-btn">
                            ▶ Google Play
                        </a>
                        <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank" rel="noopener" class="apd-app-btn">
                             App Store
                        </a>
                    </div>
                </div>-->

            </div><!-- /.apd-content -->

            <!-- ===== SIDEBAR ===== -->
            <div class="apd-sidebar">

                <!-- Booking card -->
                <div class="apd-sidebar-card">
                    <div class="apd-sidebar-price">₹<?= htmlspecialchars($discount_price) ?></div>
                    <div class="apd-sidebar-name"><?= htmlspecialchars($pkg_title) ?></div>

                    <ul class="apd-sidebar-features">
                        <li>Home sample collection included</li>
                        <li>Reports delivered in 24 hours</li>
                        <li>Digital report on WhatsApp &amp; email</li>
                        <li>NABL accredited lab testing</li>
                        <li>Barcode sample tracking</li>
                    </ul>

                    <a href="javascript:void(0);" onclick="apdBookNow();" class="apd-sidebar-book">
                        Book This Package
                    </a>
                    <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_msg ?>" target="_blank" rel="noopener" class="apd-sidebar-wa">
                        📲 Book via WhatsApp
                    </a>
                    <a href="tel:+918101161616" class="apd-sidebar-call">
                        📞 <span>+91 8101-161616</span>
                    </a>
                </div>

                <!-- Package info -->
                <div class="apd-sidebar-card" style="border-top-color:#1a1a2e;">
                    <h3 style="font-size:13px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 14px;">Package Information</h3>
                    <table class="apd-info-table">
                        <tr>
                            <td>Report Time</td>
                            <td>Within 24 hours of collection</td>
                        </tr>
                        <tr>
                            <td>Sample Collection</td>
                            <td>Home collection available citywide</td>
                        </tr>
                        <tr>
                            <td>Fasting Required</td>
                            <td>8–12 hrs (confirmed at booking)</td>
                        </tr>
                        <tr>
                            <td>Available In</td>
                            <td>Ahmedabad, Gujarat</td>
                        </tr>
                    </table>
                </div>

                <!-- Accreditations -->
                <div class="apd-accred">
                    <h3>Our Accreditations</h3>
                    <div class="apd-accred-grid">
                        <div class="apd-accred-item">
                            <strong>NABL Accredited</strong>
                            <!--<span>ISO 15189 certified diagnostic lab</span>-->
                        </div>
                        <div class="apd-accred-item">
                            <strong>ICMR Approved</strong>
                            <span>Govt. approved testing protocols</span>
                        </div>
                        <div class="apd-accred-item">
                            <strong>Barcode Tracked</strong>
                            <span>Every sample uniquely identified</span>
                        </div>
                        <div class="apd-accred-item">
                            <strong>Digital Reports</strong>
                            <span>WhatsApp &amp; email delivery</span>
                            <!--<span>App, WhatsApp &amp; email delivery</span>-->
                        </div>
                    </div>
                </div>

            </div><!-- /.apd-sidebar -->

        </div><!-- /.apd-layout -->

        <!-- -------- Similar Packages -------- -->
        <?php if (!empty($package_array)): ?>
        <div class="apd-similar">
            <h2>Similar <span>Packages</span></h2>
            <div class="apd-packages-grid">
                <?php foreach ($package_array as $key1): ?>
                <?php
                    $sp = $key1[0] ?? [];
                    $sp_price = $key1[1][0]['d_price'] ?? '';
                ?>
                <a href="<?= base_url() ?>user_master/package_details/<?= htmlspecialchars($sp['id']) ?>" class="apd-pkg-card">
                    <?php if ($sp_price): ?>
                    <span class="apd-pkg-price-tag">₹<?= htmlspecialchars($sp_price) ?></span>
                    <?php endif; ?>
                    <img class="apd-pkg-card-img"
                         src="<?= base_url() ?>upload/package/<?= htmlspecialchars($sp['image'] ?? '') ?>"
                         alt="<?= htmlspecialchars($sp['title'] ?? '') ?> blood test Ahmedabad"
                         onerror="this.style.background='#f3f4f6';this.style.visibility='hidden'">
                    <div class="apd-pkg-card-body">
                        <h3><?= htmlspecialchars($sp['title'] ?? '') ?></h3>
                        <span class="apd-pkg-book-btn">Book Now</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <a href="<?= base_url() ?>User_master/all_packages" class="apd-view-all">
                View All Packages →
            </a>
        </div>
        <?php endif; ?>

    </div><!-- /.apd-container -->
</div><!-- /.apd-main -->

</div><!-- /.apd-wrap -->

<!-- =====================  HIDDEN FIELDS + JS  ===================== -->
<input type="hidden" id="package_name_id" value="<?= htmlspecialchars($pkg_title) ?>" />
<input type="hidden" id="package_id_id"   value="<?= htmlspecialchars($pkg_id) ?>" />

<script>
/* ---- Book Now (existing AJAX logic preserved) ---- */
function apdBookNow() {
    var ids   = ['p-' + document.getElementById('package_id_id').value];
    var names = [document.getElementById('package_name_id').value];

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= base_url() ?>user_master/searching_test', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        window.location = '<?= base_url() ?>user_master/order_search';
    };
    xhr.send('id[]=' + encodeURIComponent(ids[0]) + '&name[]=' + encodeURIComponent(names[0]));
}

/* ---- FAQ accordion ---- */
function apdToggleFaq(btn) {
    var answer = btn.nextElementSibling;
    var isOpen = answer.classList.contains('open');

    // Close all
    document.querySelectorAll('.apd-faq-a').forEach(function(a){ a.classList.remove('open'); });
    document.querySelectorAll('.apd-faq-q').forEach(function(q){ q.classList.remove('open'); });

    if (!isOpen) {
        answer.classList.add('open');
        btn.classList.add('open');
    }
}

/* ---- Success modal ---- */
<?php if (!empty($success_msg)): ?>
(function() {
    var modal = document.getElementById('apd-modal-payment');
    setTimeout(function(){ modal.classList.add('show'); }, 3000);
    setTimeout(function(){ modal.classList.remove('show'); }, 8000);
})();
<?php endif; ?>
</script>