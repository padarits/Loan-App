<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            // Add 'status' column with default value 'new' and an index
            $table->string('status')->default('010-new')->index();
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
            // Remove the index before dropping the column
            $table->dropIndex(['status']);
            // Remove the 'status' column
            $table->dropColumn('status');
        });
    }
}
