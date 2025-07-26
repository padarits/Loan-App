<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInvoiceHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_headers', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Unikāls rēķina numurs
            $table->date('invoice_date'); // Rēķina datums
            $table->string('supplier_name'); // Piegādātāja vārds
            $table->decimal('total_amount', 15, 2); // Kopējā summa
            $table->decimal('tax_amount', 15, 2); // PVN summa
            $table->decimal('net_amount', 15, 2); // Neto summa (kopējā summa bez PVN)
            $table->timestamps(); // Pievieno created_at un updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_invoice_headers');
    }
}

