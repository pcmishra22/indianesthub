# TODO

- [x] Normalize query params in `app/Http/Controllers/Frontend/PropertiesController.php`:
  - [x] Clean `looking_for` by trimming, stripping quotes, and using the first token before any comma (e.g. `PG,2026-04-01` -> `PG`).
  - [x] Trim/strip quotes for `city` and `locality`.
  - [x] Ensure existing Sale/Buy/Rent/PG fallback logic remains intact.
- [ ] Quick runtime sanity check (curl or phpunit) after changes.


