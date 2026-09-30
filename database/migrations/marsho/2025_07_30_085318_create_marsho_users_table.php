<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_job')->create('marsho_users', function (Blueprint $table) {
            $table->id();
            
            // Reference to user_id from main database users table (no cross-db foreign key)
            $table->unsignedBigInteger('user_id')->unique();

            // Foreign key to marsho_departments within mysql_job
            $table->foreignId('marsho_department_id')
                  ->constrained('marsho_departments')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_job')->dropIfExists('marsho_users');
    }
};
