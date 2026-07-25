{{-- Shared social post editor. Included by dealer.properties.social-post and builder.properties.social-post --}}

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Post Image</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" data-template="square">Square</button>
                    <button type="button" class="btn btn-outline-secondary" data-template="story">Story</button>
                </div>
            </div>
            <div class="card-body text-center bg-light">
                <canvas id="postCanvas" width="1080" height="1080" style="width:100%;max-width:420px;border-radius:6px;box-shadow:0 2px 10px rgba(0,0,0,.15);"></canvas>
                <div id="canvasStatus" class="text-muted small mt-2">Rendering preview…</div>
            </div>
            <div class="card-footer d-flex gap-2">
                <button type="button" id="downloadBtn" class="btn btn-primary btn-sm">
                    <i class="align-middle" data-feather="download"></i> Download Image
                </button>
                <span class="text-muted small align-self-center">PNG · ready for Instagram &amp; Facebook</span>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Caption</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <textarea id="captionBox" class="form-control mb-3" rows="10">{{ $caption }}</textarea>
                <button type="button" id="copyCaptionBtn" class="btn btn-outline-primary btn-sm align-self-start mb-3">
                    <i class="align-middle" data-feather="copy"></i> Copy Caption
                </button>

                <hr>

                <p class="text-muted small mb-2">Post the downloaded image manually on Instagram, or share the link directly on these:</p>
                <div class="d-flex flex-wrap gap-2">
                    @if($publicUrl)
                        <a class="btn btn-outline-secondary btn-sm" target="_blank"
                           href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($publicUrl) }}">
                            Facebook
                        </a>
                        <a class="btn btn-outline-secondary btn-sm" target="_blank"
                           href="https://twitter.com/intent/tweet?url={{ urlencode($publicUrl) }}&text={{ urlencode($property->title) }}">
                            X / Twitter
                        </a>
                        <a class="btn btn-outline-secondary btn-sm" target="_blank"
                           href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($publicUrl) }}">
                            LinkedIn
                        </a>
                        <a class="btn btn-outline-success btn-sm" target="_blank"
                           href="https://wa.me/?text={{ urlencode($property->title . "\n" . $publicUrl) }}">
                            WhatsApp
                        </a>
                    @else
                        <span class="badge bg-secondary">Save property to enable link sharing</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script id="postData" type="application/json">
{!! json_encode([
    'coverImage'  => $property->cover_image ? asset('storage/' . $property->cover_image) : null,
    'title'       => $property->title,
    'listingType' => $property->listing_type,
    'price'       => $property->price ?: $property->expected_price,
    'currency'    => '₹',
    'location'    => collect([$property->locality, $property->city])->filter()->implode(', '),
    'specs'       => collect([$property->bhk_type, $property->area ? $property->area . ' sqft' : null])->filter()->implode(' · '),
    'companyName' => $property->company_name,
    'contactPhone'=> $property->contact_phone,
]) !!}
</script>

<script>
(function () {
    const data = JSON.parse(document.getElementById('postData').textContent);
    const canvas = document.getElementById('postCanvas');
    const ctx = canvas.getContext('2d');
    const statusEl = document.getElementById('canvasStatus');
    let template = 'square'; // 'square' (1080x1080) or 'story' (1080x1920)

    function wrapText(text, maxWidth, font) {
        ctx.font = font;
        const words = text.split(' ');
        const lines = [];
        let line = '';
        words.forEach(word => {
            const test = line ? line + ' ' + word : word;
            if (ctx.measureText(test).width > maxWidth && line) {
                lines.push(line);
                line = word;
            } else {
                line = test;
            }
        });
        if (line) lines.push(line);
        return lines;
    }

    function drawCover(img, w, h) {
        const imgRatio = img.width / img.height;
        const boxRatio = w / h;
        let sx, sy, sw, sh;
        if (imgRatio > boxRatio) {
            sh = img.height;
            sw = sh * boxRatio;
            sx = (img.width - sw) / 2;
            sy = 0;
        } else {
            sw = img.width;
            sh = sw / boxRatio;
            sx = 0;
            sy = (img.height - sh) / 2;
        }
        ctx.drawImage(img, sx, sy, sw, sh, 0, 0, w, h);
    }

    function render() {
        const w = canvas.width;
        const h = template === 'story' ? 1920 : 1080;
        canvas.height = h;

        ctx.fillStyle = '#1e3a5f';
        ctx.fillRect(0, 0, w, h);

        function drawRest(img) {
            if (img) drawCover(img, w, h);

            // Bottom gradient for text legibility
            const grad = ctx.createLinearGradient(0, h * 0.45, 0, h);
            grad.addColorStop(0, 'rgba(10,20,35,0)');
            grad.addColorStop(1, 'rgba(10,20,35,0.92)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, h * 0.45, w, h * 0.55);

            // Top ribbon: FOR SALE / FOR RENT
            const isRent = (data.listingType || '').toLowerCase().includes('rent');
            const ribbonText = isRent ? 'FOR RENT' : 'FOR SALE';
            ctx.font = 'bold 34px sans-serif';
            const ribbonW = ctx.measureText(ribbonText).width + 60;
            ctx.fillStyle = isRent ? '#1e6fd9' : '#e0442e';
            ctx.fillRect(0, 50, ribbonW, 64);
            ctx.fillStyle = '#fff';
            ctx.fillText(ribbonText, 30, 94);

            // Price badge
            if (data.price) {
                const priceText = data.currency + Number(data.price).toLocaleString('en-IN') + (isRent ? '/mo' : '');
                ctx.font = 'bold 46px sans-serif';
                const pw = ctx.measureText(priceText).width + 50;
                ctx.fillStyle = '#ffd166';
                ctx.fillRect(w - pw - 30, 50, pw, 74);
                ctx.fillStyle = '#1e3a5f';
                ctx.fillText(priceText, w - pw - 5, 100);
            }

            // Title (wrapped)
            let y = h - 300;
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 52px sans-serif';
            const titleLines = wrapText(data.title || '', w - 100, 'bold 52px sans-serif');
            titleLines.slice(0, 2).forEach(line => {
                ctx.fillText(line, 50, y);
                y += 60;
            });

            // Specs
            if (data.specs) {
                ctx.font = '34px sans-serif';
                ctx.fillStyle = '#e5e9f0';
                ctx.fillText(data.specs, 50, y + 10);
                y += 55;
            }

            // Location
            if (data.location) {
                ctx.font = '32px sans-serif';
                ctx.fillStyle = '#c8d3e0';
                ctx.fillText('📍 ' + data.location, 50, y + 10);
            }

            // Footer bar: company / contact
            const footerText = [data.companyName, data.contactPhone].filter(Boolean).join('  ·  ');
            if (footerText) {
                ctx.fillStyle = 'rgba(0,0,0,0.35)';
                ctx.fillRect(0, h - 70, w, 70);
                ctx.font = 'bold 30px sans-serif';
                ctx.fillStyle = '#ffffff';
                ctx.fillText(footerText, 50, h - 25);
            }

            statusEl.textContent = 'Preview ready — click Download to save.';
        }

        if (data.coverImage) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => drawRest(img);
            img.onerror = () => { statusEl.textContent = 'Could not load property photo — using plain background.'; drawRest(null); };
            img.src = data.coverImage;
        } else {
            drawRest(null);
        }
    }

    document.querySelectorAll('[data-template]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-template]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            template = btn.dataset.template;
            render();
        });
    });

    document.getElementById('downloadBtn').addEventListener('click', () => {
        const link = document.createElement('a');
        link.download = 'social-post.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });

    document.getElementById('copyCaptionBtn').addEventListener('click', () => {
        const box = document.getElementById('captionBox');
        box.select();
        navigator.clipboard?.writeText(box.value).then(() => {
            const btn = document.getElementById('copyCaptionBtn');
            const original = btn.innerHTML;
            btn.innerHTML = 'Copied!';
            setTimeout(() => btn.innerHTML = original, 1500);
        });
    });

    render();
})();
</script>
