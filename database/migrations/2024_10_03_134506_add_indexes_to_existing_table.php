<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToExistingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_documents', function (Blueprint $table) {
            $table->index('document_number');
            $table->index('document_date');
            $table->index('supplier_name');
            $table->index('supplier_reg_number');
            $table->index('supplier_address');
            $table->index('receiver_name');
            $table->index('receiver_reg_number');
            $table->index('receiver_address');
            $table->index('issuer_name');
            $table->index('receiver_person_name');
            $table->index('receiving_location');
            //$table->index('additional_info');
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
            $table->dropIndex(['document_number']);
            $table->dropIndex(['document_date']);
            $table->dropIndex(['supplier_name']);
            $table->dropIndex(['supplier_reg_number']);
            $table->dropIndex(['supplier_address']);
            $table->dropIndex(['receiver_name']);
            $table->dropIndex(['receiver_reg_number']);
            $table->dropIndex(['receiver_address']);
            $table->dropIndex(['issuer_name']);
            $table->dropIndex(['receiver_person_name']);
            $table->dropIndex(['receiving_location']);
            $table->dropIndex(['additional_info']);
        });
    }
}

