{{--
  Partner role switcher. $activeRole = 'dealer' | 'builder' | 'service_provider'
  $mode = 'register' | 'login'
--}}
<p class="text-center text-muted small mb-2 fw-semibold">
    I want to {{ $mode === 'login' ? 'sign in' : 'register' }} as a...
</p>
<div class="d-flex justify-content-center gap-2 mb-4" style="flex-wrap:wrap;">
    @php
        $tabs = [
            'dealer'           => ['label' => 'Property Dealer',  'icon' => 'bi-building'],
            'builder'          => ['label' => 'Builder',          'icon' => 'bi-buildings'],
            'service_provider' => ['label' => 'Service Provider', 'icon' => 'bi-tools'],
        ];
        $routeFor = [
            'dealer'           => $mode === 'login' ? route('dealer.login')           : route('dealer.register'),
            'builder'          => $mode === 'login' ? route('builder.login')          : route('builder.register'),
            'service_provider' => $mode === 'login' ? route('service-provider.login') : route('service-provider.register'),
        ];
    @endphp
    @foreach($tabs as $key => $tab)
        <a href="{{ $routeFor[$key] }}"
           class="btn btn-sm px-3 py-2"
           style="border-radius:8px;font-weight:600;font-size:13px;
                  {{ $activeRole === $key
                        ? 'background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;border:none;'
                        : 'background:#fff;color:#475569;border:1.5px solid #e2e8f0;' }}">
            <i class="bi {{ $tab['icon'] }} me-1"></i>{{ $tab['label'] }}
        </a>
    @endforeach
</div>
