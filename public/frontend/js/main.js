(function() {
  // Save (Watchlist) Button AJAX
  const saveBtn = document.getElementById('save-property-btn');
  if (saveBtn) {
    const propertyId = saveBtn.getAttribute('data-property-id');
    const icon = document.getElementById('save-property-icon');
    const text = document.getElementById('save-property-text');
    const messageBox = document.getElementById('save-property-message');
    // Check initial state
    fetch(`/watchlist/is-saved?property_id=${propertyId}`)
      .then(res => res.json())
      .then(data => {
        if (data.saved) {
          icon.classList.add('text-danger');
          text.textContent = 'Saved';
        } else {
          icon.classList.remove('text-danger');
          text.textContent = 'Save';
        }
      });
    saveBtn.addEventListener('click', function(e) {
      e.preventDefault();
      let csrfToken = '';
      const meta = document.querySelector('meta[name="csrf-token"]');
      if (meta) {
        csrfToken = meta.getAttribute('content');
      } else {
        // fallback: try to get from hidden input if present
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) csrfToken = tokenInput.value;
      }
      console.log('CSRF Token used for AJAX:', csrfToken);
      fetch('/watchlist/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ property_id: propertyId })
      })
      .then(async res => {
        let data;
        try { data = await res.json(); } catch (e) { data = {}; }
        if (res.status === 401) {
          messageBox.innerHTML = '<div class="alert alert-warning">You must be logged in to save properties.</div>';
        } else if (res.status === 419) {
          messageBox.innerHTML = '<div class="alert alert-danger">Session expired. Please refresh and try again.</div>';
        } else if (data.status === 'added') {
          icon.classList.add('text-danger');
          text.textContent = 'Saved';
          messageBox.innerHTML = '<div class="alert alert-success">Property saved to your watchlist.</div>';
        } else if (data.status === 'removed') {
          icon.classList.remove('text-danger');
          text.textContent = 'Save';
          messageBox.innerHTML = '<div class="alert alert-info">Property removed from your watchlist.</div>';
        } else if (data.error) {
          messageBox.innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
        } else {
          messageBox.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        }
      })
      .catch(() => {
        messageBox.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
      });
    });
  }

  // Share Button
  const shareBtn = document.getElementById('share-property-btn');
  if (shareBtn) {
    shareBtn.addEventListener('click', function() {
      const url = window.location.href;
      if (navigator.share) {
        navigator.share({
          title: document.title,
          url: url
        });
      } else {
        navigator.clipboard.writeText(url).then(function() {
          alert('Property link copied to clipboard!');
        }, function() {
          alert('Failed to copy link.');
        });
      }
    });
  }
})();
(function() {
  // AJAX Inquiry Form Submission
  const inquiryForm = document.getElementById('inquiry-form');
  if (inquiryForm) {
    inquiryForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(inquiryForm);
      const submitBtn = inquiryForm.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      let messageBox = inquiryForm.querySelector('.ajax-message');
      if (!messageBox) {
        messageBox = document.createElement('div');
        messageBox.className = 'ajax-message my-2';
        inquiryForm.prepend(messageBox);
      }
      messageBox.innerHTML = '';
      fetch(inquiryForm.getAttribute('action'), {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': formData.get('_token')
        },
        body: formData
      })
      .then(async response => {
        submitBtn.disabled = false;
        if (response.ok) {
          messageBox.innerHTML = '<div class="alert alert-success">Your inquiry has been sent successfully!</div>';
          inquiryForm.reset();
        } else if (response.status === 422) {
          const data = await response.json();
          let errors = data.errors || {};
          let errorHtml = '<div class="alert alert-danger"><ul>';
          Object.values(errors).forEach(errArr => {
            errArr.forEach(err => { errorHtml += '<li>' + err + '</li>'; });
          });
          errorHtml += '</ul></div>';
          messageBox.innerHTML = errorHtml;
        } else {
          messageBox.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        }
      })
      .catch(() => {
        submitBtn.disabled = false;
        messageBox.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
      });
    });
  }
})();
/**
* Template Name: IndianestHub
* Template URL: https://bootstrapmade.com/indianesthub-bootstrap-real-estate-template/
* Updated: Jul 05 2025 with Bootstrap v5.3.7
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

})();