<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedAtIndexToWarehouseMaterialMovementTable extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            // Pievieno updated_at kolonnu, ja tā neeksistē
            if (!Schema::hasColumn('warehouse_material_movements', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            // Pievieno indeksu kolonnai updated_at
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            // Noņem indeksu no kolonnas updated_at
            $table->dropIndex(['updated_at']);

            // Ja nepieciešams, noņem kolonu updated_at
            $table->dropColumn('updated_at');
        });
    }
}

