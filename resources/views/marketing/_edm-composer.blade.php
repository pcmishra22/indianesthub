{{-- Shared EDM composer. Included by dealer.properties.edm and builder.properties.edm --}}

@if(session('edm_success'))
    <div class="alert alert-success">{{ session('edm_success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $sendUrl }}">
    @csrf

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Compose Email</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject', $subject) }}" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="8" class="form-control" maxlength="3000" required>{{ old('message', $message) }}</textarea>
                        <div class="form-text">The property photo, price and a "View Full Listing" button are added automatically below your message.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recipients</h5>
                    @if($leads->isNotEmpty())
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="selectAllLeads" checked>
                            <label class="form-check-label small" for="selectAllLeads">Select all</label>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($leads->isNotEmpty())
                        <p class="text-muted small mb-2">People who enquired about this property ({{ $leads->count() }}):</p>
                        <div style="max-height:220px;overflow-y:auto;" class="mb-3 border rounded p-2">
                            @foreach($leads as $lead)
                                <div class="form-check">
                                    <input class="form-check-input lead-checkbox" type="checkbox" name="lead_emails[]"
                                           value="{{ $lead->email }}" id="lead-{{ $lead->id }}" checked>
                                    <label class="form-check-label small" for="lead-{{ $lead->id }}">
                                        {{ $lead->name }} &lt;{{ $lead->email }}&gt;
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-3">No enquiries yet for this property. Add emails manually below.</p>
                    @endif

                    <label class="form-label small">Additional emails (comma or newline separated)</label>
                    <textarea name="extra_emails" rows="3" class="form-control" placeholder="jane@example.com, john@example.com">{{ old('extra_emails') }}</textarea>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="align-middle" data-feather="send"></i> Send Email Campaign
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    const selectAll = document.getElementById('selectAllLeads');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.lead-checkbox').forEach(cb => cb.checked = selectAll.checked);
        });
    }
</script>
