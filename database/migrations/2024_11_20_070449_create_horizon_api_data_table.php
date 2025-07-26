<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorizonApiDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horizon_api_data', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid')->unique();
            $table->uuid('parent_guid')->nullable()->index();
            $table->uuid('session_guid')->index();
            $table->unsignedInteger('entry_number');
            $table->string('entry_path')->index();
            $table->string('entry_key')->index();
            $table->text('entry_value')->nullable();
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horizon_api_data');
    }
}

