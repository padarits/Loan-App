<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentDateStrToTransportDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->string('document_date_str', 30)->nullable()->after('document_date')->index();
        });
    }

    public function down()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->dropColumn('document_date_str');
        });
    }
}

