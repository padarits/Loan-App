<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseMaterialMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_material_movements', function (Blueprint $table) {
            //$table->id();
            $table->uuid('guid')->primary();
            $table->string('article_id')->index();
            $table->date('date')->index();
            $table->string('code')->index();
            $table->enum('status', ['R', 'Ri', 'N', 'M', '-'])->nullable()->index();
            $table->string('order_number')->index();
            $table->string('name')->index();
            $table->string('name_2')->nullable();
            $table->string('material_grade')->index();
            $table->string('unit');
            $table->decimal('quantity', 18, 6);
            $table->decimal('price_per_unit', 18, 6)->nullable();
            $table->decimal('total_price', 18, 6)->nullable();
            $table->string('supplier')->index();
            $table->string('recipient')->nullable()->index();
            $table->date('due_date')->nullable()->index();
            $table->string('invoice_number')->nullable()->index();
            $table->string('supplier_company')->nullable()->index();
            $table->date('warehouse_date')->nullable()->index();
            $table->boolean('issued')->default(false)->index();
            $table->string('code_2')->nullable()->index();
            $table->dateTime('loaded_at')->nullable()->index();
            $table->enum('type', [
                '010_none',
                '020_application',
                '030_received',
                '040_dispensed',
                '045_sent',
                '050_written_off',
                '060_added_to_inventory',
                '070_removed_from_inventory',
                '080_in_transit',
                '090_canceled',
                '100_balance'
                                ])->default('010_none')->index();
            $table->decimal('internal_warehouse_sum', 18, 6)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_material_movements');
    }
}

