<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->string('rec_type', 30)->nullable()->after('type'); 
            // Aizstājiet 'existing_column' ar kolonnu, pēc kuras vēlaties ievietot `rec_type`.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('rec_type');
        });
    }
};


