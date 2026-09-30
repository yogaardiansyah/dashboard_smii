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
        Schema::connection('mysql_kanban')->create('kanban_users', function (Blueprint $table) {
            $table->id();
            
            // Reference ke ID tabel users (main database)
            $table->unsignedBigInteger('user_id')->unique()->index();

            // Foreign key ke tabel kanban_departments (same database)
            $table->foreignId('kanban_department_id')
                  ->constrained('kanban_departments')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->dropIfExists('kanban_users');
    }
};
