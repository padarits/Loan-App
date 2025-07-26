<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceDataTable extends Migration
{
    public function up()
    {
        Schema::create('insurance_data', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primārā atslēga
            $table->string('nosauk')->nullable(); // Nosaukums
            $table->string('reg_nr')->nullable(); // Reģistrācijas numurs
            $table->decimal('summa_db_pv', 15, 2)->nullable(); // Summa ar decimāliem
            $table->string('href')->nullable(); // Saite
            $table->string('valuta')->nullable(); // Valūta
            $table->decimal('insured_amount', 15, 2)->nullable(); // Apdrošināta summa
            $table->string('insured_currency', 10)->nullable(); // Apdrošināta valūta
            $table->decimal('balance', 15, 2)->nullable(); // Atlikums
            $table->timestamps(); // Izveidots un atjaunināts
        });
    }

    public function down()
    {
        Schema::dropIfExists('insurance_data');
    }
}

