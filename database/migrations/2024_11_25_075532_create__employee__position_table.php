<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_positions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // GUID kā primārā atslēga
            $table->string('position_name'); // Amata nosaukums
            $table->uuid('position_for_department_id')->nullable()->index(); // Atsauce uz employee_for_position
            $table->boolean('is_head')->default(false); // Lauks is_head ar noklusējumu false
            $table->timestamps();

            // Ārējā atslēga uz employee_for_position
            // $table->foreign('position_for_department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_positions');
    }
};
