<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsuredCompanyToInsuranceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->string('insured_company')->nullable()->after('VALSTS');
            $table->string('companyId')->nullable()->after('insured_company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->dropColumn('insured_company');
            $table->dropColumn('companyId');
        });
    }
}

