<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->string('recipient')->nullable()->change(); // Padara kolonnu recipient par nullable
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            //$table->string('recipient')->nullable(false)->change(); // Atgriež kolonnas obligāto prasību
        });
    }
};
