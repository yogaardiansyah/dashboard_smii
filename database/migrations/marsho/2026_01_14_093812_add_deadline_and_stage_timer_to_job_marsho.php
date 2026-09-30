<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    public function up(): void
    {
        Schema::connection('mysql_job')->table('marsho_job_marsho', function (Blueprint $table) {
            $table->date('deadline')->nullable()->after('tanggal_job_selesai'); // Deadline total pekerjaan
            $table->timestamp('last_stage_update')->nullable()->after('status'); // Untuk reset timer 3 hari
        });
    }

    public function down(): void
    {
        if (Schema::connection('mysql_job')->hasTable('marsho_job_marsho')) {
            Schema::connection('mysql_job')->table('marsho_job_marsho', function (Blueprint $table) {
                $columns = [];
                if (Schema::connection('mysql_job')->hasColumn('marsho_job_marsho', 'deadline')) {
                    $columns[] = 'deadline';
                }
                if (Schema::connection('mysql_job')->hasColumn('marsho_job_marsho', 'last_stage_update')) {
                    $columns[] = 'last_stage_update';
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
