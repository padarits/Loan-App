<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Izmanto UUID kā primāro atslēgu
            $table->string('name'); // Departamenta nosaukums
            $table->string('code')->unique(); // Departamenta kods
            $table->string('parent_code')->nullable(); //
            $table->string('contact_person')->nullable(); // Kontaktperson
            $table->string('email')->nullable(); // E-pasts
            $table->string('phone')->nullable(); // Telefona numurs
            $table->string('address')->nullable(); // Adrese
            $table->string('city')->nullable(); // Pielikums
            $table->string('country')->nullable(); // Valsts
            $table->string('zip')->nullable(); // Pasts
            $table->text('description')->nullable(); // Apraksts
            $table->timestamps(); // Izveides un atjaunināšanas laika zīmogi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};

