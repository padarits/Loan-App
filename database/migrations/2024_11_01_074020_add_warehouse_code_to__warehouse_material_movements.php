<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WarehouseMaterialMovement;

class AddWarehouseCodeToWarehouseMaterialMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->string('warehouse_code', 50)->default(WarehouseMaterialMovement::WarehouseTypeNone)->after('guid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('warehouse_code');
        });
    }
}

