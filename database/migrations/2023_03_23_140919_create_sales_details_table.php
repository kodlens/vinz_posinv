<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id('sales_detail_id');
            $table->unsignedBigInteger('sales_id');
            $table->foreign('sales_id')->references('sales_id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('item_id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('item_name')->nullable();
            $table->double('qty')->default(0);
            $table->double('price')->default(0);

            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_details');
    }
}
