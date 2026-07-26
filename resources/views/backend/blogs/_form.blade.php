{{-- Shared blog post form. $blog is null on create. --}}

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $blog?->title ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-muted small">(leave blank to auto-generate)</span></label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $blog?->slug ?? '') }}" placeholder="e.g. how-to-buy-your-first-home">
                </div>

                <div class="mb-3">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" rows="2" class="form-control" maxlength="500">{{ old('excerpt', $blog?->excerpt ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" rows="16" class="form-control" required>{{ old('content', $blog?->content ?? '') }}</textarea>
                    <div class="form-text">Basic HTML is allowed (e.g. &lt;p&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;img&gt;).</div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h6 class="card-title mb-0">SEO</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $blog?->meta_title ?? '') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-control" maxlength="500">{{ old('meta_description', $blog?->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header"><h6 class="card-title mb-0">Publish</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status', $blog?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $blog?->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $blog?->category ?? '') }}" placeholder="e.g. tips, market-update">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="align-middle" data-feather="save"></i> {{ isset($blog) ? 'Update' : 'Create' }} Blog Post
                </button>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h6 class="card-title mb-0">Featured Image</h6></div>
            <div class="card-body">
                @if(!empty($blog?->featured_image))
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" class="img-fluid rounded mb-3" alt="">
                @endif
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                <div class="form-text">JPG/PNG, max 4MB.</div>
            </div>
        </div>
    </div>
</div>
