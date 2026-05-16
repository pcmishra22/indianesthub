## TODO
- [x] Implement guard logic in `database/migrations/2026_02_21_110655_add_builder_fields_to_properties_table.php` to skip adding foreign keys if `builders` or `builder_projects` tables don’t exist.
- [x] Re-run migrations (`php artisan migrate`) to confirm the foreign key error is resolved.



