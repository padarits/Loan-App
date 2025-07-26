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
            $table->decimal('quantity', 18, 6)->change(); // Update 'price' column
        });
    }
    
    public function down()
    {
        Schema::table('transport_document_lines', function (Blueprint $table) {
            // Revert the changes if necessary
            $table->decimal('quantity', 10, 2)->change(); // Revert 'price' column
        });
    }
    
};
