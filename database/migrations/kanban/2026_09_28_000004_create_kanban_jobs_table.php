<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_kanban')->create('kanban_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('id_job')->unique();
            $table->unsignedBigInteger('pengaju_id')->index()->comment('Requester (users.id)');
            $table->foreignId('area_id')->constrained('kanban_areas')->onDelete('restrict');
            $table->text('list_job');
            $table->date('tanggal_job_mulai')->nullable();
            $table->date('tanggal_job_selesai')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', [
                'to_be_scheduled',
                'scheduled',
                'preparation',
                'on_going',
                'completed',
                'closed',
                'cancelled'
            ])->default('to_be_scheduled');
            $table->timestamp('last_stage_update')->nullable();
            $table->unsignedBigInteger('penutup_id')->nullable()->index()->comment('Closed by (users.id)');
            $table->timestamp('closed_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->dropIfExists('kanban_jobs');
    }
};
