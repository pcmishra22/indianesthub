{{-- =====================================================================
     EMI Calculator Widget
     Usage: @include('frontend.partials.emi-calculator', ['propertyPrice' => 5000000])
     ===================================================================== --}}

@php
    $defaultLoan = isset($propertyPrice) ? round($propertyPrice * 0.8) : 3000000;
    $defaultLoan = min($defaultLoan, 100000000); // cap at 10 Cr
    $pId     = $property->id ?? null;
    $projId  = $builderProject->id ?? null;
@endphp

<div class="card border-0 shadow-sm rounded-3 overflow-hidden" id="emi-calculator-card">
    <!-- Header -->
    <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-calculator text-white fs-5"></i>
            <h6 class="mb-0 text-white fw-semibold">EMI Calculator</h6>
            <span class="badge bg-warning text-dark ms-auto small">Free Tool</span>
        </div>
    </div>

    <div class="card-body p-4">

        <!-- Loan Amount -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0 fw-medium small text-muted">Loan Amount</label>
                <span class="fw-bold text-primary" id="emi-loan-display">₹<span id="emi-loan-val">{{ number_format($defaultLoan) }}</span></span>
            </div>
            <input type="range" class="form-range" id="emi-loan-range"
                   min="100000" max="100000000" step="100000"
                   value="{{ $defaultLoan }}"
                   oninput="emiCalc.updateLoan(this.value)">
            <div class="d-flex justify-content-between">
                <small class="text-muted">₹1 L</small>
                <small class="text-muted">₹10 Cr</small>
            </div>
        </div>

        <!-- Interest Rate -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0 fw-medium small text-muted">Interest Rate (p.a.)</label>
                <span class="fw-bold text-primary"><span id="emi-rate-val">8.5</span>%</span>
            </div>
            <input type="range" class="form-range" id="emi-rate-range"
                   min="6" max="18" step="0.1" value="8.5"
                   oninput="emiCalc.updateRate(this.value)">
            <div class="d-flex justify-content-between">
                <small class="text-muted">6%</small>
                <small class="text-muted">18%</small>
            </div>
        </div>

        <!-- Tenure -->
        <div class="mb-3">
            <label class="form-label fw-medium small text-muted mb-2">Loan Tenure</label>
            <div class="d-flex gap-2 flex-wrap" id="emi-tenure-btns">
                @foreach([5, 10, 15, 20, 25, 30] as $yr)
                    <button type="button"
                            class="btn btn-sm {{ $yr === 20 ? 'btn-primary' : 'btn-outline-secondary' }} emi-tenure-btn"
                            onclick="emiCalc.setTenure({{ $yr }}, this)">
                        {{ $yr }}Y
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Result -->
        <div class="rounded-3 p-3 text-center my-3" style="background: #f0f7ff; border: 1px solid #bfdbfe;">
            <div class="text-muted small mb-1">Monthly EMI</div>
            <div class="fw-bold text-primary" style="font-size: 1.75rem;" id="emi-monthly">₹0</div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6 text-center rounded p-2" style="background:#f8fafc;">
                <div class="text-muted" style="font-size:0.72rem;">Total Interest</div>
                <div class="fw-semibold text-danger small" id="emi-interest">₹0</div>
            </div>
            <div class="col-6 text-center rounded p-2" style="background:#f8fafc;">
                <div class="text-muted" style="font-size:0.72rem;">Total Amount</div>
                <div class="fw-semibold text-success small" id="emi-total">₹0</div>
            </div>
        </div>

        <!-- CTA -->
        <button type="button"
                class="btn btn-primary w-100 fw-semibold"
                onclick="openLoanModal({{ $pId ?? 'null' }}, {{ $projId ?? 'null' }}, 'emi-calculator')">
            <i class="fas fa-landmark me-2"></i> Check Loan Eligibility &amp; Apply
        </button>
        <p class="text-center text-muted mt-2 mb-0" style="font-size:0.72rem;">
            <i class="fas fa-shield-alt text-success me-1"></i>
            100% Free · No credit score impact · Expert assistance
        </p>
    </div>
</div>

<script>
const emiCalc = (function () {
    let loan   = {{ $defaultLoan }};
    let rate   = 8.5;
    let tenure = 20;

    function formatINR(n) {
        if (n >= 10000000) return '₹' + (n / 10000000).toFixed(2) + ' Cr';
        if (n >= 100000)   return '₹' + (n / 100000).toFixed(2) + ' L';
        return '₹' + n.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function compute() {
        const r = (rate / 12) / 100;
        const n = tenure * 12;
        let emi = 0;
        if (r === 0) {
            emi = loan / n;
        } else {
            emi = loan * r * Math.pow(1 + r, n) / (Math.pow(1 + r, n) - 1);
        }
        const total    = emi * n;
        const interest = total - loan;

        document.getElementById('emi-monthly').textContent  = formatINR(emi);
        document.getElementById('emi-interest').textContent = formatINR(interest);
        document.getElementById('emi-total').textContent    = formatINR(total);
    }

    return {
        updateLoan(v) {
            loan = parseFloat(v);
            document.getElementById('emi-loan-val').textContent = parseFloat(v).toLocaleString('en-IN');
            compute();
        },
        updateRate(v) {
            rate = parseFloat(v);
            document.getElementById('emi-rate-val').textContent = parseFloat(v).toFixed(1);
            compute();
        },
        setTenure(yr, el) {
            tenure = yr;
            document.querySelectorAll('.emi-tenure-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-secondary');
            });
            el.classList.remove('btn-outline-secondary');
            el.classList.add('btn-primary');
            compute();
        },
    };
})();

// Initial compute
document.addEventListener('DOMContentLoaded', () => emiCalc.updateLoan({{ $defaultLoan }}));
</script>
