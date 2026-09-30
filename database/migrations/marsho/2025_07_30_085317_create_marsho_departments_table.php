<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    public function up(): void
    {
        Schema::connection('mysql_job')->create('marsho_departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql_job')->dropIfExists('marsho_departments');
    }
};
