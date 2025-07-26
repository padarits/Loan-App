<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVehicleRegistrationNumberToYourTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->string('vehicle_registration_number')->nullable()->after('status')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->dropColumn('vehicle_registration_number');
        });
    }
}
