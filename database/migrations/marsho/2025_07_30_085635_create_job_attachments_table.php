<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    public function up()
    {
        Schema::connection('mysql_job')->create('marsho_job_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('marsho_job_marsho')->onDelete('cascade');
            $table->foreignId('job_route_id')->nullable()->constrained('marsho_job_routes')->onDelete('set null');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('uploaded_by')->index()->comment('User from main users table');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mysql_job')->dropIfExists('marsho_job_attachments');
    }
};
