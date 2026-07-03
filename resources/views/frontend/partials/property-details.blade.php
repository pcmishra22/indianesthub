<style>
/* ===== 99ACRES-INSPIRED PROPERTY DETAILS STYLES ===== */
:root {
  --pd-primary: #1f85de;
  --pd-primary-dark: #1565c0;
  --pd-green: #22c55e;
  --pd-orange: #f97316;
  --pd-red: #ef4444;
  --pd-gray-50: #f8fafc;
  --pd-gray-100: #f1f5f9;
  --pd-gray-200: #e2e8f0;
  --pd-gray-300: #cbd5e1;
  --pd-gray-500: #64748b;
  --pd-gray-600: #475569;
  --pd-gray-700: #334155;
  --pd-gray-800: #1e293b;
  --pd-shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
  --pd-shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
  --pd-shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
  --pd-radius: 8px;
  --pd-radius-lg: 12px;
}

/* ---- PAGE WRAPPER ---- */
.pd-page { background: var(--pd-gray-100); min-height: 100vh; }

/* ---- PROPERTY HEADER BAR ---- */
.pd-header-bar {
  background: #fff;
  border-bottom: 1px solid var(--pd-gray-200);
  padding: 18px 0;
}
.pd-header-bar .pd-title {
  font-size: 1.45rem;
  font-weight: 700;
  color: var(--pd-gray-800);
  margin: 0 0 4px;
  line-height: 1.3;
}
.pd-header-bar .pd-subtitle {
  font-size: .85rem;
  color: var(--pd-gray-500);
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}
.pd-header-bar .pd-price-hero {
  font-size: 1.9rem;
  font-weight: 800;
  color: var(--pd-primary-dark);
  line-height: 1.1;
}
.pd-header-bar .pd-price-sub {
  font-size: .82rem;
  color: var(--pd-gray-500);
  margin-top: 2px;
}
.pd-action-btns { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-action-btns .btn { border-radius: 6px; font-size: .82rem; font-weight: 600; padding: 7px 14px; }

/* ---- GALLERY ---- */
.pd-gallery-section { background: #fff; margin-bottom: 8px; }
.pd-gallery-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 280px 140px;
  gap: 4px;
}
.pd-gallery-grid .gal-main {
  grid-column: 1;
  grid-row: 1 / 3;
  position: relative;
  overflow: hidden;
  cursor: pointer;
}
.pd-gallery-grid .gal-side { position: relative; overflow: hidden; cursor: pointer; }
.pd-gallery-grid img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .3s ease;
}
.pd-gallery-grid .gal-main:hover img,
.pd-gallery-grid .gal-side:hover img { transform: scale(1.04); }
.gal-overlay-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}
.gal-overlay-badge .badge-tag {
  background: var(--pd-primary);
  color: #fff;
  font-size: .72rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 4px;
  letter-spacing: .4px;
  text-transform: uppercase;
}
.gal-overlay-badge .badge-tag.sale { background: var(--pd-green); }
.gal-overlay-badge .badge-tag.featured { background: var(--pd-orange); }
.gal-overlay-badge .badge-tag.premium { background: #7c3aed; }
.gal-photo-count {
  position: absolute;
  bottom: 12px;
  right: 12px;
  background: rgba(0,0,0,.65);
  color: #fff;
  font-size: .78rem;
  font-weight: 600;
  padding: 5px 12px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 5px;
  cursor: pointer;
  transition: background .2s;
}
.gal-photo-count:hover { background: rgba(0,0,0,.85); }
.gal-last-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,.55);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.1rem;
  font-weight: 700;
}

/* Swiper gallery (mobile/all photos modal) */
.pd-swiper-gallery { position: relative; }
.pd-swiper-gallery .hero-image { width: 100%; height: 420px; object-fit: cover; }
.pd-swiper-gallery .swiper-button-next,
.pd-swiper-gallery .swiper-button-prev {
  width: 40px; height: 40px;
  background: rgba(255,255,255,.9);
  border-radius: 50%;
  color: var(--pd-gray-800) !important;
}
.pd-swiper-gallery .swiper-button-next::after,
.pd-swiper-gallery .swiper-button-prev::after { font-size: 14px !important; font-weight: 900; }
.pd-thumb-strip { margin-top: 4px; }
.pd-thumb-strip .thumbnail-img { height: 68px; width: 100%; object-fit: cover; border-radius: 4px; cursor: pointer; opacity: .75; transition: opacity .2s; }
.pd-thumb-strip .swiper-slide-thumb-active .thumbnail-img { opacity: 1; outline: 2px solid var(--pd-primary); }

/* ---- QUICK HIGHLIGHTS ---- */
.pd-highlights {
  background: #fff;
  border-radius: var(--pd-radius);
  box-shadow: var(--pd-shadow);
  margin-bottom: 8px;
}
.pd-highlights-inner {
  display: flex;
  overflow-x: auto;
  scrollbar-width: none;
  padding: 18px 20px;
  gap: 0;
}
.pd-highlights-inner::-webkit-scrollbar { display: none; }
.pd-hl-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 100px;
  padding: 0 20px;
  border-right: 1px solid var(--pd-gray-200);
  text-align: center;
}
.pd-hl-item:last-child { border-right: none; }
.pd-hl-item .hl-icon {
  width: 38px; height: 38px;
  background: #e8f1fc;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--pd-primary);
  font-size: 1rem;
  margin-bottom: 6px;
}
.pd-hl-item .hl-value {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--pd-gray-800);
  line-height: 1.1;
}
.pd-hl-item .hl-label {
  font-size: .72rem;
  color: var(--pd-gray-500);
  margin-top: 2px;
  white-space: nowrap;
}

/* ---- SECTION CARDS ---- */
.pd-card {
  background: #fff;
  border-radius: var(--pd-radius);
  box-shadow: var(--pd-shadow);
  padding: 22px 24px;
  margin-bottom: 8px;
}
.pd-card-title {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--pd-gray-800);
  padding-bottom: 14px;
  margin-bottom: 16px;
  border-bottom: 2px solid var(--pd-gray-100);
  display: flex;
  align-items: center;
  gap: 8px;
}
.pd-card-title i { color: var(--pd-primary); font-size: 1rem; }

/* Description readmore */
.pd-description { font-size: .9rem; color: var(--pd-gray-600); line-height: 1.75; }
.pd-description-clamp { overflow: hidden; transition: max-height .4s ease; }
.pd-readmore-btn {
  background: none; border: none; color: var(--pd-primary); font-size: .85rem;
  font-weight: 600; cursor: pointer; padding: 0; margin-top: 8px;
}
.pd-readmore-btn:hover { text-decoration: underline; }

/* Overview table */
.pd-overview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 14px;
}
.pd-ov-item {
  background: var(--pd-gray-50);
  border: 1px solid var(--pd-gray-200);
  border-radius: 6px;
  padding: 12px 14px;
}
.pd-ov-item .ov-label {
  font-size: .72rem;
  color: var(--pd-gray-500);
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 4px;
}
.pd-ov-item .ov-value {
  font-size: .92rem;
  font-weight: 600;
  color: var(--pd-gray-800);
}

/* Amenities */
.pd-amenities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 10px;
}
.pd-amenity-chip {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--pd-gray-50);
  border: 1px solid var(--pd-gray-200);
  border-radius: 6px;
  padding: 10px 12px;
  font-size: .83rem;
  font-weight: 500;
  color: var(--pd-gray-700);
}
.pd-amenity-chip i { color: var(--pd-primary); font-size: .95rem; }

/* Nearby */
.pd-nearby-list { display: flex; flex-direction: column; gap: 10px; }
.pd-nearby-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  background: var(--pd-gray-50);
  border-radius: 6px;
  border: 1px solid var(--pd-gray-200);
}
.pd-nearby-item .nearby-icon {
  width: 36px; height: 36px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .9rem;
  flex-shrink: 0;
}
.pd-nearby-item .nearby-label { font-size: .82rem; font-weight: 600; color: var(--pd-gray-700); }
.pd-nearby-item .nearby-value { font-size: .8rem; color: var(--pd-gray-500); }

/* Similar Properties */
.pd-similar-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 14px;
}
.pd-similar-card {
  border: 1px solid var(--pd-gray-200);
  border-radius: var(--pd-radius);
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
  background: #fff;
  text-decoration: none;
}
.pd-similar-card:hover {
  box-shadow: var(--pd-shadow-md);
  transform: translateY(-2px);
}
.pd-similar-card img { width: 100%; height: 150px; object-fit: cover; }
.pd-similar-card .similar-body { padding: 12px; }
.pd-similar-card .sim-price {
  font-size: 1rem;
  font-weight: 700;
  color: var(--pd-primary-dark);
  margin-bottom: 4px;
}
.pd-similar-card .sim-title {
  font-size: .82rem;
  font-weight: 600;
  color: var(--pd-gray-800);
  margin-bottom: 4px;
  line-height: 1.35;
}
.pd-similar-card .sim-specs {
  font-size: .75rem;
  color: var(--pd-gray-500);
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.pd-similar-card .sim-specs span { display: flex; align-items: center; gap: 3px; }

/* Map */
.pd-map-wrapper { border-radius: var(--pd-radius); overflow: hidden; border: 1px solid var(--pd-gray-200); }
.pd-map-wrapper iframe { display: block; }

/* ---- SIDEBAR ---- */
.pd-sidebar { position: sticky; top: 80px; }

/* Price card sidebar */
.pd-price-card {
  background: linear-gradient(135deg, var(--pd-primary) 0%, var(--pd-primary-dark) 100%);
  border-radius: var(--pd-radius-lg);
  padding: 22px;
  color: #fff;
  margin-bottom: 8px;
  box-shadow: 0 4px 15px rgba(31,133,222,.35);
}
.pd-price-card .pc-price { font-size: 2rem; font-weight: 800; line-height: 1; }
.pd-price-card .pc-period { font-size: .82rem; opacity: .85; margin-left: 2px; }
.pd-price-card .pc-sub { font-size: .8rem; opacity: .85; margin-top: 4px; }
.pd-price-card .pc-badges { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 12px; }
.pd-price-card .pc-badge {
  background: rgba(255,255,255,.2);
  border: 1px solid rgba(255,255,255,.3);
  border-radius: 4px;
  font-size: .72rem;
  font-weight: 600;
  padding: 3px 9px;
}
.pd-price-card .pc-cta-row { display: flex; gap: 8px; margin-top: 14px; }
.pd-price-card .pc-cta-row .btn { flex: 1; font-weight: 700; font-size: .85rem; border-radius: 7px; padding: 10px; }

/* Contact dealer card */
.pd-dealer-card {
  background: #fff;
  border-radius: var(--pd-radius-lg);
  box-shadow: var(--pd-shadow-md);
  padding: 20px;
  margin-bottom: 8px;
  border: 1px solid var(--pd-gray-200);
}
.pd-dealer-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
.pd-dealer-avatar {
  width: 60px; height: 60px; border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--pd-gray-200);
  flex-shrink: 0;
}
.pd-dealer-name { font-size: 1rem; font-weight: 700; color: var(--pd-gray-800); margin: 0 0 2px; }
.pd-dealer-role { font-size: .78rem; color: var(--pd-gray-500); margin: 0 0 4px; }
.pd-dealer-verified {
  display: inline-flex; align-items: center; gap: 3px;
  font-size: .72rem; color: var(--pd-green); font-weight: 600;
}
.pd-contact-row { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.pd-contact-detail {
  display: flex; align-items: center; gap: 8px;
  font-size: .83rem; color: var(--pd-gray-600);
  padding: 8px 10px;
  background: var(--pd-gray-50);
  border-radius: 6px;
  border: 1px solid var(--pd-gray-200);
}
.pd-contact-detail i { color: var(--pd-primary); width: 16px; text-align: center; }
.pd-dealer-btns { display: flex; flex-direction: column; gap: 8px; }
.pd-dealer-btns .btn { font-weight: 600; font-size: .88rem; border-radius: 7px; padding: 10px; display: flex; align-items: center; justify-content: center; gap: 7px; }
.btn-whatsapp { background: #25d366; border-color: #25d366; color: #fff; }
.btn-whatsapp:hover { background: #1ebe5d; border-color: #1ebe5d; color: #fff; }

/* Inquiry form card */
.pd-inquiry-card {
  background: #fff;
  border-radius: var(--pd-radius-lg);
  box-shadow: var(--pd-shadow);
  padding: 20px;
  margin-bottom: 8px;
  border: 1px solid var(--pd-gray-200);
}
.pd-inquiry-card h5 { font-size: .95rem; font-weight: 700; color: var(--pd-gray-800); margin-bottom: 16px; }
.pd-inquiry-card .form-control,
.pd-inquiry-card .form-select {
  border: 1px solid var(--pd-gray-300);
  border-radius: 6px;
  font-size: .85rem;
  padding: 9px 12px;
  color: var(--pd-gray-700);
}
.pd-inquiry-card .form-control:focus,
.pd-inquiry-card .form-select:focus {
  border-color: var(--pd-primary);
  box-shadow: 0 0 0 3px rgba(31,133,222,.12);
}

/* EMI Calculator */
.pd-emi-card {
  background: #fff;
  border-radius: var(--pd-radius-lg);
  box-shadow: var(--pd-shadow);
  padding: 20px;
  margin-bottom: 8px;
  border: 1px solid var(--pd-gray-200);
}
.pd-emi-card h5 { font-size: .95rem; font-weight: 700; color: var(--pd-gray-800); margin-bottom: 14px; }
.emi-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--pd-gray-100); font-size: .83rem; }
.emi-row:last-child { border-bottom: none; }
.emi-row .emi-label { color: var(--pd-gray-500); }
.emi-row .emi-val { font-weight: 600; color: var(--pd-gray-800); }
.emi-total { display: flex; justify-content: space-between; align-items: center; background: #e8f1fc; padding: 12px 14px; border-radius: 7px; margin-top: 10px; }
.emi-total .emi-label { font-weight: 700; color: var(--pd-primary-dark); font-size: .88rem; }
.emi-total .emi-val { font-weight: 800; color: var(--pd-primary-dark); font-size: 1.05rem; }

/* Photo modal */
.pd-photo-modal .modal-dialog { max-width: 900px; }
.pd-photo-modal .modal-content { background: #111; border: none; border-radius: 12px; }
.pd-photo-modal .modal-header { border-bottom: 1px solid rgba(255,255,255,.1); color: #fff; }
.pd-photo-modal .btn-close { filter: invert(1); }
.pd-photo-modal .hero-image { height: 540px; object-fit: contain; }
.pd-photo-modal .thumbnail-img { height: 70px; object-fit: cover; border-radius: 4px; opacity: .6; }
.pd-photo-modal .swiper-slide-thumb-active .thumbnail-img { opacity: 1; outline: 2px solid var(--pd-primary); }

/* Trust badges */
.pd-trust-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
.pd-trust-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: .72rem; color: var(--pd-gray-600); font-weight: 500;
  border: 1px solid var(--pd-gray-200);
  border-radius: 20px;
  padding: 4px 10px;
  background: var(--pd-gray-50);
}
.pd-trust-badge i { color: var(--pd-green); }

/* Breadcrumb enhancement */
.pd-breadcrumb { background: #fff; border-bottom: 1px solid var(--pd-gray-200); padding: 10px 0; font-size: .82rem; }
.pd-breadcrumb .breadcrumb { margin: 0; }
.pd-breadcrumb .breadcrumb-item a { color: var(--pd-primary); text-decoration: none; }
.pd-breadcrumb .breadcrumb-item.active { color: var(--pd-gray-500); }
.pd-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: var(--pd-gray-300); }

/* Share/Save floating */
.pd-share-save { display: flex; gap: 8px; }
.pd-icon-btn {
  width: 36px; height: 36px;
  border: 1px solid var(--pd-gray-300);
  background: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: var(--pd-gray-600);
  cursor: pointer;
  transition: all .2s;
  font-size: .9rem;
}
.pd-icon-btn:hover { background: var(--pd-gray-100); color: var(--pd-primary); border-color: var(--pd-primary); }

/* Status tag */
.pd-status-tag {
  display: inline-flex; align-items: center;
  font-size: .72rem; font-weight: 700;
  padding: 3px 10px; border-radius: 4px;
  text-transform: uppercase; letter-spacing: .5px;
}
.pd-status-tag.sale { background: #dcfce7; color: #15803d; }
.pd-status-tag.rent { background: #dbeafe; color: #1d4ed8; }

/* Rera badge */
.rera-badge {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: .72rem; font-weight: 600;
  background: #fef9c3; color: #854d0e;
  padding: 3px 9px; border-radius: 4px;
  border: 1px solid #fde68a;
}

/* Floor plan */
.pd-floorplan img { border-radius: var(--pd-radius); border: 1px solid var(--pd-gray-200); }

/* Responsive */
@media (max-width: 768px) {
  .pd-gallery-grid { grid-template-columns: 1fr; grid-template-rows: 240px; }
  .pd-gallery-grid .gal-main { grid-row: 1; }
  .pd-gallery-grid .gal-side:not(.gal-side-1) { display: none; }
  .pd-gallery-grid { grid-template-rows: 240px; }
  .pd-hl-item { min-width: 80px; padding: 0 12px; }
  .pd-header-bar .pd-title { font-size: 1.15rem; }
  .pd-header-bar .pd-price-hero { font-size: 1.45rem; }
  .pd-similar-grid { grid-template-columns: repeat(2, 1fr); }
  .pd-sidebar { position: static; top: unset; }
  .pd-overview-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
  .pd-similar-grid { grid-template-columns: 1fr; }
  .pd-amenities-grid { grid-template-columns: repeat(2, 1fr); }
  .pd-overview-grid { grid-template-columns: 1fr 1fr; }
}
</style>
<style>
/* ===== SOCIAL PROOF STRIP ===== */
.pd-social-proof-strip { display: flex; flex-wrap: wrap; gap: 6px; }
.pd-sp-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: .74rem; font-weight: 600; padding: 4px 10px;
  border-radius: 20px; white-space: nowrap;
}
.pd-sp-badge.sp-views     { background:#eff6ff; color:#1d4ed8; }
.pd-sp-badge.sp-inquiries { background:#fff7ed; color:#c2410c; }
.pd-sp-badge.sp-verified  { background:#f0fdf4; color:#15803d; }
.pd-sp-badge.sp-rera      { background:#ecfdf5; color:#065f46; }
.pd-sp-badge.sp-builder-verified { background:#f5f3ff; color:#6d28d9; }

/* ===== BUILDER CARD ===== */
.pd-builder-card { border-left: 3px solid var(--pd-primary); }
.pd-builder-profile { display:flex; align-items:center; gap:14px; margin-bottom:14px; }
.pd-builder-logo { width:60px; height:60px; object-fit:contain; border-radius:8px; border:1px solid var(--pd-gray-200); background:#fff; padding:4px; }
.pd-builder-logo-placeholder { width:60px; height:60px; border-radius:8px; background:#e8f1fc; display:flex; align-items:center; justify-content:center; color:var(--pd-primary); font-size:1.6rem; }
.pd-builder-name { font-size:1.05rem; font-weight:700; color:var(--pd-gray-800); }
.pd-builder-meta { font-size:.78rem; color:var(--pd-gray-500); margin-top:2px; }
.pd-builder-verified { display:inline-flex; align-items:center; gap:4px; font-size:.72rem; font-weight:600; color:#15803d; background:#f0fdf4; padding:2px 8px; border-radius:12px; margin-top:4px; }
.pd-builder-stats { display:flex; gap:0; background:var(--pd-gray-50); border-radius:8px; border:1px solid var(--pd-gray-200); margin-bottom:12px; overflow:hidden; }
.pd-bstat { flex:1; text-align:center; padding:10px 8px; border-right:1px solid var(--pd-gray-200); }
.pd-bstat:last-child { border-right:none; }
.pd-bstat-num { font-size:1.3rem; font-weight:800; color:var(--pd-primary); line-height:1; }
.pd-bstat-label { font-size:.68rem; color:var(--pd-gray-500); margin-top:3px; }
.pd-builder-desc { font-size:.82rem; color:var(--pd-gray-600); line-height:1.5; margin-bottom:10px; }
.pd-project-info { background:var(--pd-gray-50); border-radius:8px; border:1px solid var(--pd-gray-200); padding:12px 14px; }
.pd-project-label { font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--pd-gray-500); letter-spacing:.5px; margin-bottom:4px; }
.pd-project-name { font-size:.95rem; font-weight:700; color:var(--pd-gray-800); margin-bottom:6px; }
.pd-project-status { font-size:.68rem; font-weight:700; padding:2px 8px; border-radius:10px; }
.pd-project-rera, .pd-project-meta { font-size:.78rem; color:var(--pd-gray-600); margin-top:5px; }

/* ===== WHY BUY HERE ===== */
.pd-why-buy-card { background:linear-gradient(135deg,#fefce8 0%,#fff 60%); }
.pd-why-buy-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.pd-why-item { display:flex; align-items:flex-start; gap:10px; background:rgba(255,255,255,.7); border-radius:8px; padding:10px; border:1px solid #fef9c3; }
.pd-why-icon { width:34px; height:34px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:.9rem; }
.pd-why-label { font-size:.78rem; font-weight:700; color:var(--pd-gray-700); }
.pd-why-val   { font-size:.74rem; color:var(--pd-gray-500); margin-top:2px; }
@media (max-width: 576px) {
  .pd-why-buy-grid { grid-template-columns:1fr; }
  .pd-social-proof-strip { gap:4px; }
}
</style>

<main class="pd-page">

  {{-- ===== BREADCRUMB ===== --}}
  <div class="pd-breadcrumb">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
          @if($property->city)<li class="breadcrumb-item"><a href="{{ route('properties') }}?city={{ $property->city }}">{{ $property->city }}</a></li>@endif
          <li class="breadcrumb-item active">{{ Str::limit($property->title, 40) }}</li>
        </ol>
      </nav>
    </div>
  </div>

  {{-- ===== PROPERTY HEADER BAR ===== --}}
  <div class="pd-header-bar">
    <div class="container">
      <div class="row align-items-center gy-3">
        <div class="col-lg-7">
          {{-- Title + Badges --}}
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="pd-status-tag {{ strtolower($property->looking_for) == 'rent' ? 'rent' : 'sale' }}">
              For {{ $property->looking_for }}
            </span>
            @if($property->is_featured)<span class="pd-status-tag" style="background:#fff7ed;color:#c2410c;">Featured</span>@endif
            @if($property->is_premium)<span class="pd-status-tag" style="background:#f5f3ff;color:#6d28d9;">Premium</span>@endif
            @if($property->rera_id)
            <span class="rera-badge"><i class="bi bi-patch-check-fill"></i>RERA: {{ $property->rera_id }}</span>
            @endif
          </div>

          <h1 class="pd-title">{{ $property->title }}</h1>

          <div class="pd-subtitle">
            <i class="bi bi-geo-alt-fill text-danger"></i>
            <span>{{ $property->address }}@if($property->locality), {{ $property->locality }}@endif, {{ $property->city }}, {{ $property->state }}@if($property->pincode) – {{ $property->pincode }}@endif</span>
            <span class="text-muted mx-1">·</span>
            <span><i class="bi bi-eye me-1"></i>{{ $property->views_count ?? 0 }} views</span>
            <span class="text-muted mx-1">·</span>
            <span>ID #{{ $property->id }}</span>
          </div>

          {{-- ===== SOCIAL PROOF STRIP ===== --}}
          <div class="pd-social-proof-strip mt-2">
            @if(isset($viewsThisWeek) && $viewsThisWeek > 0)
            <span class="pd-sp-badge sp-views">
              <i class="bi bi-graph-up-arrow"></i>
              {{ $viewsThisWeek }} views this week
            </span>
            @endif
            @if(isset($inquiriesThisWeek) && $inquiriesThisWeek > 0)
            <span class="pd-sp-badge sp-inquiries">
              <i class="bi bi-chat-dots-fill"></i>
              {{ $inquiriesThisWeek }} {{ Str::plural('enquiry', $inquiriesThisWeek) }} this week
            </span>
            @endif
            @if($property->dealer)
            <span class="pd-sp-badge sp-verified">
              <i class="bi bi-patch-check-fill"></i>
              Verified Dealer
            </span>
            @endif
            @if($property->rera_verified || $property->rera_id)
            <span class="pd-sp-badge sp-rera">
              <i class="bi bi-shield-fill-check"></i>
              RERA Verified
            </span>
            @endif
            @if($property->builder && $property->builder->is_verified)
            <span class="pd-sp-badge sp-builder-verified">
              <i class="bi bi-building-check"></i>
              Verified Builder
            </span>
            @endif
          </div>

        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-start justify-content-lg-end gap-3 flex-wrap">
            <div class="text-lg-end">
              @if($property->looking_for == 'Rent')
                <div class="pd-price-hero">
                  ₹{{ $property->monthly_rent ? number_format($property->monthly_rent) : number_format($property->price) }}
                  <span style="font-size:.95rem;font-weight:500;color:#64748b">/month</span>
                </div>
                <div class="pd-price-sub d-flex gap-2 flex-wrap justify-content-lg-end">
                  @if($property->security_deposit)<span><i class="bi bi-shield-check text-success me-1"></i>Deposit: {{ $property->security_deposit }}</span>@endif
                  @if($property->maintenance_charges)<span><i class="bi bi-tools text-muted me-1"></i>Maint: ₹{{ number_format($property->maintenance_charges) }}/mo</span>@endif
                </div>
              @else
                <div class="pd-price-hero">₹{{ number_format($property->price) }}</div>
                <div class="pd-price-sub d-flex gap-2 flex-wrap justify-content-lg-end">
                  @if($property->price_per_sqft)<span>₹{{ number_format($property->price_per_sqft) }}/sqft</span>@endif
                  @if($property->negotiable)<span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Negotiable</span>@endif
                </div>
              @endif
            </div>
            <div class="pd-share-save align-self-start mt-1">
              <button class="pd-icon-btn" title="Save"><i class="bi bi-heart"></i></button>
              <button class="pd-icon-btn" title="Share" onclick="navigator.share ? navigator.share({title:'{{$property->title}}',url:window.location.href}) : alert('Copy: ' + window.location.href)"><i class="bi bi-share"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container py-3">
    <div class="row g-3">

      {{-- ===== LEFT MAIN CONTENT ===== --}}
      <div class="col-lg-8">

        {{-- ===== PHOTO GALLERY ===== --}}
        @php
          $images = collect();
          if($property->images && count($property->images)) {
            foreach($property->images as $img) {
              $images->push(url('storage/dealer/' . $property->property_dealer_id . '/' . $property->id . '/images/' . basename($img->image_path)));
            }
          } elseif(!empty($property->cover_image)) {
            $images->push(asset('storage/' . $property->cover_image));
          } else {
            $images->push('/assets/img/real-estate/property-exterior-7.webp');
            $images->push('/assets/img/real-estate/property-interior-7.webp');
            $images->push('/assets/img/real-estate/property-exterior-9.webp');
            $images->push('/assets/img/real-estate/features-5.webp');
          }
          $totalPhotos = $images->count();
        @endphp

        {{-- Desktop gallery grid --}}
        <div class="pd-gallery-section rounded overflow-hidden mb-2 d-none d-md-block" data-bs-toggle="modal" data-bs-target="#galleryModal" style="cursor:pointer;">
          @if($totalPhotos >= 3)
          <div class="pd-gallery-grid" style="height:420px;">
            <div class="gal-main">
              <img src="{{ $images[0] }}" alt="Main photo" style="height:100%;width:100%;object-fit:cover;">
              <div class="gal-overlay-badge">
                <span class="badge-tag {{ strtolower($property->looking_for) == 'rent' ? '' : 'sale' }}">For {{ $property->looking_for }}</span>
                @if($property->is_featured)<span class="badge-tag featured">Featured</span>@endif
              </div>
            </div>
            <div class="gal-side gal-side-1">
              <img src="{{ $images[1] }}" alt="Photo 2" style="height:100%;width:100%;object-fit:cover;">
            </div>
            <div class="gal-side" style="position:relative;">
              <img src="{{ $images[2] }}" alt="Photo 3" style="height:100%;width:100%;object-fit:cover;">
              @if($totalPhotos > 3)
              <div class="gal-last-overlay">
                <span><i class="bi bi-images me-2"></i>+{{ $totalPhotos - 3 }} More</span>
              </div>
              @endif
              <div class="gal-photo-count" style="bottom:10px;right:10px;">
                <i class="bi bi-camera"></i> {{ $totalPhotos }} Photos
              </div>
            </div>
          </div>
          @elseif($totalPhotos == 2)
          <div style="display:grid;grid-template-columns:1fr 1fr;height:380px;gap:4px;">
            <img src="{{ $images[0] }}" style="width:100%;height:100%;object-fit:cover;" alt="Photo 1">
            <img src="{{ $images[1] }}" style="width:100%;height:100%;object-fit:cover;" alt="Photo 2">
          </div>
          @else
          <div style="height:380px;">
            <img src="{{ $images[0] }}" style="width:100%;height:100%;object-fit:cover;" alt="Main photo">
          </div>
          @endif
        </div>

        {{-- Mobile swiper gallery --}}
        <div class="d-md-none mb-2">
          <div class="pd-swiper-gallery swiper init-swiper" style="border-radius:var(--pd-radius);overflow:hidden;">
            <script type="application/json" class="swiper-config">{"loop":true,"speed":600,"autoplay":{"delay":5000},"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"},"thumbs":{"swiper":".pd-mob-thumb-slider"}}</script>
            <div class="swiper-wrapper">
              @foreach($images as $imgUrl)
              <div class="swiper-slide"><img src="{{ $imgUrl }}" class="hero-image" alt="Property photo" style="height:260px;width:100%;object-fit:cover;"></div>
              @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
          </div>
          @if($totalPhotos > 1)
          <div class="pd-mob-thumb-slider swiper init-swiper mt-2" style="padding:4px 0;">
            <script type="application/json" class="swiper-config">{"spaceBetween":6,"slidesPerView":5,"freeMode":true,"watchSlidesProgress":true}</script>
            <div class="swiper-wrapper">
              @foreach($images as $imgUrl)
              <div class="swiper-slide"><img src="{{ $imgUrl }}" class="thumbnail-img" alt="thumb" style="height:55px;width:100%;object-fit:cover;border-radius:4px;opacity:.7;"></div>
              @endforeach
            </div>
          </div>
          @endif
        </div>

        {{-- ===== QUICK HIGHLIGHTS ===== --}}
        <div class="pd-highlights">
          <div class="pd-highlights-inner">
            @if($property->bhk_type || $property->bedrooms)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-house-door"></i></div>
              <div class="hl-value">{{ $property->bhk_type ?? $property->bedrooms . ' BHK' }}</div>
              <div class="hl-label">Configuration</div>
            </div>
            @endif
            @if($property->carpet_area || $property->builtup_area || $property->area)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-arrows-angle-expand"></i></div>
              <div class="hl-value">{{ number_format($property->carpet_area ?? $property->builtup_area ?? $property->area) }}</div>
              <div class="hl-label">{{ $property->area_unit ?? 'Sqft' }}</div>
            </div>
            @endif
            @if($property->bathrooms)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-droplet-half"></i></div>
              <div class="hl-value">{{ $property->bathrooms }}</div>
              <div class="hl-label">Bathrooms</div>
            </div>
            @endif
            @if($property->balconies)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-columns-gap"></i></div>
              <div class="hl-value">{{ $property->balconies }}</div>
              <div class="hl-label">Balconies</div>
            </div>
            @endif
            @if($property->facing)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-compass"></i></div>
              <div class="hl-value">{{ $property->facing }}</div>
              <div class="hl-label">Facing</div>
            </div>
            @endif
            @if($property->floor_number || $property->floor)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-building-up"></i></div>
              <div class="hl-value">{{ $property->floor_number ?? $property->floor }}</div>
              <div class="hl-label">Floor</div>
            </div>
            @endif
            @if($property->covered_parking || $property->open_parking)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-car-front"></i></div>
              <div class="hl-value">{{ ($property->covered_parking ?? 0) + ($property->open_parking ?? 0) }}</div>
              <div class="hl-label">Parking</div>
            </div>
            @endif
            @if($property->furnishing_status)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-lamp"></i></div>
              <div class="hl-value" style="font-size:.85rem;">{{ $property->furnishing_status }}</div>
              <div class="hl-label">Furnishing</div>
            </div>
            @endif
            @if($property->possession_status)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-calendar-check"></i></div>
              <div class="hl-value" style="font-size:.8rem;">{{ Str::limit($property->possession_status, 10) }}</div>
              <div class="hl-label">Possession</div>
            </div>
            @endif
          </div>
        </div>

        {{-- ===== ABOUT THIS PROPERTY ===== --}}
        @if($property->description)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-info-circle-fill"></i> About This Property</div>
          <div class="pd-description">
            <div class="pd-description-clamp" id="descClamp" style="max-height:120px;">
              {!! $property->description !!}
            </div>
            <button class="pd-readmore-btn" id="descToggle" onclick="toggleDesc()">Read More <i class="bi bi-chevron-down"></i></button>
          </div>
        </div>
        @endif

        {{-- ===== PROPERTY OVERVIEW ===== --}}
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-list-columns-reverse"></i> Property Overview</div>
          <div class="pd-overview-grid">
            <div class="pd-ov-item">
              <div class="ov-label">Property Type</div>
              <div class="ov-value">{{ $property->property_type }}</div>
            </div>
            @if($property->bhk_type)
            <div class="pd-ov-item">
              <div class="ov-label">BHK Type</div>
              <div class="ov-value">{{ $property->bhk_type }}</div>
            </div>
            @endif
            @if($property->option_type)
            <div class="pd-ov-item">
              <div class="ov-label">Property Sub-type</div>
              <div class="ov-value">{{ $property->option_type }}</div>
            </div>
            @endif
            <div class="pd-ov-item">
              <div class="ov-label">Listed For</div>
              <div class="ov-value">{{ $property->looking_for }}</div>
            </div>
            @if($property->bedrooms)
            <div class="pd-ov-item">
              <div class="ov-label">Bedrooms</div>
              <div class="ov-value">{{ $property->bedrooms }}</div>
            </div>
            @endif
            @if($property->bathrooms)
            <div class="pd-ov-item">
              <div class="ov-label">Bathrooms</div>
              <div class="ov-value">{{ $property->bathrooms }}</div>
            </div>
            @endif
            @if($property->balconies)
            <div class="pd-ov-item">
              <div class="ov-label">Balconies</div>
              <div class="ov-value">{{ $property->balconies }}</div>
            </div>
            @endif
            @if($property->total_floors)
            <div class="pd-ov-item">
              <div class="ov-label">Total Floors</div>
              <div class="ov-value">{{ $property->total_floors }}</div>
            </div>
            @endif
            @if($property->floor_number)
            <div class="pd-ov-item">
              <div class="ov-label">Floor Number</div>
              <div class="ov-value">{{ $property->floor_number }}</div>
            </div>
            @endif
            @if($property->facing)
            <div class="pd-ov-item">
              <div class="ov-label">Facing</div>
              <div class="ov-value">{{ $property->facing }}</div>
            </div>
            @endif
            @if($property->property_age)
            <div class="pd-ov-item">
              <div class="ov-label">Property Age</div>
              <div class="ov-value">{{ $property->property_age }}</div>
            </div>
            @endif
            @if($property->furnishing_status)
            <div class="pd-ov-item">
              <div class="ov-label">Furnishing</div>
              <div class="ov-value">{{ $property->furnishing_status }}</div>
            </div>
            @endif
            @if($property->possession_status)
            <div class="pd-ov-item">
              <div class="ov-label">Possession</div>
              <div class="ov-value">{{ $property->possession_status }}</div>
            </div>
            @endif
            @if($property->possession_date)
            <div class="pd-ov-item">
              <div class="ov-label">Possession Date</div>
              <div class="ov-value">{{ $property->possession_date }}</div>
            </div>
            @endif
            @if($property->ownership_type)
            <div class="pd-ov-item">
              <div class="ov-label">Ownership</div>
              <div class="ov-value">{{ $property->ownership_type }}</div>
            </div>
            @endif
          </div>
        </div>

        {{-- ===== AREA DETAILS ===== --}}
        @if($property->super_builtup_area || $property->builtup_area || $property->carpet_area || $property->plot_area)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-rulers"></i> Area Details</div>
          <div class="pd-overview-grid">
            @if($property->super_builtup_area)
            <div class="pd-ov-item">
              <div class="ov-label">Super Built-up Area</div>
              <div class="ov-value">{{ number_format($property->super_builtup_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->builtup_area)
            <div class="pd-ov-item">
              <div class="ov-label">Built-up Area</div>
              <div class="ov-value">{{ number_format($property->builtup_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->carpet_area)
            <div class="pd-ov-item">
              <div class="ov-label">Carpet Area</div>
              <div class="ov-value">{{ number_format($property->carpet_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->plot_area)
            <div class="pd-ov-item">
              <div class="ov-label">Plot Area</div>
              <div class="ov-value">{{ number_format($property->plot_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->plot_length)
            <div class="pd-ov-item">
              <div class="ov-label">Plot Length</div>
              <div class="ov-value">{{ $property->plot_length }} {{ $property->area_unit ?? 'ft' }}</div>
            </div>
            @endif
            @if($property->plot_breadth)
            <div class="pd-ov-item">
              <div class="ov-label">Plot Breadth</div>
              <div class="ov-value">{{ $property->plot_breadth }} {{ $property->area_unit ?? 'ft' }}</div>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- ===== PRICING DETAILS ===== --}}
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-currency-rupee"></i> Price Details</div>
          <div class="pd-overview-grid">
            <div class="pd-ov-item">
              <div class="ov-label">{{ $property->looking_for == 'Rent' ? 'Monthly Rent' : 'Asking Price' }}</div>
              <div class="ov-value" style="color:var(--pd-primary-dark);font-size:1rem;">₹{{ number_format($property->price) }}</div>
            </div>
            @if($property->expected_price && $property->expected_price != $property->price)
            <div class="pd-ov-item">
              <div class="ov-label">Expected Price</div>
              <div class="ov-value">₹{{ number_format($property->expected_price) }}</div>
            </div>
            @endif
            @if($property->price_per_sqft)
            <div class="pd-ov-item">
              <div class="ov-label">Price per Sqft</div>
              <div class="ov-value">₹{{ number_format($property->price_per_sqft) }}</div>
            </div>
            @endif
            <div class="pd-ov-item">
              <div class="ov-label">Negotiable</div>
              <div class="ov-value" style="{{ $property->negotiable ? 'color:var(--pd-green)' : '' }}">{{ $property->negotiable ? '✓ Yes' : 'No' }}</div>
            </div>
            @if($property->booking_amount)
            <div class="pd-ov-item">
              <div class="ov-label">Booking Amount</div>
              <div class="ov-value">₹{{ number_format($property->booking_amount) }}</div>
            </div>
            @endif
            @if($property->security_deposit)
            <div class="pd-ov-item">
              <div class="ov-label">Security Deposit</div>
              <div class="ov-value">{{ $property->security_deposit }}</div>
            </div>
            @endif
            @if($property->maintenance_charges)
            <div class="pd-ov-item">
              <div class="ov-label">Maintenance</div>
              <div class="ov-value">₹{{ number_format($property->maintenance_charges) }}/mo</div>
            </div>
            @endif
            @if($property->monthly_rent && $property->looking_for == 'Rent')
            <div class="pd-ov-item">
              <div class="ov-label">Monthly Rent</div>
              <div class="ov-value" style="color:var(--pd-primary-dark);">₹{{ number_format($property->monthly_rent) }}</div>
            </div>
            @endif
            @if($property->lease_duration)
            <div class="pd-ov-item">
              <div class="ov-label">Lease Duration</div>
              <div class="ov-value">{{ $property->lease_duration }}</div>
            </div>
            @endif
          </div>
        </div>

        {{-- ===== AMENITIES ===== --}}
        @php
          $amenitiesArr = [];
          if (!empty($property->amenities)) {
            $amenitiesArr = is_array($property->amenities) ? $property->amenities : json_decode($property->amenities, true);
            if (!is_array($amenitiesArr)) $amenitiesArr = [];
          }
          $amenityIcons = [
            'lift' => 'bi-arrow-up-square', 'elevator' => 'bi-arrow-up-square',
            'gym' => 'bi-activity', 'fitness' => 'bi-activity',
            'pool' => 'bi-water', 'swimming' => 'bi-water',
            'parking' => 'bi-car-front', 'car' => 'bi-car-front',
            'security' => 'bi-shield-check', 'guard' => 'bi-shield-check', '24/7' => 'bi-shield-check',
            'power' => 'bi-lightning-charge', 'backup' => 'bi-lightning-charge',
            'garden' => 'bi-tree', 'park' => 'bi-tree', 'lawn' => 'bi-tree',
            'wifi' => 'bi-wifi', 'internet' => 'bi-wifi',
            'club' => 'bi-house-heart', 'clubhouse' => 'bi-house-heart',
            'play' => 'bi-people', 'children' => 'bi-people',
            'cctv' => 'bi-camera-video', 'camera' => 'bi-camera-video',
            'waste' => 'bi-trash3', 'disposal' => 'bi-trash3',
            'fire' => 'bi-fire', 'firefighting' => 'bi-fire',
            'maintenance' => 'bi-tools', 'management' => 'bi-tools',
            'rainwater' => 'bi-cloud-rain', 'water' => 'bi-droplet',
            'solar' => 'bi-sun', 'gas' => 'bi-flame',
            'intercom' => 'bi-telephone', 'concierge' => 'bi-person-badge',
            'pet' => 'bi-heart', 'vastu' => 'bi-compass',
          ];
          function getAmenityIcon($amenity, $icons) {
            $lower = strtolower($amenity);
            foreach ($icons as $key => $icon) {
              if (str_contains($lower, $key)) return $icon;
            }
            return 'bi-check-circle';
          }
        @endphp
        @php
          $features = [];
          if ($property->gated_society) $features[] = ['name' => 'Gated Society', 'icon' => 'bi-shield-lock'];
          if ($property->corner_property) $features[] = ['name' => 'Corner Property', 'icon' => 'bi-geo'];
          if ($property->vastu_compliant) $features[] = ['name' => 'Vastu Compliant', 'icon' => 'bi-compass'];
          if ($property->wheelchair_friendly) $features[] = ['name' => 'Wheelchair Friendly', 'icon' => 'bi-person-wheelchair'];
          if ($property->overlooking_park) $features[] = ['name' => 'Overlooking Park', 'icon' => 'bi-tree'];
          if ($property->overlooking_road) $features[] = ['name' => 'Overlooking Road', 'icon' => 'bi-signpost'];
          if ($property->pet_friendly) $features[] = ['name' => 'Pet Friendly', 'icon' => 'bi-heart'];
        @endphp

        @if(!empty($amenitiesArr) || !empty($features))
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-stars"></i> Amenities & Features</div>
          @if(!empty($amenitiesArr))
          <h6 class="text-muted fw-semibold mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Amenities</h6>
          <div class="pd-amenities-grid mb-4">
            @foreach($amenitiesArr as $amenity)
            <div class="pd-amenity-chip">
              <i class="bi {{ getAmenityIcon($amenity, $amenityIcons) }}"></i>
              <span>{{ $amenity }}</span>
            </div>
            @endforeach
          </div>
          @endif
          @if(!empty($features))
          <h6 class="text-muted fw-semibold mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Property Features</h6>
          <div class="pd-amenities-grid">
            @foreach($features as $feature)
            <div class="pd-amenity-chip">
              <i class="bi {{ $feature['icon'] }}"></i>
              <span>{{ $feature['name'] }}</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
        @endif

        {{-- ===== PARKING & UTILITIES ===== --}}
        @if($property->covered_parking || $property->open_parking || $property->water_supply || $property->electricity_status || $property->gas_pipeline !== null || $property->drainage !== null)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-plug-fill"></i> Parking & Utilities</div>
          <div class="pd-overview-grid">
            @if($property->covered_parking)<div class="pd-ov-item"><div class="ov-label">Covered Parking</div><div class="ov-value">{{ $property->covered_parking }}</div></div>@endif
            @if($property->open_parking)<div class="pd-ov-item"><div class="ov-label">Open Parking</div><div class="ov-value">{{ $property->open_parking }}</div></div>@endif
            @if($property->water_supply)<div class="pd-ov-item"><div class="ov-label">Water Supply</div><div class="ov-value">{{ $property->water_supply }}</div></div>@endif
            @if($property->electricity_status)<div class="pd-ov-item"><div class="ov-label">Electricity</div><div class="ov-value">{{ $property->electricity_status }}</div></div>@endif
            @if($property->gas_pipeline !== null)<div class="pd-ov-item"><div class="ov-label">Gas Pipeline</div><div class="ov-value">{{ $property->gas_pipeline ? 'Available' : 'Not Available' }}</div></div>@endif
            @if($property->drainage !== null)<div class="pd-ov-item"><div class="ov-label">Drainage</div><div class="ov-value">{{ $property->drainage ? 'Available' : 'Not Available' }}</div></div>@endif
          </div>
        </div>
        @endif

        {{-- ===== LEGAL & RERA ===== --}}
        @if($property->rera_id || $property->property_approval || $property->occupancy_certificate || $property->completion_certificate || $property->legal_clearance_status)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-patch-check-fill"></i> Legal & Approvals</div>
          <div class="pd-overview-grid">
            @if($property->rera_id)
            <div class="pd-ov-item">
              <div class="ov-label">RERA ID</div>
              <div class="ov-value">{{ $property->rera_id }} @if($property->rera_verified)<span class="badge bg-success ms-1" style="font-size:.65rem;">Verified</span>@endif</div>
            </div>
            @endif
            @if($property->ownership_type)<div class="pd-ov-item"><div class="ov-label">Ownership</div><div class="ov-value">{{ $property->ownership_type }}</div></div>@endif
            @if($property->property_approval)<div class="pd-ov-item"><div class="ov-label">Approval</div><div class="ov-value">{{ $property->property_approval }}</div></div>@endif
            @if($property->occupancy_certificate)<div class="pd-ov-item"><div class="ov-label">OC Certificate</div><div class="ov-value">{{ $property->occupancy_certificate }}</div></div>@endif
            @if($property->completion_certificate)<div class="pd-ov-item"><div class="ov-label">CC Certificate</div><div class="ov-value">{{ $property->completion_certificate }}</div></div>@endif
            @if($property->legal_clearance_status)<div class="pd-ov-item"><div class="ov-label">Legal Clearance</div><div class="ov-value">{{ $property->legal_clearance_status }}</div></div>@endif
          </div>
        </div>
        @endif

        {{-- ===== FLOOR PLAN ===== --}}
        @if(!empty($property->floor_plan_images) || !empty($property->floor_plan))
        <div class="pd-card pd-floorplan">
          <div class="pd-card-title"><i class="bi bi-diagram-3"></i> Floor Plan</div>
          @if(!empty($property->floor_plan_images) && is_array($property->floor_plan_images))
            @foreach($property->floor_plan_images as $img)
            <img src="{{ asset('storage/' . $img) }}" class="img-fluid mb-3" alt="Floor Plan">
            @endforeach
          @elseif(!empty($property->floor_plan))
            <img src="{{ asset('storage/' . $property->floor_plan) }}" class="img-fluid" alt="Floor Plan">
          @endif
          @if(!empty($property->floor_plan_details))
          <p class="mt-3 text-muted" style="font-size:.85rem;">{!! nl2br(e($property->floor_plan_details)) !!}</p>
          @endif
        </div>
        @endif

        {{-- ===== VIDEO TOUR ===== --}}
        @if($property->video_url)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-camera-reels-fill"></i> Video Tour</div>
          <video width="100%" controls style="max-height:380px;border-radius:8px;">
            <source src="{{ asset('storage/' . $property->video_url) }}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>
        @endif

        {{-- ===== VIRTUAL TOUR & BROCHURE ===== --}}
        @if($property->virtual_tour_url || $property->brochure_pdf)
        <div class="pd-card">
          @if($property->virtual_tour_url || $property->brochure_pdf)
          <div class="pd-card-title"><i class="bi bi-box-arrow-up-right"></i> Downloads & Tours</div>
          <div class="d-flex gap-3 flex-wrap">
            @if($property->virtual_tour_url)
            <a href="{{ $property->virtual_tour_url }}" target="_blank" class="btn btn-outline-primary" style="border-radius:7px;font-weight:600;">
              <i class="bi bi-camera-reels me-2"></i>Virtual Tour
            </a>
            @endif
            @if($property->brochure_pdf)
            <a href="{{ asset('storage/' . $property->brochure_pdf) }}" target="_blank" class="btn btn-outline-danger" style="border-radius:7px;font-weight:600;">
              <i class="bi bi-file-earmark-pdf me-2"></i>Download Brochure
            </a>
            @endif
          </div>
          @endif
        </div>
        @endif

        {{-- ===== MAP LOCATION ===== --}}
        @if($property->latitude && $property->longitude)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-map-fill"></i> Location</div>
          <p class="text-muted mb-3" style="font-size:.85rem;"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $property->address }}@if($property->locality), {{ $property->locality }}@endif, {{ $property->city }}</p>
          <div class="pd-map-wrapper">
            <iframe src="{{ $property->map_embed_url }}" width="100%" height="360" style="border:0;" allowfullscreen loading="lazy"></iframe>
          </div>
        </div>
        @elseif($property->map_url)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-map-fill"></i> Location</div>
          <p class="text-muted mb-3" style="font-size:.85rem;"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $property->address }}, {{ $property->city }}</p>
          <a href="{{ $property->map_url }}" target="_blank" class="btn btn-outline-primary" style="border-radius:7px;font-weight:600;">
            <i class="bi bi-geo-alt me-2"></i>View on Google Maps
          </a>
        </div>
        @endif

        {{-- ===== BUILDER / PROJECT CARD ===== --}}
        @if($property->builder)
        <div class="pd-card pd-builder-card">
          <div class="pd-card-title"><i class="bi bi-building"></i> Builder & Project Info</div>
          <div class="pd-builder-profile">
            <div class="pd-builder-logo-wrap">
              @if($property->builder->logo)
                <img src="{{ asset('storage/' . $property->builder->logo) }}" alt="{{ $property->builder->display_name }}" class="pd-builder-logo">
              @else
                <div class="pd-builder-logo-placeholder"><i class="bi bi-building-fill"></i></div>
              @endif
            </div>
            <div class="pd-builder-info">
              <div class="pd-builder-name">{{ $property->builder->display_name }}</div>
              @if($property->builder->established_year)
              <div class="pd-builder-meta"><i class="bi bi-calendar3 me-1"></i>Est. {{ $property->builder->established_year }}</div>
              @endif
              @if($property->builder->is_verified)
              <span class="pd-builder-verified"><i class="bi bi-patch-check-fill me-1"></i>Verified Builder</span>
              @endif
            </div>
          </div>

          <div class="pd-builder-stats">
            <div class="pd-bstat">
              <div class="pd-bstat-num">{{ $builderTotalProjects }}</div>
              <div class="pd-bstat-label">Total Projects</div>
            </div>
            @if($property->builder->total_delivered_projects)
            <div class="pd-bstat">
              <div class="pd-bstat-num">{{ $property->builder->total_delivered_projects }}</div>
              <div class="pd-bstat-label">Delivered</div>
            </div>
            @endif
            @if($property->builder->rating)
            <div class="pd-bstat">
              <div class="pd-bstat-num">{{ number_format($property->builder->rating, 1) }}<span style="font-size:.75rem;">★</span></div>
              <div class="pd-bstat-label">Rating</div>
            </div>
            @endif
          </div>

          @if($property->builder->description)
          <p class="pd-builder-desc">{{ Str::limit($property->builder->description, 160) }}</p>
          @endif

          @if($property->builderProject)
          <div class="pd-project-info">
            <div class="pd-project-label"><i class="bi bi-layers me-1"></i>Project</div>
            <div class="pd-project-name">{{ $property->builderProject->title }}</div>
            @if($property->builderProject->status)
            <span class="pd-project-status {{ $property->builderProject->status_badge_class ?? 'bg-info' }}">{{ $property->builderProject->status }}</span>
            @endif
            @if($property->builderProject->rera_id)
            <div class="pd-project-rera"><i class="bi bi-shield-fill-check text-success me-1"></i>RERA: {{ $property->builderProject->rera_id }}</div>
            @endif
            @if($property->builderProject->possession_date)
            <div class="pd-project-meta"><i class="bi bi-calendar-check me-1"></i>Possession: {{ $property->builderProject->possession_date->format('M Y') }}</div>
            @endif
          </div>
          @endif

          @if($property->builder->slug)
          <a href="{{ route('builder.show', $property->builder->slug) }}" class="btn btn-outline-primary btn-sm w-100 mt-3" style="border-radius:7px;font-weight:600;">
            <i class="bi bi-arrow-right-circle me-1"></i>View All by {{ $property->builder->display_name }}
          </a>
          @endif
        </div>
        @endif

        {{-- ===== OTHER PROPERTIES BY SAME BUILDER ===== --}}
        @if(isset($builderProperties) && $builderProperties->count())
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-building-fill"></i> More from {{ $property->builder->display_name ?? 'Builder' }}</div>
          <div class="pd-similar-grid">
            @foreach($builderProperties as $bp)
            <a href="{{ route('property-details', $bp) }}" class="pd-similar-card">
              @if($bp->images && $bp->images->count())
                <img src="{{ url('storage/dealer/' . $bp->property_dealer_id . '/' . $bp->id . '/images/' . basename($bp->images->first()->image_path)) }}" alt="{{ $bp->title }}">
              @elseif($bp->cover_image)
                <img src="{{ asset('storage/' . $bp->cover_image) }}" alt="{{ $bp->title }}">
              @else
                <img src="/assets/img/real-estate/property-exterior-4.webp" alt="{{ $bp->title }}">
              @endif
              <div class="similar-body">
                <div class="sim-price">₹{{ number_format($bp->price) }}@if($bp->looking_for == 'Rent')<span style="font-size:.75rem;font-weight:500;color:#64748b">/mo</span>@endif</div>
                <div class="sim-title">{{ Str::limit($bp->title, 45) }}</div>
                <div class="sim-specs">
                  @if($bp->bedrooms)<span><i class="bi bi-house-door"></i> {{ $bp->bedrooms }} Bed</span>@endif
                  @if($bp->area)<span><i class="bi bi-arrows-angle-expand"></i> {{ number_format($bp->area) }} sqft</span>@endif
                </div>
              </div>
            </a>
            @endforeach
          </div>
        </div>
        @endif

        {{-- ===== WHY BUY HERE? ===== --}}
        @if($property->city || $property->locality || $property->nearby_schools || $property->nearby_hospitals)
        <div class="pd-card pd-why-buy-card">
          <div class="pd-card-title"><i class="bi bi-star-fill" style="color:#f59e0b;"></i>
            Why Buy in {{ $property->locality ?? $property->city }}?
          </div>
          <div class="pd-why-buy-grid">
            @if($property->nearby_schools)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-mortarboard-fill"></i></div>
              <div>
                <div class="pd-why-label">Schools Nearby</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_schools, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->nearby_hospitals)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-hospital-fill"></i></div>
              <div>
                <div class="pd-why-label">Hospitals Nearby</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_hospitals, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->nearby_metro)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-train-lightrail-front-fill"></i></div>
              <div>
                <div class="pd-why-label">Metro / Transit</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_metro, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->nearby_malls)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-shop-window"></i></div>
              <div>
                <div class="pd-why-label">Shopping</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_malls, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->gated_society)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-shield-lock-fill"></i></div>
              <div>
                <div class="pd-why-label">Gated Community</div>
                <div class="pd-why-val">24×7 Security & Access Control</div>
              </div>
            </div>
            @endif
            @if($property->vastu_compliant)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-compass-fill"></i></div>
              <div>
                <div class="pd-why-label">Vastu Compliant</div>
                <div class="pd-why-val">Designed as per Vastu Shastra</div>
              </div>
            </div>
            @endif
            @if($property->rera_id)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-patch-check-fill"></i></div>
              <div>
                <div class="pd-why-label">RERA Registered</div>
                <div class="pd-why-val">{{ $property->rera_id }} — Legally Secured</div>
              </div>
            </div>
            @endif
            @if($property->possession_status)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-key-fill"></i></div>
              <div>
                <div class="pd-why-label">Possession</div>
                <div class="pd-why-val">{{ $property->possession_status }}@if($property->possession_date) · {{ \Carbon\Carbon::parse($property->possession_date)->format('M Y') }}@endif</div>
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- ===== SIMILAR PROPERTIES ===== --}}
        @if(isset($similarProperties) && $similarProperties->count())
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-grid-3x2-gap-fill"></i> Similar Properties
            @if($property->bhk_type)<small class="text-muted fw-normal ms-1">· {{ $property->bhk_type }} BHK in {{ $property->city }}</small>@endif
          </div>
          <div class="pd-similar-grid">
            @foreach($similarProperties as $similar)
            <a href="{{ route('property-details', $similar) }}" class="pd-similar-card">
              @if($similar->images && $similar->images->count())
                <img src="{{ url('storage/dealer/' . $similar->property_dealer_id . '/' . $similar->id . '/images/' . basename($similar->images->first()->image_path)) }}" alt="{{ $similar->title }}">
              @elseif($similar->cover_image)
                <img src="{{ asset('storage/' . $similar->cover_image) }}" alt="{{ $similar->title }}">
              @else
                <img src="/assets/img/real-estate/property-exterior-4.webp" alt="{{ $similar->title }}">
              @endif
              <div class="similar-body">
                <div class="sim-price">₹{{ number_format($similar->price) }}@if($similar->looking_for == 'Rent')<span style="font-size:.75rem;font-weight:500;color:#64748b">/mo</span>@endif</div>
                <div class="sim-title">{{ Str::limit($similar->title, 45) }}</div>
                <div class="sim-specs">
                  @if($similar->bedrooms)<span><i class="bi bi-house-door"></i> {{ $similar->bedrooms }} Bed</span>@endif
                  @if($similar->bathrooms)<span><i class="bi bi-droplet"></i> {{ $similar->bathrooms }} Bath</span>@endif
                  @if($similar->area)<span><i class="bi bi-arrows-angle-expand"></i> {{ number_format($similar->area) }} sqft</span>@endif
                </div>
              </div>
            </a>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- End col-lg-8 --}}

      {{-- ===== RIGHT SIDEBAR ===== --}}
      <div class="col-lg-4">
        <div class="pd-sidebar">

          {{-- Price Card --}}
          <div class="pd-price-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
              <div>
                @if($property->looking_for == 'Rent')
                  <div class="pc-price">₹{{ $property->monthly_rent ? number_format($property->monthly_rent) : number_format($property->price) }}<span class="pc-period">/mo</span></div>
                @else
                  <div class="pc-price">₹{{ number_format($property->price) }}</div>
                @endif
                @if($property->price_per_sqft)<div class="pc-sub">₹{{ number_format($property->price_per_sqft) }}/sqft</div>@endif
              </div>
              <span style="background:rgba(255,255,255,.2);border-radius:4px;padding:4px 10px;font-size:.72rem;font-weight:700;">For {{ $property->looking_for }}</span>
            </div>

            <div class="pc-badges">
              @if($property->negotiable)<span class="pc-badge"><i class="bi bi-check-lg me-1"></i>Negotiable</span>@endif
              @if($property->is_featured)<span class="pc-badge"><i class="bi bi-star me-1"></i>Featured</span>@endif
              @if($property->rera_id)<span class="pc-badge"><i class="bi bi-patch-check me-1"></i>RERA</span>@endif
              @if($property->is_premium)<span class="pc-badge"><i class="bi bi-gem me-1"></i>Premium</span>@endif
            </div>

            <div class="pc-cta-row">
              @auth
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-light text-primary">
                <i class="bi bi-telephone-fill me-1"></i>Call
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I'm interested in {{ urlencode($property->title) }}" target="_blank" class="btn btn-whatsapp">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
              </a>
              <button class="btn btn-light text-primary" onclick="document.getElementById('inquiry-form-sidebar').scrollIntoView({behavior:'smooth'})">
                <i class="bi bi-chat-dots-fill me-1"></i>Enquire
              </button>
              @endauth
              @guest
              <a href="{{ route('login') }}" class="btn btn-light text-primary w-100">
                <i class="bi bi-lock-fill me-1"></i> Login to Contact
              </a>
              @endguest
            </div>
          </div>

          {{-- Dealer / Agent Card --}}
          @if($property->dealer)
          <div class="pd-dealer-card">
            <div class="pd-dealer-header">
              @if($property->dealer->profile_image)
                <img src="{{ asset('storage/' . $property->dealer->profile_image) }}" class="pd-dealer-avatar" alt="Dealer">
              @else
                <img src="/assets/img/person/person-f-12.webp" class="pd-dealer-avatar" alt="Dealer">
              @endif
              <div>
                <p class="pd-dealer-name">{{ $property->dealer->name ?? 'Property Dealer' }}</p>
                @if($property->dealer->company_name)<p class="pd-dealer-role">{{ $property->dealer->company_name }}</p>@endif
                <span class="pd-dealer-verified"><i class="bi bi-patch-check-fill"></i> Verified Dealer</span>
              </div>
            </div>

            @php
              $canViewContact = auth()->check() || (!empty($property->public_contact_enabled));
            @endphp

            @if($canViewContact)
            <div class="pd-contact-row">
              <div class="pd-contact-detail">
                <i class="bi bi-telephone-fill"></i>
                <span>+91 {{ config('app.contact_phone','7340753780') }}</span>
              </div>
              <div class="pd-contact-detail">
                <i class="bi bi-envelope-fill"></i>
                <span>{{ config('app.contact_email','admin@indianesthub.com') }}</span>
              </div>
            </div>

            <div class="pd-dealer-btns">
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success">
                <i class="bi bi-telephone-fill"></i> Call Now
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I'm interested in {{ urlencode($property->title) }}" target="_blank" class="btn btn-whatsapp">
                <i class="bi bi-whatsapp"></i> WhatsApp
              </a>
              <button class="btn btn-outline-primary" onclick="document.getElementById('schedule-visit-card').scrollIntoView({behavior:'smooth'})">
                <i class="bi bi-calendar2-check"></i> Request Site Visit
              </button>
            </div>
            @else
            <div class="alert alert-info py-2 mb-0" style="font-size: .85rem;">
              <i class="bi bi-info-circle me-1"></i> Please <a href="{{ route('login') }}" class="fw-bold">Login</a> to view contact details.
            </div>
            @endif

            <div class="pd-trust-row mt-3">
              <span class="pd-trust-badge"><i class="bi bi-shield-check-fill"></i> Safe & Verified</span>
              <span class="pd-trust-badge"><i class="bi bi-clock-fill" style="color:#f59e0b;"></i> Responds Quickly</span>
            </div>
          </div>
          @endif

          {{-- Inquiry Form --}}
          @if($canViewContact)
          <div class="pd-inquiry-card" id="inquiry-form-sidebar">

            <h5><i class="bi bi-envelope-fill me-2 text-primary"></i>Send Your Inquiry</h5>
            <form action="{{ route('property.inquiry.submit') }}" method="POST" id="property-inquiry-form">
              @csrf
              <input type="hidden" name="property_id" value="{{ $property->id }}">
              <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Full Name *" required>
              </div>
              <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address *" required>
              </div>
              <div class="mb-3">
                <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
              </div>
              <div class="mb-3">
                <textarea name="message" class="form-control" rows="3" placeholder="I'm interested in this property..." required>Hi, I am interested in this property. Please share more details.</textarea>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="needs_loan" id="needs_loan_sidebar" value="1">
                <label class="form-check-label small" for="needs_loan_sidebar" style="cursor:pointer;">
                  🏦 I need home loan assistance
                </label>
              </div>
              <button type="submit" class="btn btn-primary w-100" style="border-radius:7px;font-weight:700;padding:11px;">
                <i class="bi bi-send-fill me-2"></i>Send Inquiry
              </button>
            </form>
          </div>
          @else
          <div class="alert alert-info py-2 mb-0" style="font-size: .85rem;">
            <i class="bi bi-info-circle me-1"></i> Please <a href="{{ route('login') }}" class="fw-bold">Login</a> to send an inquiry.
          </div>
          @endif


          {{-- Schedule a Site Visit --}}
          @if($property->property_dealer_id)
          <div class="pd-inquiry-card" id="schedule-visit-card" style="background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 100%);border:1.5px solid #bae6fd;">
            <h5><i class="bi bi-calendar2-check-fill me-2" style="color:#0284c7;"></i>Schedule a Site Visit</h5>
            @auth
            <form id="schedule-viewing-form">
              @csrf
              <input type="hidden" name="property_id" value="{{ $property->id }}">
              <input type="hidden" name="dealer_id" value="{{ $property->property_dealer_id }}">
              <div class="row g-2 mb-2">
                <div class="col-6">
                  <label class="form-label small fw-semibold mb-1">Preferred Date *</label>
                  <input type="date" name="date" class="form-control form-control-sm" required
                         min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                <div class="col-6">
                  <label class="form-label small fw-semibold mb-1">Preferred Time *</label>
                  <select name="time" class="form-control form-control-sm" required>
                    <option value="">Select</option>
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="17:00">5:00 PM</option>
                  </select>
                </div>
              </div>
              <div class="mb-2">
                <input type="text" name="name" class="form-control form-control-sm" placeholder="Your Name *" required>
              </div>
              <div class="row g-2 mb-2">
                <div class="col-6">
                  <input type="email" name="email" class="form-control form-control-sm" placeholder="Email *" required>
                </div>
                <div class="col-6">
                  <input type="tel" name="phone" class="form-control form-control-sm" placeholder="Phone">
                </div>
              </div>

              {{-- Loan assistance toggle --}}
              <div class="p-2 rounded mb-2" style="background:#fff;border:1px dashed #0284c7;">
                <div class="d-flex align-items-center justify-content-between">
                  <label class="small fw-semibold mb-0" style="color:#0369a1;">
                    🏦 Need home loan assistance?
                  </label>
                  <div class="d-flex gap-2">
                    <button type="button" id="loanHelpYes"
                            onclick="toggleScheduleLoan(true)"
                            class="btn btn-sm px-3 py-1" style="font-size:.75rem;border-radius:20px;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">Yes</button>
                    <button type="button" id="loanHelpNo"
                            onclick="toggleScheduleLoan(false)"
                            class="btn btn-sm px-3 py-1" style="font-size:.75rem;border-radius:20px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;">No</button>
                  </div>
                </div>
                <div id="scheduleLoanInfo" style="display:none;" class="mt-2">
                  <p class="mb-1 small" style="color:#0369a1;">✅ A loan expert will contact you after confirming your visit.</p>
                  <input type="hidden" name="needs_loan" id="schedule_needs_loan" value="0">
                </div>
              </div>

              <button type="submit" id="scheduleVisitBtn" class="btn w-100 fw-semibold"
                      style="background:#0284c7;color:#fff;border-radius:7px;padding:10px;">
                <i class="bi bi-calendar-check me-2"></i>Confirm Site Visit
              </button>
              <div id="scheduleVisitSuccess" style="display:none;" class="py-2">
                <div class="text-center mb-2">
                  <i class="bi bi-check-circle-fill text-success d-block mb-1" style="font-size:1.8rem;"></i>
                  <strong class="small">Visit confirmed! The dealer will call you to confirm.</strong>
                </div>
                <div class="mt-2 p-2 rounded" style="background:#f0fdf4;border:1px dashed #16a34a;">
                  <div class="small fw-semibold text-success mb-1">🛡️ Protect your new home?</div>
                  <div class="small text-muted mb-2">Get home insurance from ₹2,000/year before possession.</div>
                  <button type="button" class="btn btn-sm w-100 fw-semibold"
                          style="background:#16a34a;color:#fff;border-radius:6px;"
                          onclick="openInsuranceModal({{ $property->id }}, null, 'post-visit')">
                    Get Free Quote <i class="bi bi-arrow-right ms-1"></i>
                  </button>
                </div>
              </div>
            </form>
            @else
            <div class="text-center py-2">
              <p class="small text-muted mb-2">Login to schedule a visit with the dealer.</p>
              <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login Now</a>
            </div>
            @endauth
          </div>
          @endif

          {{-- EMI Calculator --}}
          @if($property->looking_for != 'Rent')
          @php
            $price = $property->price ?? 0;
            $downPayment = round($price * 0.20);
            $loanAmount = $price - $downPayment;
            $rate = 8.5 / 12 / 100;
            $tenure = 240; // 20 years
            $emi = $rate > 0 ? round($loanAmount * $rate * pow(1+$rate, $tenure) / (pow(1+$rate, $tenure) - 1)) : 0;
          @endphp
          <div class="pd-emi-card">
            <h5><i class="bi bi-calculator-fill me-2 text-primary"></i>EMI Calculator</h5>
            <div class="emi-row">
              <span class="emi-label">Property Price</span>
              <span class="emi-val">₹{{ number_format($price) }}</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Down Payment (20%)</span>
              <span class="emi-val">₹{{ number_format($downPayment) }}</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Loan Amount</span>
              <span class="emi-val">₹{{ number_format($loanAmount) }}</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Interest Rate</span>
              <span class="emi-val">8.5% p.a.</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Loan Tenure</span>
              <span class="emi-val">20 Years</span>
            </div>
            <div class="emi-total">
              <span class="emi-label">Est. Monthly EMI</span>
              <span class="emi-val">₹{{ number_format($emi) }}</span>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:.72rem;">*Estimates are indicative. Actual EMI may vary based on lender.</p>
            <button type="button" class="btn btn-success w-100 mt-3 fw-semibold"
                    onclick="openLoanModal({{ $property->id }}, null, 'property-page')">
              <i class="bi bi-bank me-2"></i> Apply for Home Loan →
            </button>
          </div>
          @endif

          {{-- 🛡️ Insurance CTA card (only for sale properties) --}}
          @if($property->looking_for != 'Rent')
          <div class="pd-inquiry-card"
               style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border:1.5px solid #86efac;">
            <div class="d-flex align-items-start gap-3">
              <div style="background:#16a34a;width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-shield-check-fill text-white" style="font-size:1.1rem;"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1" style="color:#15803d;">Protect Your Home from Day 1 🛡️</h6>
                <p class="text-muted small mb-2">
                  Home insurance from <strong>₹{{ number_format(max(2000, (int)(($property->price ?? 5000000) * 0.0007))) }}/year</strong>.
                  Compare 10+ insurers — HDFC ERGO, Bajaj, Tata AIG &amp; more.
                </p>
                <button type="button" class="btn btn-sm fw-semibold w-100"
                        style="background:#16a34a;color:#fff;border-radius:20px;"
                        onclick="openInsuranceModal({{ $property->id }}, null, 'property-page')">
                  <i class="bi bi-shield-check me-1"></i> Get Free Insurance Quote
                </button>
              </div>
            </div>
          </div>
          @endif

          {{-- Nearby Places --}}
          @if($property->nearby_schools || $property->nearby_hospitals || $property->nearby_malls || $property->nearby_metro || $property->nearby_bus_stand)
          <div class="pd-dealer-card">
            <div class="pd-card-title" style="border-bottom:none;padding-bottom:0;margin-bottom:14px;"><i class="bi bi-geo-alt-fill"></i> Nearby Places</div>
            <div class="pd-nearby-list">
              @if($property->nearby_schools)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-mortarboard"></i></div>
                <div>
                  <div class="nearby-label">Schools</div>
                  <div class="nearby-value">{{ $property->nearby_schools }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_hospitals)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-hospital"></i></div>
                <div>
                  <div class="nearby-label">Hospitals</div>
                  <div class="nearby-value">{{ $property->nearby_hospitals }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_malls)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-shop"></i></div>
                <div>
                  <div class="nearby-label">Malls</div>
                  <div class="nearby-value">{{ $property->nearby_malls }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_metro)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-train-lightrail-front"></i></div>
                <div>
                  <div class="nearby-label">Metro Station</div>
                  <div class="nearby-value">{{ $property->nearby_metro }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_bus_stand)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-bus-front"></i></div>
                <div>
                  <div class="nearby-label">Bus Stand</div>
                  <div class="nearby-value">{{ $property->nearby_bus_stand }}</div>
                </div>
              </div>
              @endif
              @if(is_array($property->distance_metrics ?? null))
                @foreach($property->distance_metrics as $place => $distance)
                <div class="pd-nearby-item">
                  <div class="nearby-icon" style="background:#f8fafc;color:#64748b;"><i class="bi bi-geo-alt"></i></div>
                  <div>
                    <div class="nearby-label">{{ ucfirst($place) }}</div>
                    <div class="nearby-value">{{ $distance }}</div>
                  </div>
                </div>
                @endforeach
              @endif
            </div>
          </div>
          @endif

        </div>
      </div>{{-- End col-lg-4 sidebar --}}

    </div>{{-- End row --}}
  </div>{{-- End container --}}

  {{-- ════════════════════════════════════════════
       INTERNAL LINKING STRIP — Explore More
  ════════════════════════════════════════════ --}}
  <div style="background:#eef5fb; border-top:1px solid #bee3f8; padding:24px 0;">
    <div class="container">
      <p class="fw-700 mb-2" style="color:#0a2d5e;font-size:.9rem;">
        <i class="bi bi-arrow-right-circle me-2" style="color:#0078d4;"></i>Explore More Properties
      </p>
      <div class="d-flex flex-wrap gap-2">
        @if($property->city)
          <a href="{{ url('/properties/in/'.strtolower(str_replace(' ','-',$property->city))) }}"
             class="btn btn-sm btn-outline-primary">
            All Properties in {{ $property->city }}
          </a>
          @php
            $citySlug = strtolower(str_replace(' ', '-', $property->city));
            $seoLandingCities = ['zirakpur','mohali','chandigarh','panchkula','kharar','derabassi','mullanpur','patiala','ambala'];
          @endphp
          @if(in_array($citySlug, $seoLandingCities))
            <a href="{{ url('/flats-in-'.$citySlug) }}" class="btn btn-sm btn-outline-primary">Flats in {{ $property->city }}</a>
            <a href="{{ url('/new-projects-in-'.$citySlug) }}" class="btn btn-sm btn-outline-primary">New Projects in {{ $property->city }}</a>
            @if($property->bhk_type)
              @php $bhkNum = (int) $property->bhk_type; @endphp
              @if($bhkNum >= 1 && $bhkNum <= 5)
                <a href="{{ url('/'.$bhkNum.'bhk-flats-in-'.$citySlug) }}" class="btn btn-sm btn-outline-primary">
                  {{ $property->bhk_type }} Flats in {{ $property->city }}
                </a>
              @endif
            @endif
          @endif
        @endif
        @if($property->looking_for === 'Rent' || $property->looking_for === 'rent')
          @php $rCity = strtolower(str_replace(' ','-',$property->city ?? '')); @endphp
          @if(in_array($rCity, ['zirakpur','mohali','chandigarh','panchkula','kharar']))
            <a href="{{ url('/rent-flats-in-'.$rCity) }}" class="btn btn-sm btn-outline-primary">Rent Flats in {{ $property->city }}</a>
          @endif
        @endif
        <a href="{{ route('properties') }}" class="btn btn-sm btn-primary">All Properties</a>
      </div>
    </div>
  </div>
  {{-- ════════════════════════════════════════════ --}}

</main>

{{-- ===== LOAN ELIGIBILITY MODAL ===== --}}
@include('frontend.partials.loan-eligibility-modal', [
    'property_id'         => $property->id,
    'builder_project_id'  => null,
    'source'              => 'property-page',
    'source_page'         => request()->path(),
    'prefill_loan_amount' => isset($loanAmount) ? $loanAmount : null,
])

{{-- ===== INSURANCE MODAL ===== --}}
@include('frontend.partials.insurance-modal', [
    'property_id'        => $property->id,
    'builder_project_id' => null,
    'source'             => 'property-page',
    'source_page'        => request()->path(),
    'prefill_value'      => $property->price ?? null,
    'prefill_city'       => $property->city  ?? null,
    'prefill_type'       => $property->property_type ?? null,
])

{{-- ===== FULL GALLERY MODAL ===== --}}
<div class="modal fade pd-photo-modal" id="galleryModal" tabindex="-1" aria-label="Photo Gallery" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:940px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-images me-2"></i>{{ $property->title }} — All Photos ({{ $totalPhotos }})
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-2">
        <div class="property-gallery-slider swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {"loop":true,"speed":500,"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"},"thumbs":{"swiper":".gallery-modal-thumbs"}}
          </script>
          <div class="swiper-wrapper">
            @foreach($images as $imgUrl)
            <div class="swiper-slide text-center">
              <img src="{{ $imgUrl }}" class="hero-image" alt="Property Photo" style="height:520px;width:100%;object-fit:contain;">
            </div>
            @endforeach
          </div>
          <div class="swiper-button-next" style="color:#fff;"></div>
          <div class="swiper-button-prev" style="color:#fff;"></div>
        </div>
        <div class="gallery-modal-thumbs swiper init-swiper mt-2">
          <script type="application/json" class="swiper-config">
            {"spaceBetween":6,"slidesPerView":8,"freeMode":true,"watchSlidesProgress":true}
          </script>
          <div class="swiper-wrapper">
            @foreach($images as $imgUrl)
            <div class="swiper-slide">
              <img src="{{ $imgUrl }}" class="thumbnail-img" alt="thumb" style="height:60px;width:100%;object-fit:cover;border-radius:3px;opacity:.65;cursor:pointer;">
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function toggleDesc() {
  const el = document.getElementById('descClamp');
  const btn = document.getElementById('descToggle');
  if (el.style.maxHeight === 'none') {
    el.style.maxHeight = '120px';
    btn.innerHTML = 'Read More <i class="bi bi-chevron-down"></i>';
  } else {
    el.style.maxHeight = 'none';
    btn.innerHTML = 'Show Less <i class="bi bi-chevron-up"></i>';
  }
}

// ─── Schedule Viewing Form ────────────────────────────────────────────────────
function toggleScheduleLoan(yes) {
  const info = document.getElementById('scheduleLoanInfo');
  const input = document.getElementById('schedule_needs_loan');
  const yesBtn = document.getElementById('loanHelpYes');
  const noBtn  = document.getElementById('loanHelpNo');
  if (!info) return;
  if (yes) {
    info.style.display = 'block';
    input.value = '1';
    yesBtn.style.background = '#0284c7';
    yesBtn.style.color = '#fff';
    noBtn.style.background = '#f1f5f9';
    noBtn.style.color = '#64748b';
  } else {
    info.style.display = 'none';
    input.value = '0';
    noBtn.style.background = '#94a3b8';
    noBtn.style.color = '#fff';
    yesBtn.style.background = '#e0f2fe';
    yesBtn.style.color = '#0369a1';
  }
}

const scheduleForm = document.getElementById('schedule-viewing-form');
if (scheduleForm) {
  scheduleForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('scheduleVisitBtn');
    const successDiv = document.getElementById('scheduleVisitSuccess');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Confirming...';

    fetch('{{ route("property.schedule.viewing") }}', {
      method: 'POST',
      body: new FormData(this),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        // If needs_loan, also open loan modal to capture more details
        const needsLoan = document.getElementById('schedule_needs_loan');
        if (needsLoan && needsLoan.value === '1') {
          openLoanModal({{ $property->id }}, null, 'schedule-form');
        }
        scheduleForm.style.display = 'none';
        successDiv.style.display = 'block';
      } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-calendar-check me-2"></i>Confirm Site Visit';
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-calendar-check me-2"></i>Confirm Site Visit';
    });
  });
}

// Inquiry form AJAX submission
document.getElementById('property-inquiry-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = this;
  const btn = form.querySelector('button[type="submit"]');
  const origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
  fetch(form.action, {
    method: 'POST',
    body: new FormData(form),
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Inquiry Sent!';
    btn.classList.remove('btn-primary');
    btn.classList.add('btn-success');
    form.reset();
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = origText;
      btn.classList.remove('btn-success');
      btn.classList.add('btn-primary');
    }, 4000);
  })
  .catch(() => {
    btn.disabled = false;
    btn.innerHTML = origText;
    alert('Something went wrong. Please try again.');
  });
});

// ── Sticky bottom urgency bar (desktop) ───────────────────────────────────
(function() {
  const bar = document.getElementById('pd-sticky-bar');
  if (!bar) return;
  window.addEventListener('scroll', function() {
    const scrolled = window.scrollY > 400;
    bar.style.transform = scrolled ? 'translateY(0)' : 'translateY(110%)';
  }, { passive: true });
})();

// ── Exit-intent popup ──────────────────────────────────────────────────────
(function() {
  let shown = false;
  const popup = document.getElementById('pd-exit-popup');
  if (!popup) return;
  document.addEventListener('mouseleave', function(e) {
    if (!shown && e.clientY < 10) {
      shown = true;
      popup.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  });
  document.getElementById('pd-exit-popup-close')?.addEventListener('click', function() {
    popup.style.display = 'none';
    document.body.style.overflow = '';
  });
  popup.addEventListener('click', function(e) {
    if (e.target === popup) { popup.style.display = 'none'; document.body.style.overflow = ''; }
  });
})();
</script>

{{-- ═══════════════════════════════════════════════════════════════════
     STICKY BOTTOM BAR (desktop, appears after scrolling 400px)
════════════════════════════════════════════════════════════════════ --}}
<style>
#pd-sticky-bar {
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 1050;
  background: #fff; border-top: 2px solid #e2e8f0;
  box-shadow: 0 -4px 20px rgba(0,0,0,.12);
  padding: 12px 0;
  transform: translateY(110%);
  transition: transform .35s cubic-bezier(.4,0,.2,1);
}
#pd-sticky-bar .sb-inner { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
#pd-sticky-bar .sb-title { font-size:.95rem; font-weight:700; color:#1e293b; }
#pd-sticky-bar .sb-price { font-size:1.3rem; font-weight:800; color:#0078d4; }
#pd-sticky-bar .sb-meta  { font-size:.75rem; color:#64748b; }
#pd-sticky-bar .sb-urgency { background:#fff7ed; border:1px solid #fed7aa; border-radius:6px; padding:4px 10px; font-size:.72rem; font-weight:600; color:#c2410c; display:flex; align-items:center; gap:4px; }
#pd-sticky-bar .sb-actions { display:flex; gap:8px; flex-shrink:0; }
@media(max-width:768px) { #pd-sticky-bar { display:none; } }

/* Mobile floating CTA */
#pd-mobile-cta {
  display:none;
  position:fixed; bottom:0; left:0; right:0; z-index:1050;
  background:#fff; border-top:2px solid #e2e8f0;
  box-shadow:0 -4px 20px rgba(0,0,0,.12);
  padding:10px 16px; gap:8px;
}
@media(max-width:768px) {
  #pd-mobile-cta { display:flex; }
}
#pd-mobile-cta .btn { flex:1; border-radius:8px; font-weight:700; font-size:.85rem; padding:11px 8px; }

/* Exit-intent popup */
#pd-exit-popup {
  display:none; position:fixed; inset:0; background:rgba(0,0,0,.6);
  z-index:9999; align-items:center; justify-content:center; padding:16px;
}
.pd-exit-card {
  background:#fff; border-radius:16px; padding:32px 28px; max-width:420px; width:100%;
  position:relative; text-align:center;
  box-shadow:0 20px 60px rgba(0,0,0,.25);
}
.pd-exit-icon { font-size:3rem; margin-bottom:12px; }
.pd-exit-title { font-size:1.3rem; font-weight:800; color:#1e293b; margin-bottom:8px; }
.pd-exit-sub   { font-size:.88rem; color:#64748b; margin-bottom:20px; line-height:1.5; }
#pd-exit-popup-close { position:absolute; top:14px; right:18px; font-size:1.4rem; cursor:pointer; color:#94a3b8; line-height:1; }
</style>

<div id="pd-sticky-bar">
  <div class="container">
    <div class="sb-inner">
      <div>
        <div class="sb-title">{{ Str::limit($property->title, 50) }}</div>
          <div class="sb-meta">
              <i class="bi bi-geo-alt me-1"></i>

              {{ collect([
                  $property->city,
                  $property->bhk_type ? $property->bhk_type . ' BHK' : null,
                  $property->area ? number_format($property->area) . ' sq.ft' : null,
              ])->filter()->implode(' · ') }}
          </div>
      </div>
      <div class="d-flex align-items-center gap-3">
        <div>
          <div class="sb-price">₹{{ number_format($property->price) }}</div>
          @if(isset($inquiriesThisWeek) && $inquiriesThisWeek > 0)
          <div class="sb-urgency">
            <i class="bi bi-fire"></i> {{ $inquiriesThisWeek }} {{ Str::plural('enquiry', $inquiriesThisWeek) }} this week
          </div>
          @endif
        </div>
        <div class="sb-actions">
          <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success" style="border-radius:8px;font-weight:700;">
            <i class="bi bi-telephone-fill me-1"></i>Call Now
          </a>
          <button class="btn btn-primary" style="border-radius:8px;font-weight:700;"
                  onclick="document.getElementById('schedule-visit-card').scrollIntoView({behavior:'smooth'})">
            <i class="bi bi-calendar-check me-1"></i>Request Site Visit
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Mobile floating CTA --}}
<div id="pd-mobile-cta">
  <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success">
    <i class="bi bi-telephone-fill me-1"></i>Call
  </a>
  <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I'm interested in {{ urlencode($property->title) }}"
     target="_blank" class="btn btn-whatsapp" style="background:#25d366;color:#fff;">
    <i class="bi bi-whatsapp me-1"></i>WhatsApp
  </a>
  <button class="btn btn-primary"
          onclick="document.getElementById('inquiry-form-sidebar').scrollIntoView({behavior:'smooth'})">
    <i class="bi bi-send me-1"></i>Enquire
  </button>
</div>

{{-- Exit-Intent Popup --}}
<div id="pd-exit-popup">
  <div class="pd-exit-card">
    <span id="pd-exit-popup-close">&times;</span>
    <div class="pd-exit-icon">🏠</div>
    <div class="pd-exit-title">Wait! Before you go…</div>
    <div class="pd-exit-sub">
      Get a <strong>free callback</strong> from our property expert on
      <strong>{{ $property->title }}</strong>.
      No obligation. No brokerage.
    </div>
    <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success w-100 mb-2" style="border-radius:8px;font-weight:700;padding:13px;">
      <i class="bi bi-telephone-fill me-2"></i>Call Now — +91 {{ config('app.contact_phone','7340753780') }}
    </a>
    <button class="btn btn-primary w-100" style="border-radius:8px;font-weight:700;padding:12px;"
            onclick="document.getElementById('pd-exit-popup').style.display='none';document.body.style.overflow='';document.getElementById('inquiry-form-sidebar').scrollIntoView({behavior:'smooth'})">
      <i class="bi bi-send me-2"></i>Send Quick Enquiry
    </button>
    <div class="mt-3" style="font-size:.72rem;color:#94a3b8;">
      <i class="bi bi-shield-check me-1 text-success"></i>100% free · No brokerage · Safe & secure
    </div>
  </div>
</div>
