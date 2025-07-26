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
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('interest_portion', 12, 2); // Payment interest_portion
            $table->decimal('principal_portion', 12, 2); // Payment principal_portion 
            $table->decimal('remaining_balance', 12, 2); // Payment remaining_balance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('interest_portion');
            $table->dropColumn('principal_portion');
            $table->dropColumn('remaining_balance');
        });
    }
};
