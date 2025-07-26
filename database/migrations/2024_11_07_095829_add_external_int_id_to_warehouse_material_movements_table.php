<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->integer('external_int_id')->nullable()->after('guid'); // Norādiet kolonnu, pēc kuras pievienot
        });
    }
    
    public function down()
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('external_int_id');
        });
    }
    
};
