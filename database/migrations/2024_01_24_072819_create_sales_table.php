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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no');
            $table->foreignId('customer_id')->reference('id')->on('customers');
            $table->double('total');
            $table->double('subtotal');
            $table->double('paid_amount')->nullable();
            $table->double('due_amount')->nullable();
            $table->string('status')->default(STATUS_PAID)->comment(STATUS_PAID, STATUS_DUE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
