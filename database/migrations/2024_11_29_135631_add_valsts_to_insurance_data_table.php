<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValstsToInsuranceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_data', function (Blueprint $table) {
            $table->string('VALSTS', 10)->nullable(); // Pievieno jauno kolonnu
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
            $table->dropColumn('VALSTS'); // Noņem kolonnu
        });
    }
}

