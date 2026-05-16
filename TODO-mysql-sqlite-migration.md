# TODO: Move SQLite -> MySQL rows and verify

- [x] Inspect SQLite schema: list tables + columns
- [x] Connect to MySQL and list target tables + schemas
- [x] Copy rows table-by-table (disable FK checks during load)
- [x] Normalize/handle datatype differences (e.g., booleans, timestamps, JSON/text)
- [x] Verify counts per table (SQLite vs MySQL)
- [x] Spot-check sample rows per table
- [x] Produce a summary report with any mismatches
- [x] Finalize Artisan command for automation
