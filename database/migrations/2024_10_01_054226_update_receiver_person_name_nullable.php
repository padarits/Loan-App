<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReceiverPersonNameNullable extends Migration
{
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->string('receiver_person_name')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            //$table->string('receiver_person_name')->nullable(false)->change();
        });
    }
}

