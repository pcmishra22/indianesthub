- [ ] Identify mismatch between BlogPost model queries and blog_posts migration columns
- [ ] Update BlogPost model scopePublished (and any other references) to use existing columns (is_published) instead of missing status
- [ ] Update BlogController show() visibility check to align with published rules
- [ ] Run Laravel tests or a quick artisan migrate check / minimal query to confirm fix

