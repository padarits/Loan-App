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
        Schema::create('employee_for_position', function (Blueprint $table) {
            $table->uuid('id')->primary(); // GUID kā primārā atslēga
            //$table->string('employee_name'); // Darbinieka vārds
            $table->uuid('employee_id'); // Darbinieka GUID
            $table->uuid('department_id')->nullable()->index(); // Departamenta GUID
            $table->uuid('position_id'); // Amata GUID
            $table->boolean('is_head')->default(false); // Lauks is_head ar noklusējumu false
            $table->timestamps(); // Lauki `created_at` un `updated_at`

            // Definējam ārējo atslēgu
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            // Definējam ārējo atslēgu
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            // Definējam ārējo atslēgu
            $table->foreign('position_id')->references('id')->on('employee_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_for_position');
    }
};
