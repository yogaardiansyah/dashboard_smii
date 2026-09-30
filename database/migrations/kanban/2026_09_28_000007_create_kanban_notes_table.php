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
        Schema::connection('mysql_kanban')->create('kanban_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('kanban_jobs')->onDelete('cascade');
            $table->foreignId('job_route_id')->nullable()->constrained('kanban_routes')->onDelete('set null');
            $table->text('note');
            $table->unsignedBigInteger('created_by')->index()->comment('Created by (users.id)');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->dropIfExists('kanban_notes');
    }
};
