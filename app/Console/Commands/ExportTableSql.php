<?php

// app/Console/Commands/ExportTableSql.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportTableSql extends Command
{
    protected $signature = 'export:table {table}';
    protected $description = 'Export a specific table as an SQL file';

    public function handle()
    {
        $table = $this->argument('table');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');

        $filename = "$table.sql";
        $path = storage_path("app/public/{$filename}");

        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} {$table} > {$path}";
        $output = null;
        $resultCode = null;

        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            $this->info("Table '{$table}' has been successfully exported to {$path}");
        } else {
            $this->error("Failed to export table '{$table}'");
        }
    }
}

