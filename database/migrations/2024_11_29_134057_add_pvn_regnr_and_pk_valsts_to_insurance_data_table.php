<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPvnRegnrAndPkValstsToInsuranceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->string('PVN_REGNR')->nullable();  // Jauns lauks
            $table->string('PK_VALSTS')->nullable();  // Jauns lauks
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
            $table->dropColumn(['PVN_REGNR', 'PK_VALSTS']);
        });
    }
}

