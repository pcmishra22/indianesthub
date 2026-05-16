<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransferSqliteToMysql extends Command
{
    protected $signature = 'db:transfer-sqlite-to-mysql {--force : Skip confirmation}';
    protected $description = 'Transfer all rows from SQLite to MySQL and verify counts';

    public function handle()
    {
        if (! $this->option('force') && ! $this->confirm('This will truncate existing data in MySQL tables and replace it with SQLite data. Do you wish to continue?')) {
            return self::FAILURE;
        }

        $sqlitePath = config('database.connections.sqlite.database');

        if (! file_exists($sqlitePath)) {
            $this->error("SQLite database file not found at: {$sqlitePath}");
            $this->comment("Ensure your SQLite file is at database/database.sqlite or set DB_DATABASE_SQLITE in .env");
            return self::FAILURE;
        }

        // Ensure both connections are available
        try {
            DB::connection('sqlite')->getPdo();
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $this->error('Could not connect to both databases. Please check your .env file.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $sqliteConn = DB::connection('sqlite');
        $mysqlConn = DB::connection('mysql');

        try {
            // 1. Get all tables from SQLite (excluding internal ones)
            $tables = $sqliteConn->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'migrations';");

            // 2. Disable Foreign Key Checks in MySQL
            $mysqlConn->statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->info('Starting data transfer...');
            $results = [];

            foreach ($tables as $tableRow) {
                $table = $tableRow->name;
                $this->comment("Transferring table: {$table}");

                // Clear target table
                $mysqlConn->table($table)->truncate();

                // 3. Calculate safe chunk size based on column count to avoid MySQL placeholder limit (65,535)
                $columnCount = count($sqliteConn->getSchemaBuilder()->getColumnListing($table));
                $chunkSize = $columnCount > 0 ? (int) floor(60000 / $columnCount) : 1000;
                $chunkSize = max(1, min($chunkSize, 1000));

                $sqliteConn->table($table)->orderByRaw('1')->chunk($chunkSize, function ($rows) use ($mysqlConn, $table) {
                    // More efficient conversion than json_encode/decode
                    $data = array_map(fn($row) => (array) $row, $rows->toArray());

                    if (!empty($data)) {
                        $mysqlConn->table($table)->insert($data);
                    }
                });

                // 4. Verify Record Counts
                $sqliteCount = $sqliteConn->table($table)->count();
                $mysqlCount = $mysqlConn->table($table)->count();

                $results[] = [
                    'table' => $table,
                    'sqlite' => $sqliteCount,
                    'mysql' => $mysqlCount,
                    'status' => ($sqliteCount === $mysqlCount) ? '<info>OK</info>' : '<error>MISMATCH</error>'
                ];
            }
        } catch (\Exception $e) {
            $this->error("An error occurred during transfer: " . $e->getMessage());
            return self::FAILURE;
        } finally {
            // 5. Always Re-enable Foreign Key Checks
            $mysqlConn->statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->newLine();
        $this->info('Transfer Complete. Verification Summary:');
        $this->table(['Table', 'SQLite Count', 'MySQL Count', 'Status'], $results);

        return self::SUCCESS;
    }
}