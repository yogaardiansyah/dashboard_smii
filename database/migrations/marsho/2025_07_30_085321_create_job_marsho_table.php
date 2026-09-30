<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_job';

    public function up()
    {
        Schema::connection('mysql_job')->create('marsho_job_marsho', function (Blueprint $table) {
            $table->id();
            $table->string('id_job')->unique();
            $table->unsignedBigInteger('pengaju_id')->index()->comment('Requester from main users table');
            $table->foreignId('area_id')->constrained('marsho_areas')->onDelete('restrict');
            $table->text('list_job');
            $table->date('tanggal_job_mulai')->nullable();
            $table->date('tanggal_job_selesai')->nullable();
            $table->enum('status', ['open', 'on_process', 'completed', 'closed'])->default('open');
            $table->unsignedBigInteger('penutup_id')->nullable()->index()->comment('Closer from main users table');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mysql_job')->dropIfExists('marsho_job_marsho');
    }
};
