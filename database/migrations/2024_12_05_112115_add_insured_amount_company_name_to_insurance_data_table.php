<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsuredAmountCompanyNameToInsuranceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->string('insured_amount_company_name')->nullable()->after('insurance_identifier_type');
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
            $table->dropColumn('insured_amount_company_name');
        });
    }
}
