<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primārā atslēga
            $table->string('key')->unique(); // Unikāla atslēga (piemēram, "2024-11-27-USD-EUR")
            $table->date('date')->index(); // Datums, kurā kurss tika noteikts
            $table->string('currency_from_url', 50);
            $table->string('currency_from', 10)->nullable()->index(); // Valūta no (piemēram, "USD")
            $table->string('currency_to_url', 50);
            $table->string('currency_to', 10)->nullable()->index(); // Valūta uz (piemēram, "EUR")
            $table->decimal('rate', 15, 8); // Kurss (piemēram, 1.23456789)
            $table->timestamps(); // `created_at` un `updated_at` lauki
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
};

