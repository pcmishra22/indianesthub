<title>{{ $property->meta_title ?? $property->title }}</title>
<meta name="description" content="{{ $property->meta_description ?? $property->description }}">
<!-- Open Graph Tags -->
<meta property="og:title" content="{{ $property->meta_title ?? $property->title }}">
<meta property="og:description" content="{{ $property->meta_description ?? $property->description }}">
<meta property="og:url" content="{{ url('/properties/' . $property->id) }}">
<meta property="og:image" content="{{ $property->image_url ?? '' }}">
<!-- Structured Data -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ $property->title }}",
  "description": "{{ $property->description }}",
  "image": "{{ $property->image_url ?? '' }}",
  "offers": {
    "@type": "Offer",
    "price": "{{ $property->price }}",
    "priceCurrency": "INR"
  }
}
</script>
<!-- Marketing Pixels -->
<script>
  // Facebook Pixel
  !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
  n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
  document,'script','https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', 'YOUR_PIXEL_ID');
  fbq('track', 'PageView');
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=YOUR_GA_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'YOUR_GA_ID');
</script>