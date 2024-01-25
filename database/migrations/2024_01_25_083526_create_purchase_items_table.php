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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->reference('id')->on('suppliers');
            $table->foreignId('purchase_id')->reference('id')->on('purchases')->onDelete('cascade');
            $table->foreignId('product_id')->reference('id')->on('products');
            $table->string('batch')->nullable();
            $table->integer('qty')->default(0);
            $table->double('sale_price');
            $table->double('purchase_price');
            $table->double('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
