<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToWarehouseMaterialMovementTable extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            // Pievienojam kopīgo indeksu trim laukiem
            $table->index(['type', 'article_id', 'date'], 'type_article_created_index');
        });
    }

    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            // Noņemam indeksu ja nepieciešams migrāciju atcelt
            $table->dropIndex('type_article_created_index');
        });
    }
}

