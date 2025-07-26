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
            $table->uuid('created_by')->nullable()->after('created_at'); // Pievieno 'created_by' GUID lauku
            $table->uuid('updated_by')->nullable()->after('updated_at'); // Pievieno 'updated_by' GUID lauku
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropColumn('created_by'); // Noņem 'created_by' lauku
            $table->dropColumn('updated_by'); // Noņem 'updated_by' lauku
        });
    }
};
