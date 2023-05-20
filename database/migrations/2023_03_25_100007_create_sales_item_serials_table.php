<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesItemSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_item_serials', function (Blueprint $table) {
            $table->id('sales_item_serial_id');

            $table->unsignedBigInteger('sales_detail_id');
            $table->foreign('sales_detail_id')->references('sales_detail_id')->on('sales_details')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('sales_id');
            $table->foreign('sales_id')->references('sales_id')->on('sales')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('item_id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('serial')->nullable();

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
        Schema::dropIfExists('sales_item_serials');
    }
}
