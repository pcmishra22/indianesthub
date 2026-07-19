<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
            <span class="align-middle">AdminKit</span>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-header">Pages</li>
            <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">All Accounts</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.users.bulk-email.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users.bulk-email.index') }}">
                    <i class="align-middle" data-feather="mail"></i> <span class="align-middle">User Bulk Email</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.dealers.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dealers.index') }}">
                    <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Dealers</span>
                </a>
            </li>

            {{-- Service Providers Section --}}
            <li class="sidebar-item {{ request()->routeIs('admin.service-providers.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.service-providers.index') }}">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">Service Providers</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.services.index') }}">
                    <i class="align-middle" data-feather="tool"></i> <span class="align-middle">Services</span>
                </a>
            </li>

            {{-- Builder Section --}}
            <li class="sidebar-header">Builders</li>
            <li class="sidebar-item {{ request()->routeIs('admin.builders.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.builders.index') }}">
                    <i class="align-middle" data-feather="layers"></i> <span class="align-middle">Builders</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.builder-projects.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.builder-projects.index') }}">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Projects</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.builder-leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.builder-leads.index') }}">
                    <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Builder Leads</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.city-import.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.city-import.create') }}">
                    <i class="align-middle" data-feather="download-cloud"></i> <span class="align-middle">City Data Import</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.loan-leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.loan-leads.index') }}">
                    <i class="align-middle" data-feather="dollar-sign"></i>
                    <span class="align-middle">Loan Leads</span>
                    @php $newLoanLeads = \App\Models\LoanLead::where('status','new')->count(); @endphp
                    @if($newLoanLeads > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:.65rem;">{{ $newLoanLeads }}</span>
                    @endif
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.insurance-leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.insurance-leads.index') }}">
                    <i class="align-middle" data-feather="shield"></i>
                    <span class="align-middle">Insurance Leads</span>
                    @php $newInsuranceLeads = \App\Models\InsuranceLead::where('status','new')->count(); @endphp
                    @if($newInsuranceLeads > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:.65rem;">{{ $newInsuranceLeads }}</span>
                    @endif
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.legal-leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.legal-leads.index') }}">
                    <i class="align-middle" data-feather="book-open"></i>
                    <span class="align-middle">Legal Help Leads</span>
                    @php $newLegalLeads = \App\Models\LegalLead::where('status','new')->count(); @endphp
                    @if($newLegalLeads > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:.65rem;">{{ $newLegalLeads }}</span>
                    @endif
                </a>
            </li>
            {{-- Dealer Management --}}
            <li class="sidebar-header">Dealer Management</li>
            <li class="sidebar-item {{ request()->routeIs('admin.dealers.bulk-email.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dealers.bulk-email.index') }}">
                    <i class="align-middle" data-feather="mail"></i> <span class="align-middle">Dealer Bulk Email</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">

                <a class="sidebar-link" href="{{ route('admin.subscriptions.index') }}">
                    <i class="align-middle" data-feather="crown"></i>
                    <span class="align-middle">Subscriptions</span>
                    @php $expiringSoon = \App\Models\Subscription::where('status','active')->where('end_date','<=',now()->addDays(7))->count(); @endphp
                    @if($expiringSoon > 0)
                        <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size:.65rem;">{{ $expiringSoon }}</span>
                    @endif
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.schedule-viewings.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.schedule-viewings.index') }}">
                    <i class="align-middle" data-feather="calendar"></i>
                    <span class="align-middle">Schedule Viewings</span>
                    @php $pendingViewings = \App\Models\ScheduleViewing::where('status','pending')->count(); @endphp
                    @if($pendingViewings > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:.65rem;">{{ $pendingViewings }}</span>
                    @endif
                </a>
            </li>

            <li class="sidebar-header">More</li>

            <li class="sidebar-item {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.properties.index') }}">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Properties</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.payments.index') }}">
                    <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Payments</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.blog.index') }}">
                    <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Blog</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reviews.index') }}">
                    <i class="align-middle" data-feather="star"></i>
                    <span class="align-middle">Reviews</span>
                    @php $pendingReviews = \App\Models\Review::where('status','pending')->count(); @endphp
                    @if($pendingReviews > 0)
                        <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size:.65rem;">{{ $pendingReviews }}</span>
                    @endif
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.reports.expiring-tomorrow') || request()->routeIs('admin.reports.expiring-in-a-week') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reports.expiring-tomorrow') }}">
                    <i class="align-middle" data-feather="alert-triangle"></i> <span class="align-middle">Report</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.inquiries.index') }}">
                    <i class="align-middle" data-feather="mail"></i> <span class="align-middle">Inquiries</span>
                </a>
            </li>

            {{-- Home Marketplace --}}
            <li class="sidebar-header">Home Marketplace</li>
            <li class="sidebar-item {{ request()->routeIs('admin.marketplace.leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.marketplace.leads.index') }}">
                    <i class="align-middle" data-feather="shopping-bag"></i>
                    <span class="align-middle">Marketplace Leads</span>
                    @php $newMktLeads = \App\Models\MarketplaceLead::where('status','new')->count(); @endphp
                    @if($newMktLeads > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:.65rem;">{{ $newMktLeads }}</span>
                    @endif
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.marketplace.categories.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.marketplace.categories.index') }}">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Categories</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.marketplace.vendors.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.marketplace.vendors.index') }}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Vendors</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.marketplace.products.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.marketplace.products.index') }}">
                    <i class="align-middle" data-feather="package"></i> <span class="align-middle">Products</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.contacts.index') }}">
                    <i class="align-middle" data-feather="phone"></i> <span class="align-middle">Contacts</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.leads-report.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.leads-report.index') }}">
                    <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Leads &amp; Analytics</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.faqs.index') }}">
                    <i class="align-middle" data-feather="help-circle"></i> <span class="align-middle">FAQs</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.banners.index') }}">
                    <i class="align-middle" data-feather="image"></i> <span class="align-middle">Banners</span>
                </a>
            </li>

            {{-- System --}}
            <li class="sidebar-header">System</li>
            <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.index') }}">
                    <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Site Settings</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
