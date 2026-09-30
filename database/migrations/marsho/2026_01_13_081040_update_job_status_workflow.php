<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    public function up(): void
    {
        DB::connection('mysql_job')->statement("ALTER TABLE marsho_job_marsho MODIFY COLUMN status VARCHAR(255)");
        
        DB::connection('mysql_job')->table('marsho_job_marsho')->where('status', 'open')->update(['status' => 'to_be_scheduled']);
        DB::connection('mysql_job')->table('marsho_job_marsho')->where('status', 'on_process')->update(['status' => 'on_going']);

        DB::connection('mysql_job')->statement("ALTER TABLE marsho_job_marsho MODIFY COLUMN status ENUM('to_be_scheduled', 'scheduled', 'preparation', 'on_going', 'completed', 'closed') DEFAULT 'to_be_scheduled'");
    }

    public function down(): void
    {
        if (Schema::connection('mysql_job')->hasTable('marsho_job_marsho')) {
            DB::connection('mysql_job')->statement("ALTER TABLE marsho_job_marsho MODIFY COLUMN status VARCHAR(255)");
            
            DB::connection('mysql_job')->table('marsho_job_marsho')->where('status', 'to_be_scheduled')->update(['status' => 'open']);
            DB::connection('mysql_job')->table('marsho_job_marsho')->where('status', 'on_going')->update(['status' => 'on_process']);
            DB::connection('mysql_job')->table('marsho_job_marsho')->whereIn('status', ['scheduled', 'preparation'])->update(['status' => 'open']);
            DB::connection('mysql_job')->statement("ALTER TABLE marsho_job_marsho MODIFY COLUMN status ENUM('open', 'on_process', 'completed', 'closed') DEFAULT 'open'");
        }
    }
};
