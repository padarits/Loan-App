<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('transport_documents', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID kā primārais atslēgs // id();
            $table->string('supplier_name'); // Piegādātāja nosaukums
            $table->string('supplier_reg_number'); // Piegādātāja reģistrācijas numurs
            $table->string('supplier_address'); // Piegādātāja adrese
            $table->string('receiver_name'); // Saņēmēja nosaukums
            $table->string('receiver_reg_number'); // Saņēmēja reģistrācijas numurs
            $table->string('receiver_address'); // Saņēmēja adrese
            $table->string('issuer_name'); // Izsniedzēja vārds, uzvārds
            $table->string('receiver_person_name'); // Saņēmēja vārds, uzvārds
            $table->text('additional_info', 500)->nullable(); // Cita informācija
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transport_documents');
    }
}

