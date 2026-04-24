<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - {{ config('app.name') }} Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <!-- Favicons -->
  <link href="{{ asset('frontend/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('frontend/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <!-- Main CSS File -->
  <link href="{{ asset('frontend/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: {{ config('app.name') }}
  * Template URL: https://bootstrapmade.com/indianesthub-bootstrap-real-estate-template/
  * Updated: Jul 05 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  @include('frontend.partials.header')

  <main class="main">

    <!-- Hero Section -->
    @include('frontend.partials.hero')    

    <!-- Home About Section -->
    @include('frontend.partials.home-about')

    <!-- Featured Properties Section -->

    @include('frontend.partials.featured-properties')
    <!-- Featured Services Section -->
    @include('frontend.partials.featured-services')

    <!-- Featured Agents Section -->
    @include('frontend.partials.featured-agents')

    <!-- Testimonials Section -->
    @include('frontend.partials.testimonials')

    <!-- Why Us Section -->
    @include('frontend.partials.why-us')

    <!-- Call To Action Section -->
    @include('frontend.partials.call-to-us')

    <!-- Recent Blog Posts Section -->
    @include('frontend.partials.blog')

  </main>

  @include('frontend.partials.footer')


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('frontend/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('frontend/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>
<!-- Main JS File -->
<script src="{{ asset('frontend/js/main.js') }}"></script>

</body>

</html>