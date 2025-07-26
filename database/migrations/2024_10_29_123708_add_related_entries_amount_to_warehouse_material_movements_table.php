<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedEntriesAmountToWarehouseMaterialMovementsTable extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->decimal('related_entries_quantity', 18, 6)->default(0)->after('code_2');
        });
    }

    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('related_entries_quantity');
        });
    }
}

