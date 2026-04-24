<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * SEO-friendly landing pages for:
 *   - Home Loan        (/home-loan, /home-loan-in-{city}, ...)
 *   - Property Insurance (/property-insurance, /property-insurance-in-{city}, ...)
 *   - Legal Help       (/property-legal-help, /property-legal-help-in-{city},
 *                       /title-verification-in-{city}, /sale-deed-registration-in-{city}, ...)
 */
class SeoServiceController extends Controller
{
    // ── City data ─────────────────────────────────────────────────────────

    private function cities(): array
    {
        return [
            'chandigarh' => 'Chandigarh',
            'mohali'     => 'Mohali',
            'zirakpur'   => 'Zirakpur',
            'panchkula'  => 'Panchkula',
            'kharar'     => 'Kharar',
            'derabassi'  => 'Derabassi',
            'mullanpur'  => 'Mullanpur',
            'ambala'     => 'Ambala',
            'patiala'    => 'Patiala',
            'rajpura'    => 'Rajpura',
            'ropar'      => 'Ropar',
            'baddi'      => 'Baddi',
            'solan'      => 'Solan',
            'nalagarh'   => 'Nalagarh',
            'morinda'    => 'Morinda',
            'kurali'     => 'Kurali',
        ];
    }

    private function resolveCity(string $slug): ?string
    {
        $cities = $this->cities();
        return $cities[$slug] ?? null;
    }

    // ── Render helper ─────────────────────────────────────────────────────

    private function render(
        string $serviceType,   // 'loan' | 'insurance' | 'legal'
        string $h1,
        string $seoTitle,
        string $seoDesc,
        array  $faqs,
        array  $pageData = [],
        ?string $cityLabel = null,
        ?string $citySlug  = null
    ) {
        $appName   = config('app.name');
        $cities    = $this->cities();

        return view('frontend.seo-service', array_merge([
            'serviceType' => $serviceType,
            'h1'          => $h1,
            'seoTitle'    => $seoTitle,
            'seoDesc'     => $seoDesc,
            'faqs'        => $faqs,
            'cityLabel'   => $cityLabel,
            'citySlug'    => $citySlug,
            'cities'      => $cities,
            'appName'     => $appName,
        ], $pageData));
    }

    // ════════════════════════════════════════════════════════════════════
    //  HOME LOAN PAGES
    // ════════════════════════════════════════════════════════════════════

    /** GET /home-loan */
    public function homeLoan()
    {
        $appName = config('app.name');
        return $this->render('loan',
            'Home Loan in India | Best Interest Rates & Fast Approval',
            "Home Loan | Compare Banks & Get Best Rate | {$appName}",
            "Apply for a home loan in India with the lowest interest rates. Compare top banks, check eligibility, and get approved fast. Free assistance by {$appName}.",
            $this->loanFaqs('your city'),
            ['subTitle' => 'Check eligibility, compare 20+ banks & get approved in 48 hrs — free assistance']
        );
    }

    /** GET /home-loan-in-{city} */
    public function homeLoanInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('loan',
            "Home Loan in {$label} | Best Interest Rates 2026",
            "Home Loan in {$label} | Compare Banks | {$appName}",
            "Get the best home loan in {$label}. Compare 20+ banks, check your eligibility online, and get a loan approved in 48 hrs. Free expert assistance by {$appName}.",
            $this->loanFaqs($label),
            ['subTitle' => "Compare 20+ banks · Check eligibility · Get approved in 48 hrs — free in {$label}"],
            $label, $city
        );
    }

    /** GET /home-loan-for-salaried-in-{city} */
    public function homeLoanSalariedInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('loan',
            "Home Loan for Salaried in {$label} | Best Rates 2026",
            "Salaried Home Loan in {$label} | {$appName}",
            "Salaried employees in {$label} can get home loans starting from 8.35% p.a. Check eligibility and get free assistance from {$appName} experts.",
            $this->loanFaqs($label, 'salaried'),
            ['subTitle' => "Salaried employees · From 8.35% p.a. · Up to 90% funding in {$label}", 'employmentType' => 'salaried'],
            $label, $city
        );
    }

    /** GET /home-loan-for-self-employed-in-{city} */
    public function homeLoanSelfEmployedInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('loan',
            "Home Loan for Self-Employed in {$label} | Best Rates 2026",
            "Self-Employed Home Loan in {$label} | {$appName}",
            "Self-employed individuals in {$label} can get home loans from leading banks. No salary slip required. Check eligibility now with {$appName}.",
            $this->loanFaqs($label, 'self-employed'),
            ['subTitle' => "Self-employed · Business owners · ITR-based loans available in {$label}", 'employmentType' => 'self-employed'],
            $label, $city
        );
    }

    /** GET /home-loan-eligibility-in-{city} */
    public function homeLoanEligibilityInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('loan',
            "Home Loan Eligibility in {$label} | Check Instantly 2026",
            "Home Loan Eligibility Calculator {$label} | {$appName}",
            "Check your home loan eligibility in {$label} instantly. Know how much loan you qualify for based on your income. Free calculator and expert advice by {$appName}.",
            $this->loanFaqs($label),
            ['subTitle' => "Instant eligibility check · Based on income · No documents needed in {$label}"],
            $label, $city
        );
    }

    // ════════════════════════════════════════════════════════════════════
    //  PROPERTY INSURANCE PAGES
    // ════════════════════════════════════════════════════════════════════

    /** GET /property-insurance */
    public function propertyInsurance()
    {
        $appName = config('app.name');
        return $this->render('insurance',
            'Property Insurance in India | Compare 10+ Insurers Free',
            "Property Insurance | Best Home Insurance Plans | {$appName}",
            "Compare property insurance from 10+ top insurers in India. Get the best home insurance quote for your property. 100% free comparison by {$appName}.",
            $this->insuranceFaqs('your city'),
            ['subTitle' => 'Compare HDFC ERGO, Bajaj Allianz, Tata AIG & more — free quote in minutes']
        );
    }

    /** GET /property-insurance-in-{city} */
    public function propertyInsuranceInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('insurance',
            "Property Insurance in {$label} | Compare 10+ Insurers 2026",
            "Property Insurance in {$label} | Best Rates | {$appName}",
            "Get the best property insurance in {$label}. Compare HDFC ERGO, Bajaj Allianz, Tata AIG & 7 more insurers. Free quote and expert guidance by {$appName}.",
            $this->insuranceFaqs($label),
            ['subTitle' => "Compare 10+ insurers · From ₹2,000/yr · IRDAI regulated · Free quote in {$label}"],
            $label, $city
        );
    }

    /** GET /home-insurance-in-{city} */
    public function homeInsuranceInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('insurance',
            "Home Insurance in {$label} | Protect Your Home 2026",
            "Home Insurance in {$label} | Compare Plans | {$appName}",
            "Protect your home in {$label} with the best home insurance plans. Covers structure, contents, fire & theft. Compare and save with {$appName}.",
            $this->insuranceFaqs($label),
            ['subTitle' => "Home structure · Contents · Fire & flood protection in {$label} from ₹2,000/yr"],
            $label, $city
        );
    }

    /** GET /home-insurance-for-flat-in-{city} */
    public function flatInsuranceInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('insurance',
            "Flat Insurance in {$label} | Apartment Insurance 2026",
            "Flat / Apartment Insurance in {$label} | {$appName}",
            "Get flat and apartment insurance in {$label}. Covers structure, contents, fire, and water damage. Compare the best plans and get a free quote from {$appName}.",
            $this->insuranceFaqs($label),
            ['subTitle' => "Flat & apartment coverage · Contents + structure · Compare plans in {$label}"],
            $label, $city
        );
    }

    // ════════════════════════════════════════════════════════════════════
    //  LEGAL HELP PAGES
    // ════════════════════════════════════════════════════════════════════

    /** GET /property-legal-help */
    public function legalHelp()
    {
        $appName = config('app.name');
        return $this->render('legal',
            'Property Legal Help in India | Free Consultation',
            "Property Legal Help | Expert Lawyers | {$appName}",
            "Get expert legal help for your property — title verification, sale deed, disputes, rental agreements & more. Free first consultation via {$appName}.",
            $this->legalFaqs('your city'),
            ['subTitle' => 'Title verification · Sale deed · Disputes · Will & succession — free first consultation', 'issueType' => 'other']
        );
    }

    /** GET /property-legal-help-in-{city} */
    public function legalHelpInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('legal',
            "Property Legal Help in {$label} | Free Consultation 2026",
            "Property Legal Help in {$label} | Expert Lawyers | {$appName}",
            "Get expert property legal help in {$label} — title verification, sale deed registration, disputes, rental agreements & more. Free first consultation via {$appName}.",
            $this->legalFaqs($label),
            ['subTitle' => "Expert property lawyers in {$label} · Free first consultation · 100% confidential", 'issueType' => 'other'],
            $label, $city
        );
    }

    /** GET /title-verification-in-{city} */
    public function titleVerificationInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('legal',
            "Property Title Verification in {$label} | Check Ownership 2026",
            "Title Verification in {$label} | Property Lawyers | {$appName}",
            "Get property title verification done in {$label} by expert lawyers. Check ownership, encumbrances, and legal status before buying. Free first consultation via {$appName}.",
            $this->legalFaqs($label, 'title_verification'),
            ['subTitle' => "Check ownership · Encumbrances · Legal status before buying in {$label}", 'issueType' => 'title_verification'],
            $label, $city
        );
    }

    /** GET /sale-deed-registration-in-{city} */
    public function saleDeedInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('legal',
            "Sale Deed Registration in {$label} | Property Registry 2026",
            "Sale Deed Registration in {$label} | Property Lawyers | {$appName}",
            "Get your sale deed drafted and registered in {$label} with expert legal assistance. Hassle-free property registration and stamp duty guidance by {$appName}.",
            $this->legalFaqs($label, 'sale_deed'),
            ['subTitle' => "Sale deed drafting · Stamp duty · Registry assistance in {$label}", 'issueType' => 'sale_deed'],
            $label, $city
        );
    }

    /** GET /property-dispute-lawyer-in-{city} */
    public function propertyDisputeInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('legal',
            "Property Dispute Lawyer in {$label} | Expert Legal Help 2026",
            "Property Dispute Lawyer in {$label} | {$appName}",
            "Find experienced property dispute lawyers in {$label}. Resolve ownership conflicts, encroachment, partition suits & more. Free first consultation via {$appName}.",
            $this->legalFaqs($label, 'property_dispute'),
            ['subTitle' => "Ownership disputes · Partition suits · Encroachment · Boundary issues in {$label}", 'issueType' => 'property_dispute'],
            $label, $city
        );
    }

    /** GET /rental-agreement-in-{city} */
    public function rentalAgreementInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('legal',
            "Rental Agreement in {$label} | Draft & Register 2026",
            "Rental Agreement Lawyer in {$label} | {$appName}",
            "Get a legally valid rental agreement drafted and registered in {$label}. Protect your rights as owner or tenant. Expert lawyers, free first consultation via {$appName}.",
            $this->legalFaqs($label, 'rental_agreement'),
            ['subTitle' => "Draft · Review · Register rent agreements in {$label} — legally binding", 'issueType' => 'rental_agreement'],
            $label, $city
        );
    }

    /** GET /will-registration-in-{city} */
    public function willRegistrationInCity(string $city)
    {
        $label = $this->resolveCity($city);
        if (!$label) abort(404);
        $appName = config('app.name');
        return $this->render('legal',
            "Will Registration & Property Succession in {$label} 2026",
            "Will Registration in {$label} | Property Succession | {$appName}",
            "Get your will drafted, registered, and property succession planned in {$label}. Expert lawyers, affordable fees, free first consultation via {$appName}.",
            $this->legalFaqs($label, 'will_registration'),
            ['subTitle' => "Will drafting · Registration · Succession planning in {$label}", 'issueType' => 'will_registration'],
            $label, $city
        );
    }

    // ════════════════════════════════════════════════════════════════════
    //  FAQ BUILDERS
    // ════════════════════════════════════════════════════════════════════

    private function loanFaqs(string $city, string $type = ''): array
    {
        $appName = config('app.name');
        $who     = $type === 'salaried' ? 'salaried employees' : ($type === 'self-employed' ? 'self-employed individuals' : 'applicants');
        return [
            ['q' => "What is the home loan interest rate in {$city}?",
             'a' => "Home loan interest rates in {$city} currently range from 8.35% to 9.5% p.a., depending on your credit score, income, and chosen lender. {$appName} experts can help you compare and get the best rate."],
            ['q' => "How much home loan can I get in {$city}?",
             'a' => "Banks typically offer 80–90% of the property value as home loan. For {$who}, eligibility is usually 60× monthly income. Use our free eligibility check to know your exact amount."],
            ['q' => "What documents are needed for a home loan in {$city}?",
             'a' => "Key documents include Aadhar card, PAN card, last 3 months salary slips (or 2 years ITR for self-employed), 6 months bank statements, and property documents. Our team assists with document preparation."],
            ['q' => "How long does home loan approval take in {$city}?",
             'a' => "With complete documents, in-principle approval takes 24–48 hours. Final sanction and disbursement takes 7–15 working days depending on the bank and property verification."],
            ['q' => "Can I get a home loan for under-construction property in {$city}?",
             'a' => "Yes, most banks offer home loans for under-construction properties in {$city}. The loan is disbursed in stages as construction progresses. Check that the project is RERA registered before applying."],
        ];
    }

    private function insuranceFaqs(string $city): array
    {
        $appName = config('app.name');
        return [
            ['q' => "How much does property insurance cost in {$city}?",
             'a' => "Property insurance in {$city} typically costs 0.05%–0.10% of property value per year. For a ₹50 Lakh property, annual premium is approximately ₹2,500–₹5,000. {$appName} helps you compare the best rates."],
            ['q' => "What does property insurance cover in India?",
             'a' => "Property insurance covers damage to the building structure and contents from fire, earthquake, flood, theft, and accidental damage. You can choose home structure only, contents only, or both."],
            ['q' => "Is property insurance mandatory in {$city}?",
             'a' => "Property insurance is not legally mandatory, but it is required by most banks as a condition for home loan approval. It is strongly recommended to protect your biggest asset."],
            ['q' => "Which is the best home insurance company in India?",
             'a' => "Top-rated home insurers include HDFC ERGO, Bajaj Allianz, Tata AIG, ICICI Lombard, and New India Assurance. {$appName} helps you compare all plans to find the best value for your {$city} property."],
            ['q' => "How to claim property insurance in {$city}?",
             'a' => "File an FIR (for theft/fire), take photos of damage, notify your insurer within 48 hours, and submit the claim form with evidence. Our experts guide you through the entire claim process."],
        ];
    }

    private function legalFaqs(string $city, string $issueType = ''): array
    {
        $appName = config('app.name');
        $base = [
            ['q' => "How do I verify property title in {$city}?",
             'a' => "Title verification in {$city} involves checking sale deed history for the past 30 years, encumbrance certificate, mutation records, and any pending litigation. A {$appName} affiliated lawyer does this thoroughly."],
            ['q' => "What is the stamp duty for property registration in {$city}?",
             'a' => "Stamp duty in Punjab/Haryana/Chandigarh varies by location — typically 5–7% of circle rate. Registration charges are 1%. Our legal experts give you the exact calculation for your specific property."],
            ['q' => "How long does property registration take in {$city}?",
             'a' => "Property registration at the sub-registrar office in {$city} typically takes 1–3 working days once all documents are in order. Our legal team ensures your documents are complete and correct."],
            ['q' => "Can NRI buy property in {$city}?",
             'a' => "Yes, NRIs can buy residential and commercial property in {$city} under FEMA regulations. Our legal experts specialise in NRI property transactions and handle all compliance requirements."],
        ];

        $specific = match($issueType) {
            'title_verification' => [
                ['q' => "What is an encumbrance certificate and why is it important?",
                 'a' => "An encumbrance certificate shows all registered transactions (loans, mortgages, sales) on a property for a specified period. It proves the property is free from legal dues — essential before purchase."],
                ['q' => "How far back should title be checked in {$city}?",
                 'a' => "A minimum of 30 years of title history should be verified in {$city}. Our lawyers check all sale deeds, mutation records, court orders, and encumbrance certificates for the full period."],
            ],
            'sale_deed' => [
                ['q' => "What is the difference between sale agreement and sale deed in {$city}?",
                 'a' => "A sale agreement is a preliminary contract stating terms of sale. A sale deed is the final registered document that transfers ownership. The sale deed must be registered at the sub-registrar office to be legally valid."],
                ['q' => "Can I register sale deed online in {$city}?",
                 'a' => "Punjab and Haryana offer online appointment booking for registration. However, physical presence at the sub-registrar office is still required for biometric verification. Our lawyers handle all paperwork."],
            ],
            'property_dispute' => [
                ['q' => "What are common property disputes in {$city}?",
                 'a' => "Common property disputes in {$city} include boundary disputes, co-ownership conflicts, illegal encroachment, fraudulent sales, builder-buyer disputes, and succession/inheritance conflicts."],
                ['q' => "How long does a property dispute case take in court?",
                 'a' => "Property dispute cases in civil courts can take 3–7 years. However, many disputes are resolved faster through mediation or Lok Adalat. Our lawyers assess the best strategy for quick resolution."],
            ],
            'rental_agreement' => [
                ['q' => "Is rental agreement mandatory to register in {$city}?",
                 'a' => "Rental agreements for 11 months are typically not registered (notarized only). Agreements for 12+ months must be registered. Our lawyers draft agreements that protect both owner and tenant."],
                ['q' => "What should a rental agreement include in {$city}?",
                 'a' => "A proper rental agreement should include rent amount, security deposit, tenure, maintenance responsibilities, notice period, lock-in clause, and rules for use. Our lawyers ensure nothing is missed."],
            ],
            default => [],
        };

        return array_merge($base, $specific);
    }
}
