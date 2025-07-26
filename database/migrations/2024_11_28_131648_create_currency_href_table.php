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
        Schema::create('currency_href', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primārā atslēga
            $table->string('currency', 10)->unique(); // Valūtas kods (piemēram, "USD", "EUR")
            $table->string('href')->unique(); // Href saite
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
        Schema::dropIfExists('currency_href');
    }
};

