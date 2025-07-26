<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrationNumbersToPurchaseInvoiceHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_invoice_headers', function (Blueprint $table) {
            $table->string('buyer_registration_number')->nullable(); // Pircēja reģistrācijas numurs
            $table->string('seller_registration_number')->nullable(); // Pārdevēja reģistrācijas numurs
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoice_headers', function (Blueprint $table) {
            $table->dropColumn(['buyer_registration_number', 'seller_registration_number']);
        });
    }
}

