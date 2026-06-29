@extends('backend.layout')
@section('title', 'All Inquiries')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0 fw-bold">All Inquiries</h4>
  <span class="text-muted small">Total: {{ $counts['all'] }}</span>
</div>

{{-- Tab Filter --}}
<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link {{ $type === 'all' ? 'active' : '' }}" href="?type=all">
      All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ $type === 'property' ? 'active' : '' }}" href="?type=property">
      Property Inquiries <span class="badge bg-primary ms-1">{{ $counts['property'] }}</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ $type === 'builder' ? 'active' : '' }}" href="?type=builder">
      Builder / Project Leads <span class="badge bg-success ms-1">{{ $counts['builder'] }}</span>
    </a>
  </li>
</ul>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Type</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Source</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        @forelse($inquiries as $i => $inq)
          <tr>
            <td class="text-muted small">{{ $i + 1 }}</td>
            <td>
              @if($inq['type'] === 'builder')
                <span class="badge bg-success">Builder</span>
              @else
                <span class="badge bg-primary">Property</span>
              @endif
            </td>
            <td class="fw-semibold">{{ $inq['name'] }}</td>
            <td>{{ $inq['phone'] ?? '—' }}</td>
            <td>{{ $inq['email'] ?? '—' }}</td>
            <td>
              @if($inq['subject_url'])
                <a href="{{ $inq['subject_url'] }}" target="_blank" class="text-decoration-none small">
                  {{ Str::limit($inq['subject'], 40) }}
                </a>
              @else
                <span class="small text-muted">{{ $inq['subject'] }}</span>
              @endif
            </td>
            <td>
              @php
                $badgeClass = match($inq['status']) {
                  'new'       => 'bg-warning text-dark',
                  'active'    => 'bg-success',
                  'contacted' => 'bg-info text-dark',
                  'converted' => 'bg-success',
                  'lost'      => 'bg-danger',
                  default     => 'bg-secondary',
                };
              @endphp
              <span class="badge {{ $badgeClass }}">{{ ucfirst($inq['status']) }}</span>
            </td>
            <td class="small text-muted">{{ $inq['source'] }}</td>
            <td class="small" style="max-width:160px;">{{ Str::limit($inq['message'], 60) }}</td>
            <td class="small text-muted text-nowrap">{{ \Carbon\Carbon::parse($inq['created_at'])->format('d M Y, h:i A') }}</td>
            <td>
              <a href="{{ $inq['detail_url'] }}" class="btn btn-sm btn-outline-primary">View</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="11" class="text-center text-muted py-4">No inquiries found.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
