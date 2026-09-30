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
        Schema::connection('mysql_kanban')->create('kanban_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('kanban_jobs')->onDelete('cascade');
            $table->foreignId('from_department_id')->nullable()->constrained('kanban_departments');
            $table->foreignId('to_department_id')->constrained('kanban_departments');           
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->index()->comment('Created by (users.id)');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->dropIfExists('kanban_routes');
    }
};
