<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToPurchaseInvoiceHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_invoice_headers', function (Blueprint $table) {
            $table->string('buyer_name')->nullable(); // Pircēja vārds
            $table->string('buyer_address')->nullable(); // Pircēja adrese
            $table->string('seller_name')->nullable(); // Pārdevēja vārds
            $table->string('seller_address')->nullable(); // Pārdevēja adrese
            $table->string('waybill_number')->nullable(); // Pavadzīmes numurs
            $table->date('waybill_date')->nullable(); // Pavadzīmes datums
            $table->text('additional_info')->nullable(); // Papildus informācija
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
            $table->dropColumn([
                'buyer_name',
                'buyer_address',
                'seller_name',
                'seller_address',
                'waybill_number',
                'waybill_date',
                'additional_info',
            ]);
        });
    }
}

