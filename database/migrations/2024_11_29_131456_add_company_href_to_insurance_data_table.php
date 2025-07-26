<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyHrefToInsuranceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->string('company_href')->nullable()->after('balance'); // Pievieno jaunu kolonnu
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
            $table->dropColumn('company_href'); // Noņem kolonnu, ja migrācija tiek atsaukta
        });
    }
}

