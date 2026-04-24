@extends('backend.layout')
@section('title', 'Site Settings')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-cog me-2 text-primary"></i>Site Settings</h4>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    {{-- Tab navigation --}}
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        @foreach($groups as $groupKey => $groupInfo)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $groupKey }}"
                    data-bs-toggle="tab"
                    data-bs-target="#pane-{{ $groupKey }}"
                    type="button" role="tab">
                <i data-feather="{{ $groupInfo['icon'] }}" class="me-1" style="width:14px;height:14px;"></i>
                {{ $groupInfo['label'] }}
            </button>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($groups as $groupKey => $groupInfo)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
             id="pane-{{ $groupKey }}" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach($settings->where('group', $groupKey) as $setting)
                        <div class="{{ $setting->type === 'textarea' ? 'col-12' : 'col-md-6' }}">
                            <label class="form-label fw-semibold">{{ $setting->label }}</label>
                            @if($setting->type === 'textarea')
                                <textarea name="{{ $setting->key }}" class="form-control" rows="3">{{ $setting->value }}</textarea>
                            @elseif($setting->type === 'boolean')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="{{ $setting->key }}" id="{{ $setting->key }}" value="1"
                                           {{ $setting->value == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $setting->key }}">
                                        {{ $setting->value == '1' ? 'Enabled' : 'Disabled' }}
                                    </label>
                                </div>
                            @elseif($setting->type === 'image')
                                @if($setting->value)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$setting->value) }}" alt="{{ $setting->key }}"
                                         style="max-height:60px;border-radius:6px;border:1px solid #e2e8f0;">
                                </div>
                                @endif
                                <input type="text" name="{{ $setting->key }}" class="form-control"
                                       value="{{ $setting->value }}" placeholder="Storage path or URL">
                            @else
                                <input type="text" name="{{ $setting->key }}" class="form-control"
                                       value="{{ $setting->value }}"
                                       @if(str_contains($setting->key,'color')) type="color" class="form-control form-control-color" @endif>
                            @endif
                            @if($setting->key === 'maintenance_mode' && $setting->value == '1')
                            <div class="text-danger small mt-1">
                                <i class="fas fa-exclamation-triangle me-1"></i> Site is currently in maintenance mode!
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Save button --}}
    <div class="d-flex justify-content-end mt-4 gap-2">
        <button type="submit" class="btn btn-primary px-5 fw-semibold">
            <i class="fas fa-save me-2"></i> Save All Settings
        </button>
    </div>

</form>

<script>
// Refresh feather icons after tab switch (they use data-feather)
document.addEventListener('DOMContentLoaded', function () {
    if (typeof feather !== 'undefined') feather.replace();
    // Update boolean toggle labels live
    document.querySelectorAll('input[type="checkbox"].form-check-input').forEach(function(cb) {
        cb.addEventListener('change', function() {
            this.nextElementSibling.textContent = this.checked ? 'Enabled' : 'Disabled';
        });
    });
});
</script>

@endsection
