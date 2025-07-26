<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalSumToVarcharInTransportDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            // Maini total_sum kolonnu uz varchar(30)
            $table->string('total_sum', 30)->nullable()->after('status')->index();
        });
    }

    public function down()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            // Atgriež total_sum atpakaļ uz decimal(10, 2), ja nepieciešams
            $table->dropColumn('total_sum');
        });
    }
}

