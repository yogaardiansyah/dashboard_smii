<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'mysql_job';

    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::connection('mysql_job')->statement("ALTER TABLE marsho_job_marsho MODIFY COLUMN status ENUM('to_be_scheduled', 'scheduled', 'preparation', 'on_going', 'completed', 'closed', 'cancelled') DEFAULT 'to_be_scheduled'");

        Schema::connection('mysql_job')->table('marsho_job_marsho', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('closed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('mysql_job')->hasTable('marsho_job_marsho')) {
            if (Schema::connection('mysql_job')->hasColumn('marsho_job_marsho', 'cancellation_reason')) {
                Schema::connection('mysql_job')->table('marsho_job_marsho', function (Blueprint $table) {
                    $table->dropColumn('cancellation_reason');
                });
            }
        }
    }
};
