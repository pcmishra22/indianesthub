<?php
// SEO Marketing (public)
Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SeoController::class, 'sitemap'])->name('seo.sitemap');
// Analytics Reporting (public)
Route::get('/analytics', [\App\Http\Controllers\Frontend\AnalyticsController::class, 'index'])->name('analytics.index');
// Chat Messaging (public)
Route::get('/chat', [\App\Http\Controllers\Frontend\ChatController::class, 'index'])->name('chat.index');
// Payment & Billing (public)
Route::get('/wallet', [\App\Http\Controllers\Frontend\WalletController::class, 'index'])->name('wallet.index');
// Notifications (public)
Route::get('/notifications', [\App\Http\Controllers\Frontend\NotificationsController::class, 'index'])->name('notifications.index');
// Ratings & Reviews (public)
Route::get('/reviews', [\App\Http\Controllers\Frontend\ReviewController::class, 'index'])->name('reviews.index');
// Security Compliance (public)
Route::get('/privacy-policy', [\App\Http\Controllers\Frontend\PrivacyController::class, 'policy'])->name('privacy.policy');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailTrackingController;

// ── Email open-tracking pixel (public, no auth required) ─────────────
Route::get('/email/track/{token}', [EmailTrackingController::class, 'pixel'])
    ->name('email.track.pixel');

// Frontend Controllers
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PropertiesController;
use App\Http\Controllers\Frontend\PropertyDetailsController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\AgentsController;
use App\Http\Controllers\Frontend\AgentProfileController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BlogDetailsController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ServicesController;
use App\Http\Controllers\Frontend\ServiceDetailsController;
use App\Http\Controllers\Frontend\PrivacyController;
use App\Http\Controllers\Frontend\TermsController;
use App\Http\Controllers\Frontend\NotFoundController;
use App\Http\Controllers\Frontend\StarterPageController;
use App\Http\Controllers\Frontend\ScheduleViewingController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\CompareController;
use App\Http\Controllers\Frontend\ReviewController;

// Auth Controllers
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\Auth\DealerLoginController;
use App\Http\Controllers\Auth\DealerRegisterController;
use App\Http\Controllers\Auth\AdminLoginController;

// Dealer Controllers
use App\Http\Controllers\Dealer\PropertyController as DealerPropertyController;
use App\Http\Controllers\Dealer\DashboardController as DealerDashboardController;
use App\Http\Controllers\Dealer\InquiryController as DealerInquiryController;
use App\Http\Controllers\Dealer\ScheduleViewingController as DealerScheduleViewingController;
use App\Http\Controllers\Dealer\SubscriptionController;
use App\Http\Controllers\Dealer\ProfileController as DealerProfileController;

// Builder Controllers
use App\Http\Controllers\Builder\AuthController as BuilderAuthController;
use App\Http\Controllers\Builder\DashboardController as BuilderDashboardController;
use App\Http\Controllers\Builder\ProjectController as BuilderProjectController;
use App\Http\Controllers\Builder\PropertyController as BuilderPropertyController;
use App\Http\Controllers\Builder\ProfileController as BuilderProfileController;
use App\Http\Controllers\Builder\LeadsController as BuilderLeadsController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DealerController as AdminDealerController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\LeadReportController as AdminLeadReportController;
use App\Http\Controllers\Admin\BuilderController as AdminBuilderController;
use App\Http\Controllers\Admin\BuilderProjectController as AdminBuilderProjectController;
use App\Http\Controllers\Admin\BuilderLeadController as AdminBuilderLeadController;
use App\Http\Controllers\Admin\PropertyViewersController;

/*
|--------------------------------------------------------------------------
| Frontend Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [\App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
// Search & Filtering Module
Route::get('/search/autocomplete', [\App\Http\Controllers\Frontend\PropertiesController::class, 'autocomplete'])->name('search.autocomplete');
// Two-Factor Authentication
Route::middleware('auth')->group(function () {
    Route::get('/two-factor', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showForm'])->name('2fa.form');
    Route::post('/send-2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'send'])->name('2fa.send');
    Route::post('/verify-2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('2fa.verify');
});
// OTP Phone Verification
Route::middleware('auth')->group(function () {
    Route::get('/verify-phone', [\App\Http\Controllers\Auth\OtpController::class, 'showVerifyForm'])->name('phone.verify.form');
    Route::post('/send-otp', [\App\Http\Controllers\Auth\OtpController::class, 'send'])->name('phone.otp.send');
    Route::post('/verify-otp', [\App\Http\Controllers\Auth\OtpController::class, 'verify'])->name('phone.otp.verify');
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'about'])->name('about');
Route::get('/properties', [PropertiesController::class, 'index'])->name('properties');
// SEO-friendly city search (maps to same filter logic as /properties)
Route::get('/properties-in-{city}', [\App\Http\Controllers\Frontend\SeoLandingController::class, 'propertyListingsInCity'])->name('properties.city');

// Redirect slashed version to hyphenated version for SEO and search box compatibility
Route::get('/properties-in/{city}', function ($city) {
    return redirect()->route('properties.city', ['city' => $city], 301);
});

Route::get('/properties/in/{location}', [PropertiesController::class, 'locationSearch'])->name('properties.location');
Route::get('/properties/{property:slug}', [PropertyDetailsController::class, 'show'])->name('property-details');

/*
|--------------------------------------------------------------------------
| Hyperlocal SEO Landing Pages (Location × Property Type)
|--------------------------------------------------------------------------
| These keyword-targeted pages dominate hyperlocal searches like:
|   "flats in zirakpur", "3bhk flats mohali", "new projects chandigarh"
| Must be defined BEFORE any wildcard routes.
|
| ORDER MATTERS: budget + specific routes first, generic {city} routes last.
*/

// ── Budget-Based Pages (highest specificity — must come first) ─────────
Route::get('/flats-in-{city}-under-{amount}-lakh',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'flatsUnderBudget'])
    ->name('seo.flats.budget')->where('amount', '[0-9]+');

Route::get('/plots-in-{city}-under-{amount}-lakh',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'plotsUnderBudget'])
    ->name('seo.plots.budget')->where('amount', '[0-9]+');

Route::get('/villa-in-{city}-under-{amount}-cr',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'villaUnderBudget'])
    ->name('seo.villa.budget')->where('amount', '[0-9]+');

Route::get('/{bhk}-bhk-flats-in-{city}-under-{amount}-lakh',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkFlatsUnderBudget'])
    ->name('seo.bhk.budget')->where(['bhk' => '[1-5]', 'amount' => '[0-9]+']);

Route::get('/{bhk}-bhk-house-in-{city}-under-{amount}-lakh',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkHouseUnderBudget'])
    ->name('seo.bhk.house.budget')->where(['bhk' => '[1-5]', 'amount' => '[0-9]+']);

Route::get('/{bhk}-bhk-villa-in-{city}-under-{amount}-lakh',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkVillaUnderBudget'])
    ->name('seo.bhk.villa.budget')->where(['bhk' => '[1-5]', 'amount' => '[0-9]+']);

// ── BHK × Property Type × Sale ─────────────────────────────────────────
Route::get('/{bhk}-bhk-house-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkHouseForSaleInCity'])
    ->name('seo.bhk.house.city')->where('bhk', '[1-5]');

Route::get('/{bhk}-bhk-villa-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkVillaForSaleInCity'])
    ->name('seo.bhk.villa.city')->where('bhk', '[1-5]');

Route::get('/{bhk}-bhk-duplex-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkDuplexForSaleInCity'])
    ->name('seo.bhk.duplex.city')->where('bhk', '[1-5]');

// ── Extended Rental Pages ───────────────────────────────────────────────
Route::get('/house-for-rent-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'houseForRentInCity'])
    ->name('seo.house.rent.city');

Route::get('/{bhk}-bhk-flat-for-rent-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkFlatForRentInCity'])
    ->name('seo.bhk.rent.city')->where('bhk', '[1-5]');

Route::get('/{bhk}-bhk-house-for-rent-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkHouseForRentInCity'])
    ->name('seo.bhk.house.rent.city')->where('bhk', '[1-5]');

Route::get('/{bhk}-bhk-villa-for-rent-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkVillaForRentInCity'])
    ->name('seo.bhk.villa.rent.city')->where('bhk', '[1-5]');

Route::get('/commercial-shop-for-rent-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'shopForRentInCity'])
    ->name('seo.shop.rent.city');

// ── Independent House / Duplex ──────────────────────────────────────────
Route::get('/independent-house-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'independentHouseInCity'])
    ->name('seo.house.city');

Route::get('/duplex-house-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'duplexHouseInCity'])
    ->name('seo.duplex.city');

// ── Commercial Properties ───────────────────────────────────────────────
Route::get('/commercial-property-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'commercialInCity'])
    ->name('seo.commercial.city');

Route::get('/shops-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'shopsInCity'])
    ->name('seo.shops.city');

Route::get('/office-space-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'officeSpaceInCity'])
    ->name('seo.office.city');

Route::get('/sco-for-sale-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'scoInCity'])
    ->name('seo.sco.city');

// ── Buyer Intent Pages ──────────────────────────────────────────────────
Route::get('/property-dealers-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'dealersInCity'])
    ->name('seo.dealers.city');

Route::get('/real-estate-agents-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'agentsInCity'])
    ->name('seo.agents.city');

Route::get('/upcoming-projects-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'upcomingProjectsInCity'])
    ->name('seo.upcoming.city');

Route::get('/rera-approved-projects-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'reraProjectsInCity'])
    ->name('seo.rera.city');

Route::get('/investment-property-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'investmentPropertyInCity'])
    ->name('seo.investment.city');

Route::get('/best-residential-projects-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bestResidentialProjectsInCity'])
    ->name('seo.best.projects.city');

// ── Special Filter Pages ────────────────────────────────────────────────
Route::get('/gated-society-flats-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'gatedSocietyFlatsInCity'])
    ->name('seo.gated.city');

Route::get('/luxury-flats-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'luxuryFlatsInCity'])
    ->name('seo.luxury.flats.city');

Route::get('/affordable-flats-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'affordableFlatsInCity'])
    ->name('seo.affordable.city');

Route::get('/resale-flats-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'resaleFlatsInCity'])
    ->name('seo.resale.city');

Route::get('/furnished-flats-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'furnishedFlatsInCity'])
    ->name('seo.furnished.city');

Route::get('/apartments-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'apartmentsInCity'])
    ->name('seo.apartments.city');

Route::get('/property-listings-in-{city}',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'propertyListingsInCity'])
    ->name('seo.listings.city');

Route::get('/{city}-real-estate',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'realEstateInCity'])
    ->name('seo.realestate.city');

Route::get('/flats-in-{city}-with-loan-facility',
    [\App\Http\Controllers\Frontend\SeoLandingController::class, 'loanFacilityFlatsInCity'])
    ->name('seo.loan.facility.city');

// ── Generic city-level pages (catch-all — must be last in this group) ──
Route::get('/flats-in-{city}',          [\App\Http\Controllers\Frontend\SeoLandingController::class, 'flatsInCity'])->name('seo.flats.city');
Route::get('/rent-flats-in-{city}',     [\App\Http\Controllers\Frontend\SeoLandingController::class, 'rentFlatsInCity'])->name('seo.rent.city');
Route::get('/plots-in-{city}',          [\App\Http\Controllers\Frontend\SeoLandingController::class, 'plotsInCity'])->name('seo.plots.city');
Route::get('/villas-in-{city}',         [\App\Http\Controllers\Frontend\SeoLandingController::class, 'villasInCity'])->name('seo.villas.city');
Route::get('/new-projects-in-{city}',   [\App\Http\Controllers\Frontend\SeoLandingController::class, 'newProjectsInCity'])->name('seo.projects.city');
Route::get('/ready-to-move-flats-in-{city}', [\App\Http\Controllers\Frontend\SeoLandingController::class, 'readyToMoveIn'])->name('seo.rtm.city');
Route::get('/{bhk}-bhk-flats-in-{city}', [\App\Http\Controllers\Frontend\SeoLandingController::class, 'bhkFlatsInCity'])
     ->name('seo.bhk.city')
     ->where('bhk', '[1-5]');
Route::get('/agents', [AgentsController::class, 'index'])->name('agents');
Route::get('/agent-profile/{dealer:slug}', [AgentsController::class, 'profile'])->name('agent-profile');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog-details', [BlogDetailsController::class, 'index'])->name('blog-details');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/service-details', [ServiceDetailsController::class, 'index'])->name('service-details');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Loan Lead — public AJAX submit
Route::post('/loan/lead', [\App\Http\Controllers\Frontend\LoanLeadController::class, 'store'])->name('loan.lead.store');

// Insurance Lead — public AJAX submit
Route::post('/insurance/lead', [\App\Http\Controllers\Frontend\InsuranceLeadController::class, 'store'])->name('insurance.lead.store');

// Legal Help Lead — public AJAX submit
Route::post('/legal/lead', [\App\Http\Controllers\Frontend\LegalLeadController::class, 'store'])->name('legal.lead.store');

// ── SEO Service Landing Pages ─────────────────────────────────────────────

// Home Loan
Route::get('/home-loan',                                     [\App\Http\Controllers\Frontend\SeoServiceController::class, 'homeLoan'])->name('seo.loan');
Route::get('/home-loan-in-{city}',                           [\App\Http\Controllers\Frontend\SeoServiceController::class, 'homeLoanInCity'])->name('seo.loan.city');
Route::get('/home-loan-for-salaried-in-{city}',             [\App\Http\Controllers\Frontend\SeoServiceController::class, 'homeLoanSalariedInCity'])->name('seo.loan.salaried.city');
Route::get('/home-loan-for-self-employed-in-{city}',        [\App\Http\Controllers\Frontend\SeoServiceController::class, 'homeLoanSelfEmployedInCity'])->name('seo.loan.selfemployed.city');
Route::get('/home-loan-eligibility-in-{city}',              [\App\Http\Controllers\Frontend\SeoServiceController::class, 'homeLoanEligibilityInCity'])->name('seo.loan.eligibility.city');

// Property Insurance
Route::get('/property-insurance',                            [\App\Http\Controllers\Frontend\SeoServiceController::class, 'propertyInsurance'])->name('seo.insurance');
Route::get('/property-insurance-in-{city}',                 [\App\Http\Controllers\Frontend\SeoServiceController::class, 'propertyInsuranceInCity'])->name('seo.insurance.city');
Route::get('/home-insurance-in-{city}',                     [\App\Http\Controllers\Frontend\SeoServiceController::class, 'homeInsuranceInCity'])->name('seo.home.insurance.city');
Route::get('/home-insurance-for-flat-in-{city}',            [\App\Http\Controllers\Frontend\SeoServiceController::class, 'flatInsuranceInCity'])->name('seo.flat.insurance.city');

// Legal Help — generic + city
Route::get('/property-legal-help',                          [\App\Http\Controllers\Frontend\SeoServiceController::class, 'legalHelp'])->name('seo.legal');
Route::get('/property-legal-help-in-{city}',                [\App\Http\Controllers\Frontend\SeoServiceController::class, 'legalHelpInCity'])->name('seo.legal.city');

// Legal Help — issue type × city
Route::get('/title-verification-in-{city}',                 [\App\Http\Controllers\Frontend\SeoServiceController::class, 'titleVerificationInCity'])->name('seo.legal.title.city');
Route::get('/sale-deed-registration-in-{city}',             [\App\Http\Controllers\Frontend\SeoServiceController::class, 'saleDeedInCity'])->name('seo.legal.saledeed.city');
Route::get('/property-dispute-lawyer-in-{city}',            [\App\Http\Controllers\Frontend\SeoServiceController::class, 'propertyDisputeInCity'])->name('seo.legal.dispute.city');
Route::get('/rental-agreement-in-{city}',                   [\App\Http\Controllers\Frontend\SeoServiceController::class, 'rentalAgreementInCity'])->name('seo.legal.rental.city');
Route::get('/will-registration-in-{city}',                  [\App\Http\Controllers\Frontend\SeoServiceController::class, 'willRegistrationInCity'])->name('seo.legal.will.city');

Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');
Route::get('/404', [NotFoundController::class, 'index'])->name('404');
Route::get('/starter-page', [StarterPageController::class, 'index'])->name('starter-page');

// Subscription public page
Route::get('/subscription', function () {
    return view('frontend.subscription');
})->name('subscription');

// Pricing page
Route::get('/pricing', function () {
    return view('frontend.pricing');
})->name('pricing');

// Property actions (public)
Route::post('/property/inquiry/submit', [PropertyDetailsController::class, 'submitInquiry'])->name('property.inquiry.submit');
Route::post('/property/schedule-viewing', [ScheduleViewingController::class, 'submit'])->name('property.schedule.viewing');

// Property comparison (session-based, no login required)
Route::get('/compare', [CompareController::class, 'index'])->name('compare');
Route::get('/property/compare', [CompareController::class, 'compare'])->name('property.compare');
Route::post('/compare/add', [CompareController::class, 'add'])->name('compare.add');
Route::post('/compare/remove', [CompareController::class, 'remove'])->name('compare.remove');
Route::post('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');

/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/

// User Auth & Password Reset
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserRegisterController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['request' => request(), 'token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.store');
});
Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Dashboard Routes (auth:web)
|--------------------------------------------------------------------------
*/
Route::prefix('my')->middleware('auth')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', function () {
        return view('frontend.user.settings');
    })->name('settings');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
    Route::get('/inquiries', [UserDashboardController::class, 'inquiries'])->name('inquiries');
    Route::get('/recently-viewed', [UserDashboardController::class, 'recentlyViewed'])->name('recently-viewed');
});

// Wishlist AJAX routes (auth:web)
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/is-saved/{property}', [WishlistController::class, 'isSaved'])->name('wishlist.is-saved');
    // Reviews
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
});

/*
|--------------------------------------------------------------------------
| Dealer Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dealer')->name('dealer.')->group(function () {
    Route::get('/login', [DealerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DealerLoginController::class, 'login']);
    Route::post('/logout', [DealerLoginController::class, 'logout'])->name('logout');
    Route::get('/register', [DealerRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [DealerRegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Dealer Dashboard Routes (auth:dealer)
|--------------------------------------------------------------------------
*/
Route::prefix('dealer')->middleware('auth:dealer')->name('dealer.')->group(function () {
    Route::get('/dashboard', [DealerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [DealerProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [DealerProfileController::class, 'update'])->name('profile.update');

    // Properties CRUD
    Route::resource('properties', DealerPropertyController::class);
    Route::post('/properties/{property}/pay', [DealerPropertyController::class, 'payProperty'])->name('properties.pay');
    // Bulk CSV upload
    Route::get('/properties/upload-csv', [DealerPropertyController::class, 'showCsvUploadForm'])->name('properties.uploadCsvForm');
        Route::post('/properties/upload-csv', [DealerPropertyController::class, 'uploadCsv'])->name('properties.uploadCsv');

    // Inquiries
    Route::get('/inquiries', [DealerInquiryController::class, 'index'])->name('inquiries.index');

    // Schedule Viewings
    Route::get('/schedule-viewings', [DealerScheduleViewingController::class, 'index'])->name('schedule-viewings.index');

    // Subscription & Payments
    Route::get('/subscription', function () {
        return view('dealer.subscription');
    })->name('subscription');
    Route::get('/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::post('/subscription/payment/{payment}/mark-paid', [SubscriptionController::class, 'markPaid'])->name('subscription.payment.markPaid');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes (auth:admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manage Users
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'destroy']);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Manage Dealers
    Route::resource('dealers', AdminDealerController::class);
    Route::post('/dealers/{dealer}/toggle-status', [AdminDealerController::class, 'toggleStatus'])->name('dealers.toggle-status');

    // Manage Properties
    Route::resource('properties', AdminPropertyController::class)->only(['index', 'show', 'destroy']);
Route::post('/properties/{property}/toggle-featured', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');

require __DIR__ . '/admin_properties.php';


    // Property viewers (guest token based tracking)
        Route::get('/properties/{property}/viewers', [\App\Http\Controllers\Admin\PropertyViewersController::class, 'index'])
        ->name('properties.viewers.index');

    // Manage Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');

    // Manage Blog
    Route::resource('blog', AdminBlogController::class);

    // Manage Reviews
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject',  [AdminReviewController::class, 'reject'])->name('reviews.reject');

    // Schedule Viewings
    Route::get('/schedule-viewings',                         [\App\Http\Controllers\Admin\ScheduleViewingController::class, 'index'])->name('schedule-viewings.index');
    Route::get('/schedule-viewings/{scheduleViewing}',       [\App\Http\Controllers\Admin\ScheduleViewingController::class, 'show'])->name('schedule-viewings.show');
    Route::post('/schedule-viewings/{scheduleViewing}/status',[\App\Http\Controllers\Admin\ScheduleViewingController::class, 'updateStatus'])->name('schedule-viewings.update-status');
    Route::delete('/schedule-viewings/{scheduleViewing}',    [\App\Http\Controllers\Admin\ScheduleViewingController::class, 'destroy'])->name('schedule-viewings.destroy');

    // Subscriptions
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class);

    // Site Settings
    Route::get('/settings',      [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings',      [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Manage Inquiries & Contacts
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{contact}/mark-read', [AdminContactController::class, 'markRead'])->name('contacts.mark-read');
    
        // Reports
        Route::get('/reports/expiring-tomorrow', [\App\Http\Controllers\Admin\ReportController::class, 'expiringTomorrow'])->name('reports.expiring-tomorrow');
        Route::get('/reports/expiring-in-a-week', [\App\Http\Controllers\Admin\ReportController::class, 'expiringInAWeek'])->name('reports.expiring-in-a-week');

        // Leads & Analytics Report
        Route::get('/leads-report', [AdminLeadReportController::class, 'index'])->name('leads-report.index');

        // Builders
        Route::resource('builders', AdminBuilderController::class)->only(['index', 'show', 'destroy']);
        Route::post('/builders/{builder}/toggle-status',   [AdminBuilderController::class, 'toggleStatus'])->name('builders.toggle-status');
        Route::post('/builders/{builder}/toggle-verified', [AdminBuilderController::class, 'toggleVerified'])->name('builders.toggle-verified');

        // Builder Projects
        Route::resource('builder-projects', AdminBuilderProjectController::class)->only(['index', 'show', 'destroy']);
        Route::post('/builder-projects/{project}/toggle-featured', [AdminBuilderProjectController::class, 'toggleFeatured'])->name('builder-projects.toggle-featured');

        // Builder Leads
        Route::resource('builder-leads', AdminBuilderLeadController::class)->only(['index', 'show', 'destroy']);
        Route::post('/builder-leads/{lead}/update-status', [AdminBuilderLeadController::class, 'updateStatus'])->name('builder-leads.update-status');

        // Loan Leads
        Route::get('/loan-leads',                          [\App\Http\Controllers\Admin\LoanLeadController::class, 'index'])->name('loan-leads.index');
        Route::get('/loan-leads/export',                   [\App\Http\Controllers\Admin\LoanLeadController::class, 'export'])->name('loan-leads.export');
        Route::get('/loan-leads/{lead}',                   [\App\Http\Controllers\Admin\LoanLeadController::class, 'show'])->name('loan-leads.show');
        Route::post('/loan-leads/{lead}/status',           [\App\Http\Controllers\Admin\LoanLeadController::class, 'updateStatus'])->name('loan-leads.update-status');
        Route::delete('/loan-leads/{lead}',                [\App\Http\Controllers\Admin\LoanLeadController::class, 'destroy'])->name('loan-leads.destroy');

        // Insurance Leads
        Route::get('/insurance-leads',                     [\App\Http\Controllers\Admin\InsuranceLeadController::class, 'index'])->name('insurance-leads.index');
        Route::get('/insurance-leads/export',              [\App\Http\Controllers\Admin\InsuranceLeadController::class, 'export'])->name('insurance-leads.export');
        Route::get('/insurance-leads/{lead}',              [\App\Http\Controllers\Admin\InsuranceLeadController::class, 'show'])->name('insurance-leads.show');
        Route::post('/insurance-leads/{lead}/status',      [\App\Http\Controllers\Admin\InsuranceLeadController::class, 'updateStatus'])->name('insurance-leads.update-status');
        Route::delete('/insurance-leads/{lead}',           [\App\Http\Controllers\Admin\InsuranceLeadController::class, 'destroy'])->name('insurance-leads.destroy');

        // Legal Help Leads
        Route::get('/legal-leads',                         [\App\Http\Controllers\Admin\LegalLeadController::class, 'index'])->name('legal-leads.index');
        Route::get('/legal-leads/export',                  [\App\Http\Controllers\Admin\LegalLeadController::class, 'export'])->name('legal-leads.export');
        Route::get('/legal-leads/{lead}',                  [\App\Http\Controllers\Admin\LegalLeadController::class, 'show'])->name('legal-leads.show');
        Route::post('/legal-leads/{lead}/status',          [\App\Http\Controllers\Admin\LegalLeadController::class, 'updateStatus'])->name('legal-leads.update-status');
        Route::delete('/legal-leads/{lead}',               [\App\Http\Controllers\Admin\LegalLeadController::class, 'destroy'])->name('legal-leads.destroy');

        // FAQs
        Route::resource('faqs', \App\Http\Controllers\Admin\FAQController::class)->except(['show']);

        // Banners
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Builder Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('builder')->name('builder.')->group(function () {
    Route::get('/login',     [BuilderAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [BuilderAuthController::class, 'login']);
    Route::post('/logout',   [BuilderAuthController::class, 'logout'])->name('logout');
    Route::get('/register',  [BuilderAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [BuilderAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Builder Dashboard Routes (auth:builder)
|--------------------------------------------------------------------------
*/
Route::prefix('builder')->middleware('auth:builder')->name('builder.')->group(function () {
    Route::get('/dashboard', [BuilderDashboardController::class, 'index'])->name('dashboard');

    // Projects CRUD
    Route::resource('projects', BuilderProjectController::class);

    // Properties nested under a project
    Route::prefix('projects/{project}/properties')->name('projects.properties.')->group(function () {
        Route::get('/',            [BuilderPropertyController::class, 'index'])->name('index');
        Route::get('/create',      [BuilderPropertyController::class, 'create'])->name('create');
        Route::post('/',           [BuilderPropertyController::class, 'store'])->name('store');
        Route::get('/{property}/edit',   [BuilderPropertyController::class, 'edit'])->name('edit');
        Route::put('/{property}',        [BuilderPropertyController::class, 'update'])->name('update');
        Route::delete('/{property}',     [BuilderPropertyController::class, 'destroy'])->name('destroy');
    });

    // Profile
    Route::get('/profile',  [BuilderProfileController::class, 'show'])->name('profile');
    Route::put('/profile',  [BuilderProfileController::class, 'update'])->name('profile.update');

    // Leads Management
    Route::get('/leads',                         [BuilderLeadsController::class, 'index'])->name('leads.index');
    Route::patch('/leads/{lead}/status',         [BuilderLeadsController::class, 'updateStatus'])->name('leads.update-status');
    Route::delete('/leads/{lead}',               [BuilderLeadsController::class, 'destroy'])->name('leads.destroy');
});

// Virtual Tours AR (public)
Route::get('/virtual-tour', [\App\Http\Controllers\Frontend\VirtualTourController::class, 'index'])->name('virtual-tour.index');

// AI Property Recommendations (public)
Route::get('/recommendations', [\App\Http\Controllers\Frontend\RecommendationController::class, 'index'])->name('recommendations.index');

// Chatbot for Leads (public)
Route::get('/chatbot', [\App\Http\Controllers\Frontend\ChatbotController::class, 'index'])->name('chatbot.index');

// Property Market Insights (public)
Route::get('/market-insights', [\App\Http\Controllers\Frontend\MarketInsightsController::class, 'index'])->name('market-insights.index');

// Dynamic Price Trends Chart (public)
Route::get('/price-trends', [\App\Http\Controllers\Frontend\PriceTrendsController::class, 'index'])->name('price-trends.index');

// Dealer Property Listing (public)
Route::get('/dealer/{dealer}/properties', [\App\Http\Controllers\Frontend\DealerPropertyController::class, 'index'])->name('frontend.dealer.properties');

/*
|--------------------------------------------------------------------------
| Public Builder / Project Pages
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\BuilderController;

Route::get('/builders',              [BuilderController::class, 'index'])->name('builders.index');
Route::get('/builders/{builder}',    [BuilderController::class, 'show'])->name('builders.show');
Route::get('/projects/{project}',    [BuilderController::class, 'projectDetail'])->name('projects.show');
Route::post('/projects/{project}/lead', [BuilderController::class, 'submitLead'])->name('projects.lead');
