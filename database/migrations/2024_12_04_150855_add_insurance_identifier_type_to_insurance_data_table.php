<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsuranceIdentifierTypeToInsuranceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->string('insurance_identifier_type')->nullable()->after('companyId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->dropColumn('insurance_identifier_type');
        });
    }
}
