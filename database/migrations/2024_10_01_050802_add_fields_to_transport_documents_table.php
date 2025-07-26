<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTransportDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->string('document_number')->after('id'); // Dokumenta numurs
            $table->date('document_date')->after('document_number'); // Dokumenta datums
            $table->string('receiving_location')->after('receiver_person_name'); // Saņemšanas vieta
        });
    }

    public function down()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->dropColumn('document_number');
            $table->dropColumn('document_date');
            $table->dropColumn('receiving_location');
        });
    }
}
