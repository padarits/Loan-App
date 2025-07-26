<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transport_document_lines', function (Blueprint $table) {
            $table->decimal('price', 18, 6)->change(); // Update 'price' column
            $table->decimal('total', 18, 6)->change(); // Update 'total' column
        });
    }
    
    public function down()
    {
        Schema::table('transport_document_lines', function (Blueprint $table) {
            // Revert the changes if necessary
            $table->decimal('price', 10, 2)->change(); // Revert 'price' column
            $table->decimal('total', 10, 2)->change(); // Revert 'total' column
        });
    }
    
};
