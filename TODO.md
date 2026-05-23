# TODO - Banner ad-serving system (frontend)

## Step 1: Analyze current banner implementation
- [x] Confirm banner admin CRUD exists
- [x] Confirm current `banners` table only has `title,image,status`
- [x] Inspect `frontend.layout` and `frontend` home partials

## Step 2: Database + model updates for ad-serving
- [x] Add new migration to extend `banners` table with placement/target_url/dates/priority/impression/clicks fields
- [ ] Update `App\Models\Banner` fillable/casts to match new columns
- [ ] Update `Admin\BannerController` validation + store/update to handle new fields
- [ ] Update admin banner create/edit views to select placement and set target_url (and optional start/end/priority)

## Step 3: Ad-serving service + endpoints
- [ ] Add `App\Services\BannerService` to select a banner by placement using active/date/priority
- [ ] Add `BannerImpressionController` endpoint (or method in BannerController) to increment impressions
- [x] Add click endpoint that increments clicks then redirects to `target_url`
- [x] Add routes for impression/click endpoints
- [x] Add click/impression endpoints (BannerServeController + routes)

## Step 4: Frontend injection (ad-serving like system)
- [ ] Create Blade component `<x-banner-ad placement="..." />`
- [ ] Add banner injection via a view composer in `frontend.layout` (or include component directly in key partials)
- [ ] Wire placements into:
  - [ ] Home hero: `homepage_top`
  - [ ] Home mid section: `homepage_middle`
  - [ ] Property sidebar: `property_sidebar`
  - [ ] Blog inline: `blog_inline`

## Step 5: Client-side impression tracking
- [ ] Add IntersectionObserver JS in layout to call impression endpoint for `.track-banner` elements

## Step 6: Testing
- [ ] Run migrations
- [ ] Create sample banners in admin with those placements
- [ ] Verify banners show on homepage/property details/blog
- [ ] Verify click redirect + impression calls

