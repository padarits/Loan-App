<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArticleToWarehouseMaterialMovementsTable extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->string('article', 255)->nullable()->after('guid')->index();
        });
    }

    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('article');
        });
    }
}
