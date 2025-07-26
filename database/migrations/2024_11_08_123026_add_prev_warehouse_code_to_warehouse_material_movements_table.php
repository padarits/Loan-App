<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrevWarehouseCodeToWarehouseMaterialMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->string('prev_warehouse_code')->nullable()->after('warehouse_code'); // Pievieno jauno kolonnu
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
            $table->dropColumn('prev_warehouse_code'); // Noņem jauno kolonnu
        });
    }
}

