<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotReceivedQuantityToWarehouseMaterialMovementTable2 extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            // Pievieno kolonnu ar nosaukumu `not_received` angļu valodā
            $table->decimal('delta_quantity', 18, 6)->default(0)->index();
        });
    }

    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('delta_quantity');
        });
    }
}
