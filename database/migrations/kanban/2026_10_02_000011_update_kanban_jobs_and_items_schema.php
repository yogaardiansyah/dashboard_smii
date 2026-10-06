<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah source ke kanban_jobs jika belum ada
        Schema::connection('mysql_kanban')->table('kanban_jobs', function (Blueprint $table) {
            if (!Schema::connection('mysql_kanban')->hasColumn('kanban_jobs', 'source')) {
                $table->enum('source', ['manual', 'api'])->default('manual')->after('external_reference_id')->index();
            }
        });

        // 2. Modifikasi enum status kanban_jobs untuk alur baru:
        // Need Review -> To Be Scheduled -> Scheduled -> On Going -> Completed -> Closed
        DB::connection('mysql_kanban')->statement("
            ALTER TABLE `kanban_jobs` 
            MODIFY COLUMN `status` ENUM(
                'need_review',
                'to_be_scheduled',
                'scheduled',
                'on_going',
                'completed',
                'closed',
                'cancelled',
                'on_hold',
                'preparation'
            ) NOT NULL DEFAULT 'need_review'
        ");

        // Set job lama yang berstatus on_hold atau preparation ke alur baru jika ada
        DB::connection('mysql_kanban')->table('kanban_jobs')
            ->where('status', 'on_hold')
            ->update(['status' => 'need_review']);

        DB::connection('mysql_kanban')->table('kanban_jobs')
            ->where('status', 'preparation')
            ->update(['status' => 'on_going']);

        // Set source = 'api' untuk job yang punya external_reference_id
        DB::connection('mysql_kanban')->table('kanban_jobs')
            ->whereNotNull('external_reference_id')
            ->update(['source' => 'api']);

        // 3. Tambah lot_number ke kanban_items
        Schema::connection('mysql_kanban')->table('kanban_items', function (Blueprint $table) {
            if (!Schema::connection('mysql_kanban')->hasColumn('kanban_items', 'lot_number')) {
                $table->string('lot_number', 100)->nullable()->after('item_name')->index();
            }
        });

        // 4. Pastikan departemen PPIC ada di kanban_departments
        $ppicExists = DB::connection('mysql_kanban')->table('kanban_departments')
            ->where('department_name', 'like', '%PPIC%')
            ->exists();

        if (!$ppicExists) {
            DB::connection('mysql_kanban')->table('kanban_departments')->insert([
                'department_name' => 'PPIC',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->table('kanban_items', function (Blueprint $table) {
            if (Schema::connection('mysql_kanban')->hasColumn('kanban_items', 'lot_number')) {
                $table->dropColumn('lot_number');
            }
        });

        Schema::connection('mysql_kanban')->table('kanban_jobs', function (Blueprint $table) {
            if (Schema::connection('mysql_kanban')->hasColumn('kanban_jobs', 'source')) {
                $table->dropColumn('source');
            }
        });
    }
};
