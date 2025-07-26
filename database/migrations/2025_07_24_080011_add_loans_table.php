<?php
use Illuminate\Database\Migrations\Migration;  
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\Facades\Schema;  
  
return new class extends Migration  
{  
    public function up()  
    {  
        Schema::create('loans', function (Blueprint $table) {  
            $table->id();  
            $table->uuid('user_id');  
            $table  
                ->foreign('user_id')  
                ->references('id')  
                ->on('users')  
                ->onDelete('cascade');  
            $table->decimal('amount', 12, 2);         // Loan amount  
            $table->decimal('interest_rate', 5, 2);   // Interest rate, e.g. 12.50 (%)  
            $table->integer('term');                  // Loan term (e.g., in months)  
            $table->date('start_date');               // Loan start date 
            $table->timestamps();  
        });  
    }  
  
    public function down()  
    {  
        Schema::dropIfExists('loans');  
    }  
};  
