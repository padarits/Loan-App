<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigumiTable extends Migration
{
    /**
     * Palaišanas metode.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('powerbi_ligumi', function (Blueprint $table) {
            $table->uuid('guid')->primary(); // Primārā atslēga
            $table->string('pircejs'); // Pircēja nosaukums
            $table->string('ligums'); // Līguma identifikācija
            $table->date('noslegsanas_datums'); // Noslēgšanas datums
            $table->integer('m3akt_uzdots'); // Uzdotais daudzums (m3)
            $table->integer('m3akt_piegadats'); // Piegādātais daudzums (m3)
            $table->decimal('cena_par_m3', 10, 2); // Cena par m3 (ar divām zīmēm aiz komata)
            $table->string('valuta', 10)->nullable(); // Līguma valūta
            $table->date('izpildes_termins'); // Izpildes termiņš
            $table->integer('apmaksas_dienas');
            // Mēnešu lauki
            $table->integer('janvaris')->default(0); 
            $table->integer('februaris')->default(0); 
            $table->integer('marts')->default(0);
            $table->integer('aprilis')->default(0);
            $table->integer('maijs')->default(0);
            $table->integer('junijs')->default(0);
            $table->integer('julijs')->default(0);
            $table->integer('augusts')->default(0);
            $table->integer('septembris')->default(0);
            $table->integer('oktobris')->default(0);
            $table->integer('novembris')->default(0);
            $table->integer('decembris')->default(0);

            $table->integer('n_janvaris')->default(0); 
            $table->integer('n_februaris')->default(0); 
            $table->integer('n_marts')->default(0);
            $table->integer('n_aprilis')->default(0);
            $table->integer('n_maijs')->default(0);
            $table->integer('n_junijs')->default(0);
            $table->integer('Atlikums')->nullable();
            $table->integer('uzdots')->nullable();
            $table->integer('piegadats')->nullable();
            $table->string('tips', 50)->nullable(); // Cenas tips Nomināla/Aktuāla
            $table->timestamps(); // Izveides un atjaunināšanas laiks
        });
    }

    /**
     * Atcelšanas metode.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('powerbi_ligumi');
    }
}
