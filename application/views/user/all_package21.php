<?php
/**
 * Airmed Pathlabs — All Packages Page (Redesigned)
 * Matches the new live design at airmedlabs.com/user_master/all_packages
 *
 * Expected controller variables:
 *   $package_array  — array of packages, each: [ [id, title, image, category_slug], [{ d_price }], [test_tags array] ]
 *   $suggest_package — array of category groups with nested packages & test_list
 *
 * Category slugs used for JS filter tabs (set in your controller or derive from category name):
 *   full-body | senior | habits | risk | specialty
 */

// WhatsApp number — update if needed
$wa_number = '919725504245';
?>
<!-- =====================  PAGE-SCOPED STYLES  ===================== -->
<style>
/* ---------- Reset & base ---------- */
.aap-wrap *, .aap-wrap *::before, .aap-wrap *::after { box-sizing: border-box; }
.aap-wrap {
    font-family: 'DM Sans', 'Plus Jakarta Sans', Arial, sans-serif;
    color: #1a1a2e;
    background: #f8f9fb;
}

/* ---------- Breadcrumb ---------- */
.aap-breadcrumb {
    background: #fff;
    padding: 10px 0;
    border-bottom: 1px solid #e8ecf0;
    font-size: 13px;
    color: #6b7280;
}
.aap-breadcrumb a { color: #c0392b; text-decoration: none; }
.aap-breadcrumb a:hover { text-decoration: underline; }
.aap-breadcrumb span { margin: 0 6px; }

/* ---------- Hero ---------- */
.aap-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #c0392b 100%);
    padding: 44px 0 36px;
    position: relative;
    overflow: hidden;
}
.aap-hero::before {
    content: '';
    position: absolute;
    top: -100px; right: -100px;
    width: 380px; height: 380px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
}
.aap-hero h1 {
    font-family: 'DM Serif Display', 'Playfair Display', Georgia, serif;
    font-size: clamp(26px, 4.5vw, 40px);
    color: #fff;
    margin: 0 0 10px;
    line-height: 1.2;
}
.aap-hero p {
    font-size: 15px;
    color: rgba(255,255,255,0.78);
    margin: 0 0 24px;
    max-width: 580px;
}
.aap-hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 28px;
}
.aap-hero-badge {
    background: rgba(255,255,255,0.13);
    border: 1px solid rgba(255,255,255,0.22);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 20px;
}
.aap-hero-badge.green { background: rgba(16,185,129,0.22); border-color: rgba(16,185,129,0.35); }
.aap-hero-badge.gold  { background: rgba(251,191,36,0.18); border-color: rgba(251,191,36,0.3); }

/* ---------- Trust strip ---------- */
.aap-trust-strip {
    background: #1a1a2e;
    padding: 13px 0;
}
.aap-ts-inner {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px 28px;
}
.aap-ts-item {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #e2e8f0;
    font-size: 13px;
    font-weight: 500;
}
.aap-ts-dot { width: 8px; height: 8px; border-radius: 50%; background: #c0392b; flex-shrink: 0; }

/* ---------- Filter tabs ---------- */
.aap-filter-bar {
    background: #fff;
    border-bottom: 2px solid #f0f0f0;
    padding: 0;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.aap-filter-inner {
    display: flex;
    gap: 0;
    overflow-x: auto;
    scrollbar-width: none;
}
.aap-filter-inner::-webkit-scrollbar { display: none; }
.aap-filter-btn {
    flex: 0 0 auto;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 14px 22px;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    white-space: nowrap;
    transition: color 0.15s, border-color 0.15s;
}
.aap-filter-btn:hover { color: #c0392b; }
.aap-filter-btn.active { color: #c0392b; border-bottom-color: #c0392b; }

/* ---------- Main layout ---------- */
.aap-main { padding: 36px 0 60px; }
.aap-layout { display: flex; gap: 28px; align-items: flex-start; }
.aap-content { flex: 1 1 0; min-width: 0; }
.aap-sidebar { flex: 0 0 290px; position: sticky; top: 56px; }

/* ---------- Category section ---------- */
.aap-category-section { margin-bottom: 36px; }
.aap-category-section[data-cat]:not(.visible) { display: none; }
.aap-cat-heading {
    font-size: 16px;
    font-weight: 800;
    color: #1a1a2e;
    margin: 0 0 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 10px;
}
.aap-cat-count {
    background: #f3f4f6;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: 20px;
}

/* ---------- Package cards grid ---------- */
.aap-pkg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 18px;
}
.aap-pkg-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform 0.18s, box-shadow 0.18s;
}
.aap-pkg-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }

.aap-pkg-price-tag {
    position: absolute;
    top: 10px; right: 10px;
    background: #c0392b;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 20px;
    z-index: 2;
}
.aap-pkg-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
    background: #f3f4f6;
}
.aap-pkg-body { padding: 14px; flex: 1; display: flex; flex-direction: column; }
.aap-pkg-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 10px;
    line-height: 1.3;
}
.aap-pkg-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 14px;
}
.aap-pkg-tag {
    background: #f3f4f6;
    color: #4b5563;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 5px;
    border: 1px solid #e5e7eb;
}
.aap-pkg-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
}
.aap-pkg-book {
    flex: 1;
    background: #c0392b;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 9px 12px;
    border-radius: 7px;
    text-align: center;
    text-decoration: none;
    transition: background 0.15s;
}
.aap-pkg-book:hover { background: #a93226; color: #fff; }
.aap-pkg-wa {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #059669;
    font-size: 18px;
    width: 38px;
    height: 38px;
    border-radius: 7px;
    text-decoration: none;
    flex-shrink: 0;
    transition: background 0.15s;
}
.aap-pkg-wa:hover { background: #dcfce7; }

/* ---------- Sidebar ---------- */
.aap-sb-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    overflow: hidden;
}
.aap-sb-header {
    background: #1a1a2e;
    color: #fff;
    padding: 14px 18px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.aap-sb-body { padding: 0; }

/* Accordion category list */
.aap-sb-cat { border-bottom: 1px solid #f3f4f6; }
.aap-sb-cat:last-child { border-bottom: none; }
.aap-sb-cat-btn {
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    padding: 13px 18px;
    font-size: 13px;
    font-weight: 700;
    color: #1a1a2e;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}
.aap-sb-cat-btn svg { flex-shrink: 0; transition: transform 0.22s; color: #c0392b; }
.aap-sb-cat-btn.open svg { transform: rotate(180deg); }
.aap-sb-cat-body { display: none; padding: 0 0 8px; }
.aap-sb-cat-body.open { display: block; }
.aap-sb-pkg-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 9px 18px;
    border-bottom: 1px solid #f9fafb;
    gap: 8px;
}
.aap-sb-pkg-item:last-child { border-bottom: none; }
.aap-sb-pkg-name { font-size: 12.5px; color: #374151; line-height: 1.4; flex: 1; }
.aap-sb-pkg-name a { color: #374151; text-decoration: none; }
.aap-sb-pkg-name a:hover { color: #c0392b; }
.aap-sb-pkg-price { font-size: 12px; font-weight: 700; color: #c0392b; white-space: nowrap; }
.aap-sb-pkg-tests { padding: 0 18px 8px; }
.aap-sb-pkg-tests ul { padding-left: 14px; margin: 4px 0 8px; }
.aap-sb-pkg-tests ul li { font-size: 11.5px; color: #6b7280; margin-bottom: 3px; }
.aap-sb-book-link {
    display: block;
    text-align: center;
    background: #c0392b;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 7px 16px;
    border-radius: 6px;
    text-decoration: none;
    margin: 0 18px 10px;
}
.aap-sb-book-link:hover { background: #a93226; color: #fff; }

/* Book CTA card */
.aap-sb-cta {
    background: linear-gradient(135deg, #c0392b, #8b0000);
    border-radius: 12px;
    padding: 22px 18px;
    color: #fff;
    text-align: center;
    margin-bottom: 20px;
}
.aap-sb-cta h3 { font-size: 15px; font-weight: 700; margin: 0 0 6px; }
.aap-sb-cta p { font-size: 12px; opacity: 0.85; margin: 0 0 16px; line-height: 1.5; }
.aap-sb-cta-wa {
    display: block;
    background: #25D366;
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    padding: 10px;
    border-radius: 7px;
    text-decoration: none;
    margin-bottom: 8px;
}
.aap-sb-cta-call {
    display: block;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 9px;
    border-radius: 7px;
    text-decoration: none;
}

/* Accreditations */
.aap-accred-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 16px 18px; }
.aap-accred-item { background: #f8f9fb; border-radius: 8px; padding: 12px 10px; text-align: center; }
.aap-accred-item strong { display: block; font-size: 11px; color: #c0392b; font-weight: 700; margin-bottom: 3px; }
.aap-accred-item span { font-size: 10.5px; color: #6b7280; line-height: 1.4; }

/* ---------- FAQ ---------- */
.aap-faq-section { margin-top: 36px; }
.aap-faq-section h2 { font-size: 20px; font-weight: 800; margin-bottom: 18px; }
.aap-faq-section h2 span { color: #c0392b; }
.aap-faq-wrap {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.07);
    overflow: hidden;
}
.aap-faq-item { border-bottom: 1px solid #f3f4f6; }
.aap-faq-item:last-child { border-bottom: none; }
.aap-faq-q {
    width: 100%;
    background: none;
    border: none;
    text-align: left;
    padding: 16px 22px;
    font-size: 14px;
    font-weight: 600;
    color: #1a1a2e;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.aap-faq-q svg { flex-shrink: 0; transition: transform 0.25s; }
.aap-faq-q.open svg { transform: rotate(180deg); }
.aap-faq-a { font-size: 13px; color: #4b5563; line-height: 1.75; padding: 0 22px 16px; display: none; }
.aap-faq-a.open { display: block; }

/* ---------- Search bar ---------- */
.aap-search-wrap {
    position: relative;
    margin-bottom: 22px;
}
.aap-search-input {
    width: 100%;
    padding: 12px 44px 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 9px;
    font-size: 14px;
    color: #1a1a2e;
    outline: none;
    transition: border-color 0.15s;
    background: #fff;
}
.aap-search-input:focus { border-color: #c0392b; }
.aap-search-icon {
    position: absolute;
    right: 14px; top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
}
.aap-no-results {
    display: none;
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
    font-size: 14px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.07);
}

/* ---------- Container ---------- */
.aap-container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }

/* ---------- App download strip ---------- */
.aap-app-strip {
    background: linear-gradient(135deg, #1a1a2e 0%, #2d1b69 100%);
    border-radius: 12px;
    padding: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 32px;
}
.aap-app-strip h2 { font-size: 18px; font-weight: 700; color: #fff; margin: 0 0 5px; }
.aap-app-strip p { font-size: 13px; color: rgba(255,255,255,0.7); margin: 0; }
.aap-app-btns { display: flex; gap: 12px; flex-wrap: wrap; }
.aap-app-btn {
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
.aap-app-btn:hover { background: rgba(255,255,255,0.22); color: #fff; }

/* ---------- Mobile ---------- */
@media (max-width: 900px) {
    .aap-layout { flex-direction: column; }
    .aap-sidebar { position: static; flex: none; width: 100%; }
    .aap-sidebar > .aap-sb-card:first-child { display: none; } /* hide category sidebar on mobile */
}
@media (max-width: 580px) {
    .aap-pkg-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
    .aap-hero h1 { font-size: 24px; }
    .aap-app-strip { flex-direction: column; align-items: flex-start; }
}
</style>

<!-- =====================  PAGE HTML  ===================== -->

<div class="aap-wrap">

<!-- -------- Breadcrumb -------- -->
<div class="aap-breadcrumb">
    <div class="aap-container">
        <a href="<?= base_url() ?>">Home</a>
        <span>›</span>
        All Packages
    </div>
</div>

<!-- -------- Hero -------- -->
<div class="aap-hero">
    <div class="aap-container">
        <h1>Health Checkup Packages in Ahmedabad</h1>
        <p>25+ affordable diagnostic packages with home sample collection. Accurate results. Trusted by thousands across Ahmedabad.</p>
        <div class="aap-hero-badges">
            <span class="aap-hero-badge green">Home Collection</span>
            <span class="aap-hero-badge">NABL Accredited</span>
            <span class="aap-hero-badge">Reports in 24 hrs</span>
            <span class="aap-hero-badge gold">Starting ₹499</span>
        </div>
    </div>
</div>

<!-- -------- Trust strip -------- -->
<div class="aap-trust-strip">
    <div class="aap-container">
        <div class="aap-ts-inner">
            <div class="aap-ts-item"><span class="aap-ts-dot"></span>NABL Accredited Lab</div>
            <div class="aap-ts-item"><span class="aap-ts-dot"></span>50,000+ Patients Served</div>
            <div class="aap-ts-item"><span class="aap-ts-dot"></span>Ahmedabad-wide Collection</div>
            <div class="aap-ts-item"><span class="aap-ts-dot"></span>WhatsApp Booking</div>
            <div class="aap-ts-item"><span class="aap-ts-dot"></span>Reports on App</div>
        </div>
    </div>
</div>

<!-- -------- Filter tabs -------- -->
<div class="aap-filter-bar">
    <div class="aap-container">
        <div class="aap-filter-inner">
            <button class="aap-filter-btn active" onclick="aapFilter('all', this)">All Packages</button>
            <button class="aap-filter-btn" onclick="aapFilter('full-body', this)">Full Body</button>
            <button class="aap-filter-btn" onclick="aapFilter('habits', this)">By Habits</button>
            <button class="aap-filter-btn" onclick="aapFilter('risk', this)">By Risk</button>
            <button class="aap-filter-btn" onclick="aapFilter('senior', this)">Senior Citizen</button>
            <button class="aap-filter-btn" onclick="aapFilter('specialty', this)">Specialty</button>
        </div>
    </div>
</div>

<!-- -------- Main content -------- -->
<div class="aap-main">
    <div class="aap-container">
        <div class="aap-layout">

            <!-- ===== CONTENT ===== -->
            <div class="aap-content">

                <!-- Search -->
                <div class="aap-search-wrap">
                    <input type="text" class="aap-search-input" id="aap-search"
                           placeholder="Search packages e.g. thyroid, diabetes, hair loss..."
                           oninput="aapSearch(this.value)">
                    <svg class="aap-search-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>

                <div id="aap-no-results" class="aap-no-results">
                    No packages found. Try a different search term.
                </div>

                <!-- ===== DYNAMIC CATEGORY SECTIONS from controller ===== -->
                <?php
                /**
                 * $suggest_package structure (from controller):
                 *   [ [ "name" => "Full Body Packages", "cat_slug" => "full-body", "package" => [ [...pkg...] ] ] ]
                 *
                 * If your controller doesn't set cat_slug, map name → slug here:
                 */
                $slug_map = [
                    'full body'      => 'full-body',
                    'senior citizen' => 'senior',
                    'habits'         => 'habits',
                    'risk'           => 'risk',
                    'specialty'      => 'specialty',
                ];

                foreach ($suggest_package as $cat_group):
                    $cat_name = $cat_group['name'] ?? 'Packages';
                    $cat_slug = $cat_group['cat_slug'] ?? '';
                    // auto-derive slug from name if not set
                    if (!$cat_slug) {
                        foreach ($slug_map as $key => $val) {
                            if (stripos($cat_name, $key) !== false) { $cat_slug = $val; break; }
                        }
                        if (!$cat_slug) $cat_slug = strtolower(preg_replace('/[^a-z0-9]/i', '-', $cat_name));
                    }
                    $pkgs = $cat_group['package'] ?? [];
                    $pkg_count = count($pkgs);
                ?>
                <div class="aap-category-section visible" data-cat="<?= htmlspecialchars($cat_slug) ?>">
                    <div class="aap-cat-heading">
                        <?= htmlspecialchars(ucwords($cat_name)) ?>
                        <span class="aap-cat-count"><?= $pkg_count ?></span>
                    </div>
                    <div class="aap-pkg-grid">
                        <?php foreach ($pkgs as $p):
                            $p_id    = $p['id']      ?? '';
                            $p_title = $p['title']   ?? '';
                            $p_image = $p['image']   ?? '';
                            $p_price = $p['d_price'] ?? '';
                            $p_tests = $p['test_list'] ?? [];
                            $wa_msg  = urlencode("Hi, I want to book {$p_title} ₹{$p_price}");
                            // build tag list — first 5 test names, abbreviated
                            $tags = array_slice(array_map(function($t){
                                $name = $t['test_name'] ?? '';
                                return strlen($name) > 18 ? substr($name, 0, 16).'…' : $name;
                            }, $p_tests), 0, 5);
                            $extra = count($p_tests) - 5;
                        ?>
                        <div class="aap-pkg-card" data-title="<?= htmlspecialchars(strtolower($p_title)) ?>" data-cat="<?= htmlspecialchars($cat_slug) ?>">
                            <?php if ($p_price): ?>
                            <span class="aap-pkg-price-tag">₹<?= htmlspecialchars($p_price) ?></span>
                            <?php endif; ?>
                            <img class="aap-pkg-img"
                                 src="<?php echo base_url(); ?>upload/package/<?= $key1[0]["image"] ?>"
                                 alt="<?= htmlspecialchars($p_title) ?> blood test package Ahmedabad"
                                 onerror="this.style.visibility='hidden'">
                            <div class="aap-pkg-body">
                                <h3 class="aap-pkg-title"><?= htmlspecialchars($p_title) ?></h3>
                                <?php if (!empty($tags)): ?>
                                <div class="aap-pkg-tags">
                                    <?php foreach ($tags as $tag): ?>
                                    <span class="aap-pkg-tag"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                    <?php if ($extra > 0): ?>
                                    <span class="aap-pkg-tag">+<?= $extra ?> more</span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <div class="aap-pkg-actions">
                                    <a href="<?= base_url() ?>user_master/package_details/<?= htmlspecialchars($p_id) ?>"
                                       class="aap-pkg-book">Book Now</a>
                                    <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_msg ?>"
                                       target="_blank" rel="noopener" class="aap-pkg-wa" title="Book via WhatsApp">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- FAQ -->
                <div class="aap-faq-section">
                    <h2>Frequently Asked <span>Questions</span></h2>
                    <div class="aap-faq-wrap">
                        <?php
                        $faqs = [
                            [
                                'q' => 'Which is the best full body checkup package in Ahmedabad?',
                                'a' => 'The Airmed Complete Body Profile at ₹2999 is the most popular full body checkup package in Ahmedabad. It covers 11 key tests including CBC, Lipid Profile, Thyroid (T3/T4/TSH), HbA1c, Vitamin B12, Kidney function, Liver (SGPT), and Urine tests — ideal for an annual health checkup.'
                            ],
                            [
                                'q' => 'Does Airmed Pathlabs offer home blood sample collection?',
                                'a' => 'Yes, Airmed Pathlabs provides home blood sample collection across all major areas of Ahmedabad. Simply call +91 8101-161616 or send a WhatsApp message to book. A trained phlebotomist will visit your home at the scheduled time.'
                            ],
                            [
                                'q' => 'What is the cheapest blood test package available?',
                                'a' => 'The Basic Body Profile at ₹499 is the most affordable package. It includes CBC, Creatinine, Random Blood Glucose, SGPT, and Urine Routine Examination — essential markers for a basic health screening.'
                            ],
                            [
                                'q' => 'What health packages are available for senior citizens?',
                                'a' => 'Airmed offers dedicated Senior Citizen packages for Male and Female at ₹7999 each, covering 19–20 tests: Iron Profile, Vitamin D, Vitamin B12, Thyroid, Lipid Profile, Liver, Kidney, HbA1c, Homocysteine, PSA (for males), and CA 125 (for females).'
                            ],
                            [
                                'q' => 'How do I book a blood test package at Airmed Pathlabs?',
                                'a' => 'You can book by: (1) Clicking "Book Now" on any package card above, (2) WhatsApp to +91 97255-04245, or (3) Calling +91 8101-161616. Home collection is available across all major areas of Ahmedabad.'
                            ],
                            [
                                'q' => 'How long does it take to receive reports?',
                                'a' => 'Most routine blood test reports are delivered within 24 hours of sample collection. Reports are available digitally via the Airmed mobile app, WhatsApp, and email. Some specialty tests may take 48–72 hours — our team will inform you at the time of booking.'
                            ],
                        ];
                        foreach ($faqs as $faq):
                        ?>
                        <div class="aap-faq-item">
                            <button class="aap-faq-q" onclick="aapToggleFaq(this)">
                                <?= htmlspecialchars($faq['q']) ?>
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="aap-faq-a"><?= htmlspecialchars($faq['a']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- App strip -->
                <div class="aap-app-strip">
                    <div>
                        <h2>Download the Airmed App</h2>
                        <p>Book tests, view reports &amp; track your health — all in one place.</p>
                    </div>
                    <div class="aap-app-btns">
                        <a href="https://play.google.com/store/apps/details?id=com.patholab&hl=en" target="_blank" rel="noopener" class="aap-app-btn">▶ Google Play</a>
                        <a href="https://itunes.apple.com/in/app/airmed-pathlabs/id1152367695?mt=8" target="_blank" rel="noopener" class="aap-app-btn"> App Store</a>
                    </div>
                </div>

            </div><!-- /.aap-content -->

            <!-- ===== SIDEBAR ===== -->
            <div class="aap-sidebar">

                <!-- Book CTA -->
                <div class="aap-sb-cta">
                    <h3>Book Your Test</h3>
                    <p>Home sample collection available. Fast, accurate results delivered to your phone.</p>
                    <a href="https://wa.me/<?= $wa_number ?>?text=<?= urlencode('Hi, I want to book a blood test package') ?>"
                       target="_blank" rel="noopener" class="aap-sb-cta-wa">📲 Book via WhatsApp</a>
                    <a href="tel:+918101161616" class="aap-sb-cta-call">📞 +91 8101-161616</a>
                </div>

                <!-- Category accordion sidebar -->
                <div class="aap-sb-card">
                    <div class="aap-sb-header">Browse by Category</div>
                    <div class="aap-sb-body">
                        <?php foreach ($suggest_package as $i => $cat_group):
                            $cat_name = $cat_group['name'] ?? 'Packages';
                            $pkgs     = $cat_group['package'] ?? [];
                        ?>
                        <div class="aap-sb-cat">
                            <button class="aap-sb-cat-btn <?= $i === 0 ? 'open' : '' ?>"
                                    onclick="aapToggleSbCat(this)">
                                <?= htmlspecialchars(ucwords($cat_name)) ?>
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="aap-sb-cat-body <?= $i === 0 ? 'open' : '' ?>">
                                <?php foreach ($pkgs as $p):
                                    $p_id    = $p['id']      ?? '';
                                    $p_title = $p['title']   ?? '';
                                    $p_price = $p['d_price'] ?? '';
                                    $p_tests = $p['test_list'] ?? [];
                                    $p_anchor = 'pkg-' . $p_id;
                                ?>
                                <div class="aap-sb-pkg-item">
                                    <span class="aap-sb-pkg-name">
                                        <a href="<?= base_url() ?>user_master/package_details/<?= htmlspecialchars($p_id) ?>">
                                            <?= htmlspecialchars($p_title) ?>
                                        </a>
                                    </span>
                                    <?php if ($p_price): ?>
                                    <span class="aap-sb-pkg-price">₹<?= htmlspecialchars($p_price) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($p_tests)): ?>
                                <div class="aap-sb-pkg-tests">
                                    <ul>
                                        <?php foreach (array_slice($p_tests, 0, 5) as $t): ?>
                                        <li><?= htmlspecialchars(ucfirst($t['test_name'] ?? '')) ?></li>
                                        <?php endforeach; ?>
                                        <?php $extra2 = count($p_tests) - 5; if ($extra2 > 0): ?>
                                        <li>+<?= $extra2 ?> more tests</li>
                                        <?php endif; ?>
                                    </ul>
                                    <a href="<?= base_url() ?>user_master/package_details/<?= htmlspecialchars($p_id) ?>"
                                       class="aap-sb-book-link">Book Now →</a>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Accreditations -->
                <div class="aap-sb-card">
                    <div class="aap-sb-header">Our Accreditations</div>
                    <div class="aap-accred-grid">
                        <div class="aap-accred-item">
                            <strong>NABL Accredited</strong>
                            <span>ISO 15189 certified diagnostic lab</span>
                        </div>
                        <div class="aap-accred-item">
                            <strong>ICMR Approved</strong>
                            <span>Govt. approved testing protocols</span>
                        </div>
                        <div class="aap-accred-item">
                            <strong>Home Collection</strong>
                            <span>Trained phlebotomists, barcode tracking</span>
                        </div>
                        <div class="aap-accred-item">
                            <strong>Digital Reports</strong>
                            <span>Reports on app, WhatsApp &amp; email</span>
                        </div>
                    </div>
                </div>

            </div><!-- /.aap-sidebar -->

        </div><!-- /.aap-layout -->
    </div><!-- /.aap-container -->
</div><!-- /.aap-main -->

</div><!-- /.aap-wrap -->

<!-- =====================  JS  ===================== -->
<script>
/* ---- Filter tabs ---- */
function aapFilter(cat, btn) {
    // Update active tab
    document.querySelectorAll('.aap-filter-btn').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');

    // Clear search
    document.getElementById('aap-search').value = '';
    document.getElementById('aap-no-results').style.display = 'none';

    var sections = document.querySelectorAll('.aap-category-section');
    sections.forEach(function(s) {
        if (cat === 'all' || s.dataset.cat === cat) {
            s.classList.add('visible');
            s.style.display = '';
        } else {
            s.classList.remove('visible');
            s.style.display = 'none';
        }
    });
}

/* ---- Live search ---- */
function aapSearch(val) {
    val = val.toLowerCase().trim();
    var cards = document.querySelectorAll('.aap-pkg-card');
    var anyVisible = false;

    // Reset filter tabs to "all"
    document.querySelectorAll('.aap-filter-btn').forEach(function(b){ b.classList.remove('active'); });
    document.querySelector('.aap-filter-btn').classList.add('active');

    // Show all sections first
    document.querySelectorAll('.aap-category-section').forEach(function(s){ s.style.display = ''; });

    cards.forEach(function(card) {
        var title = card.dataset.title || '';
        var match = !val || title.includes(val);
        card.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
    });

    // Hide empty category sections
    document.querySelectorAll('.aap-category-section').forEach(function(s) {
        var visibleCards = s.querySelectorAll('.aap-pkg-card:not([style*="display: none"])');
        s.style.display = visibleCards.length ? '' : 'none';
    });

    document.getElementById('aap-no-results').style.display = anyVisible ? 'none' : 'block';
}

/* ---- FAQ accordion ---- */
function aapToggleFaq(btn) {
    var a = btn.nextElementSibling;
    var isOpen = a.classList.contains('open');
    document.querySelectorAll('.aap-faq-a').forEach(function(x){ x.classList.remove('open'); });
    document.querySelectorAll('.aap-faq-q').forEach(function(x){ x.classList.remove('open'); });
    if (!isOpen) { a.classList.add('open'); btn.classList.add('open'); }
}

/* ---- Sidebar category accordion ---- */
function aapToggleSbCat(btn) {
    var body = btn.nextElementSibling;
    var isOpen = body.classList.contains('open');
    body.classList.toggle('open', !isOpen);
    btn.classList.toggle('open', !isOpen);
}
</script>