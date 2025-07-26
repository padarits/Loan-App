<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToWarehouseMaterialMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->index(['guid', 'warehouse_code', 'article_id', 'unit'], 'warehouse_material_movements_index');
            $table->index('warehouse_code');
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
            //$table->dropIndex('warehouse_material_movements_index');
            //$table->dropIndex('warehouse_code');
        });
    }
}

