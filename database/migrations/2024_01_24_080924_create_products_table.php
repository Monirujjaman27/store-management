<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->reference('id')->on('product_categories');
            $table->string('name');
            $table->double('purchase_price');
            $table->double('sales_price');
            $table->boolean('is_offer')->default(STATUS_FALSE);
            $table->double('offer_price')->nullable();
            $table->double('stock_quantity')->nullable();
            $table->double('sales_quantity')->nullable();
            $table->double('stock_alert_quantity')->nullable();
            $table->string('images')->nullable()->comment('we will add json data into string');
            $table->boolean('status')->default(STATUS_TRUE);
            $table->longText('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
