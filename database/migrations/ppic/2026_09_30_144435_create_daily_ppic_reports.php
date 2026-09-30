<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppic_daily_ppic_reports', function (Blueprint $table) {
            // Tanggal snapshot harian & Master Item
            $table->date('report_date')->nullable()->after('id');
            $table->string('uom')->nullable()->after('description');
            $table->decimal('net_weight', 15, 4)->default(0)->after('uom');
            // Breakdown Sellable & Unsellable Qty
            $table->decimal('inventory_sellable', 15, 2)->default(0)->after('inventory_qty');
            $table->decimal('pallet_inventory_sellable', 15, 2)->default(0)->after('inventory_sellable');
            $table->decimal('inventory_unsellable', 15, 2)->default(0)->after('pallet_inventory_sellable');
            $table->decimal('pallet_inventory_unsellable', 15, 2)->default(0)->after('inventory_unsellable');
            // Hasil Kalkulasi Tonase
            $table->decimal('inventory_tonnage', 15, 4)->default(0)->after('forecast_unit');
            $table->decimal('inventory_sellable_tonnage', 15, 4)->default(0)->after('inventory_tonnage');
            $table->decimal('inventory_unsellable_tonnage', 15, 4)->default(0)->after('inventory_sellable_tonnage');
            $table->decimal('dispatch_tonnage', 15, 4)->default(0)->after('inventory_unsellable_tonnage');
            $table->decimal('allocated_tonnage', 15, 4)->default(0)->after('dispatch_tonnage');
            $table->decimal('so_outstanding_tonnage', 15, 4)->default(0)->after('allocated_tonnage');
            $table->decimal('mps_tonnage', 15, 4)->default(0)->after('so_outstanding_tonnage');
            // Indeks untuk query dashboard yang cepat
            $table->index(['report_date', 'item_number']);
        });
    }
    public function down(): void
    {
        Schema::table('ppic_daily_ppic_reports', function (Blueprint $table) {
            $table->dropIndex(['report_date', 'item_number']);
            $table->dropColumn([
                'report_date', 'uom', 'net_weight',
                'inventory_sellable', 'pallet_inventory_sellable',
                'inventory_unsellable', 'pallet_inventory_unsellable',
                'inventory_tonnage', 'inventory_sellable_tonnage', 'inventory_unsellable_tonnage',
                'dispatch_tonnage', 'allocated_tonnage', 'so_outstanding_tonnage', 'mps_tonnage'
            ]);
        });
    }
};
