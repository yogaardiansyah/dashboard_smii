<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanKanbanData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanban:clean-data {--force : Force cleanup without asking confirmation} {--keep-logs : Keep activity logs, only clean jobs, items, and routes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Kanban transactional test data (jobs, items, routes, activity logs) while preserving master data (areas, departments, users)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda yakin ingin membersihkan data transaksi Kanban (jobs, items, routes, logs)? Data master area & department tetap aman.')) {
                $this->info('Pembersihan data dibatalkan.');
                return 0;
            }
        }

        $this->info('Memulai pembersihan data transaksi Kanban...');

        $connection = DB::connection('mysql_kanban');

        $tables = [
            'kanban_job_routes',
            'kanban_items',
            'kanban_jobs',
        ];

        if (!$this->option('keep-logs')) {
            $tables[] = 'kanban_activity_logs';
        }

        try {
            $connection->statement('SET FOREIGN_KEY_CHECKS=0;');

            $results = [];
            foreach ($tables as $table) {
                if (Schema::connection('mysql_kanban')->hasTable($table)) {
                    $countBefore = $connection->table($table)->count();
                    $connection->table($table)->truncate();
                    $results[] = [$table, $countBefore, 0];
                }
            }

            $connection->statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->table(['Tabel', 'Jumlah Sebelum', 'Jumlah Sesudah'], $results);
            $this->info('✓ Data transaksi Kanban berhasil dibersihkan dengan sukses!');
            $this->line('Data master (kanban_areas, kanban_departments, kanban_users) tetap utuh.');

            return 0;
        } catch (\Throwable $e) {
            $connection->statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Gagal membersihkan data: ' . $e->getMessage());
            return 1;
        }
    }
}
