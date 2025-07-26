<?php
use Illuminate\Database\Migrations\Migration;  
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\Facades\Schema;  
  
return new class extends Migration  
{  
    public function up()  
    {  
        Schema::create('payments', function (Blueprint $table) {  
            $table->id();  
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');  
            $table->date('payment_date');   // Payment date  
            $table->decimal('amount', 12, 2); // Payment amount 
            $table->boolean('status')->default(false); // Paid / Unpaid 
            $table->timestamps();  
        });  
    }  
  
    public function down()  
    {  
        Schema::dropIfExists('payments');  
    }  
};  