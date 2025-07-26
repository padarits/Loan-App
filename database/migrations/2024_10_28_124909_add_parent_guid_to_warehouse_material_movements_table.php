<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentGuidToWarehouseMaterialMovementsTable extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->uuid('parent_guid')->nullable()->after('guid')->index();
        });
    }

    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('parent_guid');
        });
    }
}

