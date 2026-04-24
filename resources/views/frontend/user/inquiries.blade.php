{{-- resources/views/frontend/user/inquiries.blade.php --}}
@extends('frontend.user.layout')

@section('page-title', 'My Inquiries')

@section('user-content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">My Inquiries</h4>
      <p class="text-muted mb-0">Track all your property inquiries.</p>
    </div>
    @if(isset($inquiries) && $inquiries->count() > 0)
      <span class="badge fs-6" style="background-color: #077f46;">{{ $inquiries->total() ?? $inquiries->count() }} Total</span>
    @endif
  </div>

  @if(isset($inquiries) && $inquiries->count() > 0)
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="min-width: 200px;">Property</th>
            <th style="min-width: 250px;">Message</th>
            <th style="min-width: 120px;">Date</th>
            <th style="min-width: 100px;">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($inquiries as $inquiry)
          <tr>
            <td>
              @if($inquiry->property)
                <a href="{{ route('property-details', $inquiry->property) }}" class="text-decoration-none">
                  <div class="d-flex align-items-center">
                    @php
                      $imgUrl = null;
                      if ($inquiry->property->cover_image) {
                          $imgUrl = asset('storage/' . $inquiry->property->cover_image);
                      } elseif ($inquiry->property->images && $inquiry->property->images->first()) {
                          $imgUrl = asset('storage/' . $inquiry->property->images->first()->image_path);
                      }
                    @endphp
                    <img src="{{ $imgUrl ?? asset('frontend/img/real-estate/property-exterior-2.webp') }}" alt="Property" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                      <strong class="text-dark d-block" style="font-size: 13px;">{{ Str::limit($inquiry->property->title, 35) }}</strong>
                      <small class="text-muted">{{ $inquiry->property->city ?? '' }}</small>
                    </div>
                  </div>
                </a>
              @else
                <span class="text-muted"><em>Property removed</em></span>
              @endif
            </td>
            <td>
              <p class="mb-0 text-muted" style="font-size: 13px;">{{ Str::limit($inquiry->message, 80) }}</p>
            </td>
            <td>
              <small class="text-muted">{{ $inquiry->created_at->format('d M Y') }}</small>
              <br>
              <small class="text-muted" style="font-size: 11px;">{{ $inquiry->created_at->diffForHumans() }}</small>
            </td>
            <td>
              @php
                $status = $inquiry->status ?? 'pending';
                $badgeClass = match($status) {
                    'replied', 'responded' => 'bg-success',
                    'read', 'seen' => 'bg-info',
                    'closed' => 'bg-secondary',
                    default => 'bg-warning text-dark',
                };
              @endphp
              <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($inquiries->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $inquiries->links() }}
    </div>
    @endif
  @else
    <div class="text-center py-5">
      <div class="mb-3">
        <i class="bi bi-chat-left-text" style="font-size: 64px; color: #dee2e6;"></i>
      </div>
      <h5 class="text-muted">No inquiries yet</h5>
      <p class="text-muted mb-3">When you send an inquiry about a property, it will appear here.</p>
      <a href="{{ route('properties') }}" class="btn btn-outline-success">
        <i class="bi bi-search me-1"></i> Browse Properties
      </a>
    </div>
  @endif
@endsection
