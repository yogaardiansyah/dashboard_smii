<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    public function up()
    {
        Schema::connection('mysql_job')->create('marsho_job_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('marsho_job_marsho')->onDelete('cascade');
            $table->foreignId('from_department_id')->nullable()->constrained('marsho_departments');
            $table->foreignId('to_department_id')->constrained('marsho_departments');           
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->index()->comment('User from main users table');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::connection('mysql_job')->dropIfExists('marsho_job_routes');
    }
};
