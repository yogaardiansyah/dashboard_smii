<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Upgrade kanban_jobs
        Schema::connection('mysql_kanban')->table('kanban_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id')->index();
            $table->foreign('parent_id')->references('id')->on('kanban_jobs')->onDelete('set null');

            $table->string('external_reference_id')->nullable()->unique()->after('id_job');
            $table->unsignedBigInteger('pic_id')->nullable()->after('pengaju_id')->index()->comment('PIC in active department (users.id)');
            
            $table->integer('balance')->default(0)->after('list_job');
            $table->text('reason_description')->nullable()->after('balance');
            $table->text('remark')->nullable()->after('reason_description');
            
            $table->boolean('has_issue')->default(false)->after('status');
            $table->text('issue_note')->nullable()->after('has_issue');
        });

        // Ubah enum status kanban_jobs dan jadikan tanggal nullable
        DB::connection('mysql_kanban')->statement("
            ALTER TABLE `kanban_jobs` 
            MODIFY COLUMN `status` ENUM(
                'on_hold',
                'need_review',
                'scheduled',
                'preparation',
                'on_going',
                'completed',
                'closed',
                'cancelled'
            ) NOT NULL DEFAULT 'on_hold',
            MODIFY COLUMN `tanggal_job_mulai` DATE NULL,
            MODIFY COLUMN `tanggal_job_selesai` DATE NULL,
            MODIFY COLUMN `deadline` DATE NULL
        ");

        // 2. Buat tabel kanban_items
        Schema::connection('mysql_kanban')->create('kanban_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('kanban_jobs')->onDelete('cascade');
            $table->string('item_code')->nullable();
            $table->string('item_name');
            $table->boolean('is_completed')->default(false)->index();
            $table->unsignedBigInteger('completed_by')->nullable()->index();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 3. Upgrade kanban_routes
        Schema::connection('mysql_kanban')->table('kanban_routes', function (Blueprint $table) {
            $table->string('from_status')->nullable()->after('to_department_id');
            $table->string('to_status')->nullable()->after('from_status');
            $table->string('ip_address', 45)->nullable()->after('note');
            $table->text('user_agent')->nullable()->after('ip_address');
        });

        // 4. Upgrade kanban_attachments
        Schema::connection('mysql_kanban')->table('kanban_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('job_route_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_kanban')->table('kanban_attachments', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });

        Schema::connection('mysql_kanban')->table('kanban_routes', function (Blueprint $table) {
            $table->dropColumn(['from_status', 'to_status', 'ip_address', 'user_agent']);
        });

        Schema::connection('mysql_kanban')->dropIfExists('kanban_items');

        Schema::connection('mysql_kanban')->table('kanban_jobs', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'external_reference_id',
                'pic_id',
                'balance',
                'reason_description',
                'remark',
                'has_issue',
                'issue_note'
            ]);
        });
    }
};
