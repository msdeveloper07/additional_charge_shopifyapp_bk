<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('id');

            // The fees name
            $table->string('fees_name');

            // Name of the fees type
            $table->string('fees_type');

            // Price
            $table->string('price');

            // Store
            $table->string('store');

            // Currency
            $table->string('currency','50')->nullable(true);

            // Product Id
            $table->string('product_id','255')->nullable(true);

            // Variant Id
            $table->string('variant_id','255')->nullable(true);

            // Status
            $table->string('status','45')->default('active');

            // Provides created_at && updated_at columns
            // Provides created_at && updated_at columns
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fees');
    }
}
