<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    public function up()
    {
        Schema::create('power_bi_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // GUID kā primārā atslēga
            $table->string('pircjs'); // pircējs
            $table->string('ligums'); // līgums
            $table->date('noslegsanas_datums'); // noslēgšanas datums
            $table->float('m3_akt_uzdots')->nullable(); // m3 akt. uzdots
            $table->float('m3_nom_uzdots')->nullable(); // m3 nom. uzdots
            $table->float('m3_akt_piegadats')->nullable(); // m3 akt. piegādāts
            $table->float('m3_nom_piegadats')->nullable(); // m3 nom. piegādāts
            $table->float('m3_akt_osta')->nullable(); // m3 akt. osta
            $table->float('m3_nom_osta')->nullable(); // m3 nom. osta
            $table->float('m3_akt_rupnica')->nullable(); // m3 akt. rūpnīcā
            $table->float('m3_nom_rupnica')->nullable(); // m3 nom. rūpnīcā
            $table->float('cena_par_nom')->nullable(); // cena par nom.
            $table->float('cena_par_akt')->nullable(); // cena par akt.
            $table->string('valuta')->nullable(); // valūta
            $table->float('cena_fraht')->nullable(); // cena fraht
            $table->string('valuta_fraht')->nullable(); // valūta fraht
            $table->date('termins')->nullable(); // termiņš
            $table->string('piegades_nosacijumi')->nullable(); // piegādes nosacījumi
            $table->string('osta')->nullable(); // osta
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('power_bi_contracts');
    }
}
