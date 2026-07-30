@extends('service-provider.layout')

@section('title', 'Portfolio')

@section('content')
<div class="p-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Portfolio / Work Done</h1>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Add Completed Work</h6>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('service-provider.portfolio.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Full home wiring rework">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" maxlength="1000">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Completed On</label>
                            <input type="date" name="completed_at" class="form-control" value="{{ old('completed_at') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-lg me-1"></i> Add to Portfolio
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="row g-3">
                @forelse($portfolios as $item)
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="{{ $item->title }}">
                            <div class="card-body">
                                <h6 class="mb-1">{{ $item->title }}</h6>
                                @if($item->completed_at)
                                    <p class="text-muted small mb-1">{{ $item->completed_at->format('M Y') }}</p>
                                @endif
                                @if($item->description)
                                    <p class="small mb-2">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
                                @endif
                                <form method="POST" action="{{ route('service-provider.portfolio.destroy', $item) }}"
                                      onsubmit="return confirm('Remove this portfolio item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center text-muted py-5">
                                <i class="bi bi-images fs-1 mb-2 d-block"></i>
                                No portfolio items yet. Add photos of your completed work to build trust with customers.
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
