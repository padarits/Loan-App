<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseClassifiersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expense_classifiers', function (Blueprint $table) {
            $table->uuid('id')->primary(); // GUID ID
            $table->uuid('parent_id')->nullable(); // GUID for parent ID
            $table->string('code')->unique(); // Code
            $table->string('name')->index(); // Name
            $table->string('name_for_search')->index(); // Name for search
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_classifiers');
    }
}
