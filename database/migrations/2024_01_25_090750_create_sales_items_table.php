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
        Schema::create('sales_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->reference('id')->on('customers');
            $table->foreignId('sale_id')->reference('id')->on('sales')->onDelete('cascade');
            $table->foreignId('product_id')->reference('id')->on('products');
            $table->integer('qty')->default(0);
            $table->double('price');
            $table->double('total_price');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_items');
    }
};
