<style>
/* ── Footer Blue Theme Override ─────────────────────────── */
.footer.accent-background {
  background: linear-gradient(160deg, #061830 0%, #0a2d5e 50%, #0f4c81 100%) !important;
  color: rgba(255,255,255,0.85);
}
.footer.accent-background .sitename {
  color: #fff;
  font-weight: 800;
  letter-spacing: 0.5px;
}
.footer.accent-background p,
.footer.accent-background span {
  color: rgba(255,255,255,0.7);
}
.footer.accent-background h4 {
  color: #fff;
  font-weight: 700;
  font-size: 0.95rem;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  padding-bottom: 10px;
  border-bottom: 2px solid rgba(255,255,255,0.12);
  margin-bottom: 16px;
}
.footer.accent-background .footer-links ul a {
  color: rgba(255,255,255,0.65);
  transition: color 0.2s, padding-left 0.2s;
}
.footer.accent-background .footer-links ul a:hover {
  color: #50e6ff;
  padding-left: 4px;
}
.footer.accent-background .footer-links ul i {
  color: #50e6ff;
}
.footer.accent-background .social-links a {
  border-color: rgba(255,255,255,0.2);
  color: rgba(255,255,255,0.6);
  background: rgba(255,255,255,0.05);
  transition: all 0.2s;
}
.footer.accent-background .social-links a:hover {
  background: #0078d4;
  border-color: #0078d4;
  color: #fff;
  transform: translateY(-2px);
}
.footer.accent-background .footer-contact strong {
  color: rgba(255,255,255,0.9);
}
.footer.accent-background .copyright {
  background: rgba(0,0,0,0.25);
  border-top: 1px solid rgba(255,255,255,0.08);
  border-radius: 0;
  padding: 18px 0;
}
.footer.accent-background .copyright p,
.footer.accent-background .credits,
.footer.accent-background .credits a {
  color: rgba(255,255,255,0.45);
  font-size: 0.82rem;
}
.footer.accent-background .credits a:hover {
  color: #50e6ff;
}
/* Divider line in footer top */
.footer.accent-background .footer-top {
  border-top: 1px solid rgba(255,255,255,0.08);
  padding-top: 28px;
  margin-top: 4px;
}
/* ── Properties by Location standalone strip ──────────────── */
.footer-locations {
  background: rgba(0,0,0,0.18);
  border-top: 1px solid rgba(255,255,255,0.07);
  border-bottom: 1px solid rgba(255,255,255,0.07);
  padding: 28px 0 20px;
  margin-top: 24px;
}
.loc-section-head {
  font-size: .88rem;
  font-weight: 700;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  display: flex;
  align-items: center;
  gap: 4px;
  margin-bottom: 18px;
  flex-wrap: wrap;
}
.loc-section-head i { color: #50e6ff; }
.loc-section-sub {
  font-size: .72rem;
  font-weight: 400;
  text-transform: none;
  letter-spacing: 0;
  color: rgba(255,255,255,.4);
  margin-left: 6px;
}
/* Contact bar below locations */
.footer-contact-bar {
  padding: 18px 0;
  border-bottom: 1px solid rgba(255,255,255,0.07);
}
.footer-contact-bar p {
  color: rgba(255,255,255,0.7);
  font-size: .88rem;
}
/* Location group headings */
.footer.accent-background .loc-group-head {
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  color: #50e6ff;
  margin-bottom: 8px;
}
/* Footer brand highlight strip */
.footer-brand-strip {
  background: rgba(255,255,255,0.04);
  border-bottom: 1px solid rgba(255,255,255,0.08);
  padding: 14px 0;
  margin-bottom: 0;
}
.footer-brand-strip .tagline {
  font-size: 0.82rem;
  color: rgba(255,255,255,0.5);
  margin: 0;
}
.footer-brand-strip .brand-name {
  font-size: 1.3rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: 1px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.footer-brand-strip .brand-name i {
  color: #50e6ff;
  font-size: 1.5rem;
}
</style>

<footer id="footer" class="footer accent-background">

  {{-- Brand strip --}}
  <div class="footer-brand-strip">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="brand-name">
        <i class="bi bi-buildings"></i> {{ config('app.name') }}
      </div>
      <p class="tagline">India's trusted real estate platform — Buy, Rent &amp; Invest</p>
    </div>
  </div>

  {{-- ── Properties by Location ─────────────────────────────────── --}}
  <div class="footer-locations">
    <div class="container">
      <div class="loc-section-head">
        <i class="bi bi-geo-alt-fill me-2"></i>Properties by Location
        <span class="loc-section-sub">Browse verified listings city-wise — each page shows properties within 10 km</span>
      </div>
      <div class="row gy-3">

        {{-- Col 1: Core Tricity --}}
        <div class="col-lg col-6 footer-links">
          <p class="loc-group-head">Core Tricity</p>
          <ul>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','dhakoli') }}">Properties in Dhakoli</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','zirakpur') }}">Properties in Zirakpur</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','panchkula') }}">Properties in Panchkula</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','chandigarh') }}">Properties in Chandigarh</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','mohali') }}">Properties in Mohali</a></li>
          </ul>
        </div>

        {{-- Col 2: Near Zirakpur --}}
        <div class="col-lg col-6 footer-links">
          <p class="loc-group-head">Near Zirakpur</p>
          <ul>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','derabassi') }}">Properties in Derabassi</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','manimajra') }}">Properties in Manimajra</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','banur') }}">Properties in Banur</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','landran') }}">Properties in Landran</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','mullanpur') }}">Properties in Mullanpur</a></li>
          </ul>
        </div>

        {{-- Col 3: Kharar Belt --}}
        <div class="col-lg col-6 footer-links">
          <p class="loc-group-head">Kharar Belt</p>
          <ul>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','kharar') }}">Properties in Kharar</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','gharuan') }}">Properties in Gharuan</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','kurali') }}">Properties in Kurali</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','morinda') }}">Properties in Morinda</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','fatehgarh-sahib') }}">Properties in Fatehgarh Sahib</a></li>
          </ul>
        </div>

        {{-- Col 4: Hills & Highways --}}
        <div class="col-lg col-6 footer-links">
          <p class="loc-group-head">Hills &amp; Highways</p>
          <ul>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','pinjore') }}">Properties in Pinjore</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','kalka') }}">Properties in Kalka</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','solan') }}">Properties in Solan</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','baddi') }}">Properties in Baddi</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','nalagarh') }}">Properties in Nalagarh</a></li>
          </ul>
        </div>

        {{-- Col 5: Beyond Tricity --}}
        <div class="col-lg col-6 footer-links">
          <p class="loc-group-head">Beyond Tricity</p>
          <ul>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','rajpura') }}">Properties in Rajpura</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','ambala') }}">Properties in Ambala</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','ropar') }}">Properties in Ropar</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','barotiwala') }}">Properties in Barotiwala</a></li>
            <li><i class="bi bi-chevron-right"></i><a href="{{ route('properties.location','patiala') }}">Properties in Patiala</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>
  {{-- ── End Properties by Location ──────────────────────────────── --}}

  {{-- ── Hyperlocal SEO Links ─────────────────────────────────────── --}}
  <div style="background:#04152c; padding:22px 0; border-top:1px solid rgba(255,255,255,.08);">
    <div class="container">
      <p class="text-uppercase mb-3" style="color:#50e6ff; font-size:.72rem; font-weight:700; letter-spacing:1.5px;">
        <i class="bi bi-lightning-charge-fill me-1"></i>Quick Property Searches
      </p>
      <div class="d-flex flex-wrap gap-2" style="font-size:.8rem;">
        {{-- High-Intent Keywords (MOST IMPORTANT) --}}
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">apartments for sale in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">buy flat in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">zirakpur property listings</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">zirakpur real estate</a><span style="color:#334155;">·</span>

        {{-- Mohali Keywords --}}
        <a href="{{ url('/flats-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in mohali</a><span style="color:#334155;">·</span>

        {{-- Chandigarh Keywords --}}
        <a href="{{ url('/flats-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in chandigarh</a><span style="color:#334155;">·</span>

        {{-- Panchkula Keywords --}}
        <a href="{{ url('/flats-in-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-panchkula') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in panchkula</a><span style="color:#334155;">·</span>

        {{-- Kharar Keywords --}}
        <a href="{{ url('/flats-in-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-kharar') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in kharar</a><span style="color:#334155;">·</span>

        {{-- Mullanpur Keywords --}}
        <a href="{{ url('/flats-in-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-mullanpur') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in mullanpur</a><span style="color:#334155;">·</span>

        {{-- Derabassi Keywords --}}
        <a href="{{ url('/flats-in-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats for sale in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">ready to move flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">new flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/properties/in/derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">property for sale in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-derabassi') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in derabassi</a>

        {{-- Long-Tail Keywords (LOW COMPETITION + HIGH LEADS) --}}
        {{-- Zirakpur Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-zirakpur-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in zirakpur under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-zirakpur-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in zirakpur under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats in zirakpur near chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-vip-road-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats near vip road zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats in zirakpur with loan facility</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in zirakpur</a><span style="color:#334155;">·</span>

        {{-- Mohali Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-mohali-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in mohali under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-mohali-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in mohali under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-mohali') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-mohali') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">flats in mohali near chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-mohali') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-mohali') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in mohali</a><span style="color:#334155;">·</span>

        {{-- Chandigarh Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-chandigarh-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in chandigarh under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-chandigarh-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in chandigarh under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-chandigarh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-chandigarh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-chandigarh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in chandigarh</a><span style="color:#334155;">·</span>

        {{-- Panchkula Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-panchkula-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in panchkula under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-panchkula-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in panchkula under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-panchkula') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-panchkula') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-panchkula') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in panchkula</a><span style="color:#334155;">·</span>

        {{-- Kharar Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-kharar-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in kharar under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-kharar-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in kharar under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-kharar') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-kharar') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in kharar</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-kharar') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in kharar</a><span style="color:#334155;">·</span>

        {{-- Mullanpur Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-mullanpur-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in mullanpur under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-mullanpur-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in mullanpur under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-mullanpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-mullanpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in mullanpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-mullanpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in mullanpur</a><span style="color:#334155;">·</span>

        {{-- Derabassi Long-Tail --}}
        <a href="{{ url('/2bhk-flats-in-derabassi-under-50-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 bhk flats in derabassi under 50 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-derabassi-under-80-lakh') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 bhk flats in derabassi under 80 lakh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/affordable-flats-in-derabassi') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">affordable flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/resale-flats-in-derabassi') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">resale flats in derabassi</a><span style="color:#334155;">·</span>
        <a href="{{ url('/flats-in-derabassi') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">furnished flats in derabassi</a>

        {{-- Rent --}}
        <a href="{{ url('/rent-flats-in-zirakpur') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Rent Flats Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/rent-flats-in-mohali') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Rent Flats Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/rent-flats-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Rent Flats Chandigarh</a><span style="color:#334155;">·</span>
        {{-- BHK --}}
        <a href="{{ url('/2bhk-flats-in-zirakpur') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 BHK Flats in Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-zirakpur') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 BHK Flats in Zirakpur</a><span style="color:#334155;">·</span>

        <a href="{{ url('/3bhk-flats-in-mohali') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 BHK Flats in Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/3bhk-flats-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">3 BHK Flats in Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/2bhk-flats-in-panchkula') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">2 BHK Flats in Panchkula</a><span style="color:#334155;">·</span>
        {{-- Plots & Villas --}}
        <a href="{{ url('/plots-in-zirakpur') }}"    style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Plots in Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/plots-in-mohali') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Plots in Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/villas-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Villas in Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/villas-in-mohali') }}"     style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Villas in Mohali</a><span style="color:#334155;">·</span>
        {{-- New Projects --}}
        <a href="{{ url('/new-projects-in-zirakpur') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">New Projects Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-mohali') }}"     style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">New Projects Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">New Projects Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/new-projects-in-mullanpur') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">New Projects Mullanpur</a><span style="color:#334155;">·</span>
        {{-- Ready to Move --}}
        <a href="{{ url('/ready-to-move-flats-zirakpur') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Ready to Move flats in Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/ready-to-move-flats-mohali') }}"     style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Ready to Move flats in Mohali</a>
      </div>

      {{-- Home Loan SEO Links --}}
      <p class="text-uppercase mt-4 mb-2" style="color:#7dd3fc; font-size:.72rem; font-weight:700; letter-spacing:1.5px;">
        <i class="bi bi-bank me-1"></i>Home Loan
      </p>
      <div class="d-flex flex-wrap gap-2" style="font-size:.8rem;">
        <a href="{{ url('/home-loan-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Home Loan Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-loan-in-mohali') }}"     style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Home Loan Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-loan-in-zirakpur') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Home Loan Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-loan-in-panchkula') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Home Loan Panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-loan-for-salaried-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Salaried Loan Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-loan-for-self-employed-in-mohali') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Self-Employed Loan Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-loan-eligibility-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Loan Eligibility Chandigarh</a>
      </div>

      {{-- Property Insurance SEO Links --}}
      <p class="text-uppercase mt-3 mb-2" style="color:#86efac; font-size:.72rem; font-weight:700; letter-spacing:1.5px;">
        <i class="bi bi-shield-check me-1"></i>Property Insurance
      </p>
      <div class="d-flex flex-wrap gap-2" style="font-size:.8rem;">
        <a href="{{ url('/property-insurance-in-chandigarh') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Property Insurance Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/property-insurance-in-mohali') }}"     style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Property Insurance Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/property-insurance-in-zirakpur') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Property Insurance Zirakpur</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-insurance-in-panchkula') }}"      style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Home Insurance Panchkula</a><span style="color:#334155;">·</span>
        <a href="{{ url('/home-insurance-for-flat-in-mohali') }}" style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Flat Insurance Mohali</a>
      </div>

      {{-- Legal Help SEO Links --}}
      <p class="text-uppercase mt-3 mb-2" style="color:#d8b4fe; font-size:.72rem; font-weight:700; letter-spacing:1.5px;">
        <i class="bi bi-briefcase me-1"></i>Property Legal Help
      </p>
      <div class="d-flex flex-wrap gap-2" style="font-size:.8rem;">
        <a href="{{ url('/property-legal-help-in-chandigarh') }}"     style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Legal Help Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/property-legal-help-in-mohali') }}"          style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Legal Help Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/title-verification-in-chandigarh') }}"       style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Title Verification Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/title-verification-in-mohali') }}"           style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Title Verification Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/sale-deed-registration-in-chandigarh') }}"   style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Sale Deed Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/sale-deed-registration-in-mohali') }}"       style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Sale Deed Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/property-dispute-lawyer-in-chandigarh') }}"  style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Property Dispute Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/rental-agreement-in-chandigarh') }}"         style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Rental Agreement Chandigarh</a><span style="color:#334155;">·</span>
        <a href="{{ url('/rental-agreement-in-mohali') }}"             style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Rental Agreement Mohali</a><span style="color:#334155;">·</span>
        <a href="{{ url('/will-registration-in-chandigarh') }}"        style="color:#94a3b8;text-decoration:none;" class="footer-seo-link">Will Registration Chandigarh</a>
      </div>
    </div>
  </div>
  {{-- ── End Hyperlocal SEO Links ──────────────────────────────────── --}}

  {{-- Contact Us --}}
  <div class="container footer-contact-bar">
    <div class="row align-items-center gy-3">
      <div class="col-md-auto">
        <p class="mb-0"><i class="bi bi-geo-alt me-2" style="color:#50e6ff;"></i>{{ config('app.contact_address', 'SCO 123, Sector 17, Chandigarh - 160017') }}</p>
      </div>
      <div class="col-md-auto">
        <a href="tel:+91{{ config('app.contact_phone','9876543210') }}" style="color:rgba(255,255,255,0.7); text-decoration:none;">
          <p class="mb-0"><i class="bi bi-telephone me-2" style="color:#50e6ff;"></i>+91 {{ config('app.contact_phone','9876543210') }}</p>
        </a>
      </div>
      <div class="col-md-auto">
        <a href="mailto:{{ config('app.contact_email','support@indianesthub.com') }}" style="color:rgba(255,255,255,0.7); text-decoration:none;">
          <p class="mb-0"><i class="bi bi-envelope me-2" style="color:#50e6ff;"></i>{{ config('app.contact_email','support@indianesthub.com') }}</p>
        </a>
      </div>
      <div class="col-md-auto ms-md-auto">
        <a href="{{ route('dealer.login') }}"
           style="background:#0078d4; color:#fff; border-radius:8px; padding:8px 20px; font-size:.82rem; font-weight:700; text-decoration:none; display:inline-block;">
          <i class="bi bi-plus-circle me-1"></i> Post Property Free
        </a>
      </div>
    </div>
  </div>

  {{-- About + Quick Links + Explore + Finance --}}
  <div class="container footer-top">
    <div class="row gy-4">

      {{-- About --}}
      <div class="col-lg-4 col-md-12 footer-about">
        <p class="mt-1">
          {{ config('app.name') }} connects buyers, sellers and renters with verified property listings
          across India. Find your dream home from thousands of flats, villas, plots, and
          commercial spaces.
        </p>
        <div class="social-links d-flex mt-4">
          @if(env('SOCIAL_TWITTER_URL'))
          <a href="{{ env('SOCIAL_TWITTER_URL') }}" target="_blank" rel="noopener" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
          @endif
          @if(env('SOCIAL_FACEBOOK_URL'))
          <a href="{{ env('SOCIAL_FACEBOOK_URL') }}" target="_blank" rel="noopener" title="Facebook"><i class="bi bi-facebook"></i></a>
          @endif
          @if(env('SOCIAL_INSTAGRAM_URL'))
          <a href="{{ env('SOCIAL_INSTAGRAM_URL') }}" target="_blank" rel="noopener" title="Instagram"><i class="bi bi-instagram"></i></a>
          @endif
          @if(env('SOCIAL_LINKEDIN_URL'))
          <a href="{{ env('SOCIAL_LINKEDIN_URL') }}" target="_blank" rel="noopener" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
          @endif
          <a href="https://wa.me/91{{ config('app.whatsapp_number','9876543210') }}" target="_blank" rel="noopener" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>

      {{-- Quick Links --}}
      <div class="col-lg-2 col-6 footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/') }}">Home</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties') }}">Properties</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/agents') }}">Agents</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ route('builders.index') }}">Builders</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/blog') }}">Blog</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/pricing') }}" style="color:#fbbf24!important;">Pricing &amp; Plans</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/contact') }}">Contact</a></li>
        </ul>
      </div>

      {{-- Property Types --}}
      <div class="col-lg-2 col-6 footer-links">
        <h4>Explore</h4>
        <ul>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties?looking_for=Sale') }}">Buy Property</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties?looking_for=Rent') }}">Rent Property</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties?looking_for=PG') }}">PG / Co-living</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties?property_type=Flat') }}">Flats</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties?property_type=Villa') }}">Villas</a></li>
          <li><i class="bi bi-chevron-right"></i><a href="{{ url('/properties?property_type=Plot') }}">Plots</a></li>
        </ul>
      </div>

      {{-- Financial & Legal Services --}}
      <div class="col-lg-2 col-6 footer-links">
        <h4>Finance &amp; Legal</h4>
        <ul>
          <li><i class="bi bi-bank"></i><a href="#" onclick="event.preventDefault(); openLoanModal(null, null, 'footer');">Home Loan</a></li>
          <li><i class="bi bi-shield-check"></i><a href="#" onclick="event.preventDefault(); openInsuranceModal(null, null, 'footer');">Property Insurance</a></li>
          <li><i class="bi bi-calculator"></i><a href="#" onclick="event.preventDefault(); openLoanModal(null, null, 'footer');">EMI Calculator</a></li>
          <li><i class="bi bi-balance-scale"></i><a href="#" onclick="event.preventDefault(); openLegalModal(null, null, 'footer');" style="color:#c084fc!important;">Legal Help</a></li>
        </ul>
      </div>

    </div>
  </div>{{-- /footer-top --}}

  <div class="container copyright text-center mt-4">
    <p>© {{ date('Y') }} <strong class="sitename">{{ config('app.name') }}</strong>. All Rights Reserved.</p>
    <div class="credits">
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </div>

</footer>
