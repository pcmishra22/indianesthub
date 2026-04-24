<!-- Static property cards 2-6 from properties.html (abbreviated for brevity, copy full HTML for each card as in the template) -->
<div class="col-lg-4 col-md-6">
  <div class="property-item">
    <a href="{{ route('properties') }}" class="property-link">
      <div class="property-image-wrapper">
        <img src="/assets/img/real-estate/property-interior-1.webp" alt="Modern Apartment" class="img-fluid">
        <div class="property-status">
          <span class="status-badge new">New Listing</span>
          <span class="status-badge rent">For Rent</span>
        </div>
        <div class="property-actions">
          <button class="action-btn favorite-btn" data-toggle="tooltip" title="Add to Favorites">
            <i class="bi bi-heart"></i>
          </button>
          <button class="action-btn share-btn" data-toggle="tooltip" title="Share Property">
            <i class="bi bi-share"></i>
          </button>
          <button class="action-btn gallery-btn" data-toggle="tooltip" title="View Gallery">
            <i class="bi bi-images"></i>
            <span class="gallery-count">9</span>
          </button>
        </div>
      </div>
    </a>
    <div class="property-details">
      <a href="{{ route('properties') }}" class="property-link">
        <div class="property-header">
          <div class="property-price">$5,200<span>/month</span></div>
          <div class="property-type">Apartment</div>
        </div>
        <h4 class="property-title">Downtown Modern Penthouse</h4>
        <p class="property-address">
          <i class="bi bi-geo-alt"></i>
          1247 Broadway Street, Manhattan, NY 10001
        </p>
        <div class="property-specs">
          <div class="spec-item">
            <i class="bi bi-house-door"></i>
            <span>3 Bedrooms</span>
          </div>
          <div class="spec-item">
            <i class="bi bi-droplet"></i>
            <span>2 Bathrooms</span>
          </div>
          <div class="spec-item">
            <i class="bi bi-arrows-angle-expand"></i>
            <span>2,100 sq ft</span>
          </div>
        </div>
      </a>
      <div class="property-agent-info">
        <a href="{{ route('properties') }}" class="property-link">
          <div class="agent-avatar">
            <img src="/assets/img/real-estate/agent-4.webp" alt="Agent">
          </div>
          <div class="agent-details">
            <strong>Robert Thompson</strong>
            <span>Urban Living Realty</span>
          </div>
        </a>
        <div class="agent-contact">
          <a href="tel:+15552345678" class="contact-btn">
            <i class="bi bi-telephone"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Repeat for 4 more property cards, using the HTML from properties.html for each -->
<!-- ... -->
