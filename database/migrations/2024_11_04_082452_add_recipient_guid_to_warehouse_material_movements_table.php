<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->uuid('recipient_guid')->nullable()->after('recipient')->index(); // norādi pareizo kolonnu, aiz kuras ievietot recipient_guid
            $table->foreign('recipient_guid')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_material_movements', function (Blueprint $table) {
            $table->dropForeign(['recipient_guid']);
            $table->dropColumn('recipient_guid');
        });
    }
};
