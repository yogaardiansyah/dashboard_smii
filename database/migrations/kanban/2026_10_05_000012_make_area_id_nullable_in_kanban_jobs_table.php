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
        // Ubah foreignId area_id di kanban_jobs menjadi nullable agar job dari integrasi API
        // (misal Warehouse) bisa dibuat tanpa area_id terlebih dahulu, lalu diisi pada tahap Need Review.
        DB::connection('mysql_kanban')->statement("
            ALTER TABLE `kanban_jobs` 
            MODIFY COLUMN `area_id` BIGINT UNSIGNED NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('mysql_kanban')->statement("
            ALTER TABLE `kanban_jobs` 
            MODIFY COLUMN `area_id` BIGINT UNSIGNED NOT NULL
        ");
    }
};
