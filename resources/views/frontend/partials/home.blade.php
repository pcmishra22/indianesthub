<!-- {{ config('app.name') }} Theme Main Banner -->
<section class="hero-section" style="background: url('{{ asset('frontend/img/banner.jpg') }}') no-repeat center center; background-size: cover;">
    <div class="container text-center py-5 text-white">
        <h1 class="display-4 fw-bold mb-3">Find Your Dream Home</h1>
        <p class="lead mb-4">Search from thousands of properties, connect with trusted agents, and make your next move with confidence.</p>
        <form class="d-flex justify-content-center mb-4" action="/properties" method="get">
            <input type="text" name="search" class="form-control w-50" placeholder="Search by location, property type...">
            <button type="submit" class="btn btn-primary ms-2">Search</button>
        </form>
        <a href="/properties" class="btn btn-light btn-lg">Browse All Properties</a>
    </div>
</section>

<!-- Featured Properties Section -->
<section class="featured-properties py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Featured Properties</h2>
        <div class="row">
            <!-- Example property cards, replace with dynamic content -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('frontend/img/property1.jpg') }}" class="card-img-top" alt="Property 1">
                    <div class="card-body">
                        <h5 class="card-title">Luxury Apartment in City Center</h5>
                        <p class="card-text">2 BHK | 1200 sqft | $250,000</p>
                        <a href="{{ route('properties') }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('frontend/img/property2.jpg') }}" class="card-img-top" alt="Property 2">
                    <div class="card-body">
                        <h5 class="card-title">Spacious Villa with Garden</h5>
                        <p class="card-text">4 BHK | 3500 sqft | $650,000</p>
                        <a href="{{ route('properties') }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('frontend/img/property3.jpg') }}" class="card-img-top" alt="Property 3">
                    <div class="card-body">
                        <h5 class="card-title">Modern Studio Apartment</h5>
                        <p class="card-text">1 BHK | 600 sqft | $120,000</p>
                        <a href="{{ route('properties') }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="mb-3">Are you a dealer or agent?</h2>
        <p class="mb-4">List your properties and reach thousands of buyers instantly.</p>
        <a href="/dealer/login" class="btn btn-light btn-lg me-2">Dealer Login</a>
        <a href="/admin/login" class="btn btn-outline-light btn-lg">Admin Login</a>
    </div>
</section>
