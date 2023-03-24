<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id('inventory_adjustment_id');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('item_id')->on('items')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('adjust_option')->nullable(0);
            $table->double('adjusted_qty')->default(0);

            $table->double('current_qty')->default(0);

            $table->string('remarks')->nullable(0);
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
        Schema::dropIfExists('inventory_adjustments');
    }
}
