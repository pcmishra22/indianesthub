@extends('builder.layout')

@section('title', 'Select Facebook Page — Builder Panel')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card" style="border-radius:12px;border:1px solid #e2e8f0;">
                <div class="card-body">
                    <h4 class="mb-1">Which Page do you run project ads from?</h4>
                    <p class="text-muted small mb-4">You manage multiple Facebook Pages — pick the one you use for project listings and ads.</p>

                    <form action="{{ route('builder.social.select-page.submit') }}" method="POST">
                        @csrf
                        <div class="list-group mb-3">
                            @foreach($pages as $page)
                            <label class="list-group-item d-flex align-items-center gap-3">
                                <input type="radio" name="page_id" value="{{ $page['id'] }}" class="form-check-input mt-0" required>
                                <div>
                                    <div class="fw-semibold">{{ $page['name'] }}</div>
                                    <div class="small text-muted">
                                        {{ $page['category'] ?? '' }}
                                        @if(!empty($page['instagram_business_account']['username']))
                                            &middot; Instagram: {{ '@' . $page['instagram_business_account']['username'] }}
                                        @endif
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Connect This Page</button>
                        <a href="{{ route('builder.social.index') }}" class="btn btn-link w-100 text-muted">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
