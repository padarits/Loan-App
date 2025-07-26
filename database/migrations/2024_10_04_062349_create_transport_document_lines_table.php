<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportDocumentLinesTable extends Migration
{
    public function up()
    {
        Schema::create('transport_document_lines', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primārais atslēgs; //->id();
            $table->uuid('transport_document_id'); // UUID kā ārējā atslēga ; //foreignId('transport_document_id')->constrained('transport_documents')->onDelete('cascade')->index(); // Atsauce uz transporta dokumenta galveni
            $table->string('product_code')->index(); // Preces artikuls
            $table->string('product_name')->index(); // Preces nosaukums
            $table->integer('quantity'); // Preces daudzums
            $table->decimal('price', 10, 2); // Cena
            $table->decimal('total', 10, 2); // Summa
            $table->timestamps();

            // Pievieno ārējo atslēgu uz 'transport_documents' ar kaskādes dzēšanu
            $table->foreign('transport_document_id')
                ->references('id')->on('transport_documents')
                ->onDelete('cascade');

            $table->index('transport_document_id'); // Indekss uz transport_document_id
        });
    }

    public function down()
    {
        Schema::table('transport_document_lines', function (Blueprint $table) {
            // Droši noņem ārējo atslēgu un indeksu pirms dzēšanas
            $table->dropForeign(['transport_document_id']);
            $table->dropIndex(['transport_document_id']);
        });
        
        Schema::dropIfExists('transport_document_lines');
    }
}

