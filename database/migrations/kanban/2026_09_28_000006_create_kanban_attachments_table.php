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
        Schema::connection('mysql_kanban')->create('kanban_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('kanban_jobs')->onDelete('cascade');
            $table->foreignId('job_route_id')->nullable()->constrained('kanban_routes')->onDelete('set null');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('uploaded_by')->index()->comment('Uploaded by (users.id)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->dropIfExists('kanban_attachments');
    }
};
