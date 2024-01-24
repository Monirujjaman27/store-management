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
        Schema::create('borrower_transection_historeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->reference('id')->on('borrowers')->onDelete('cascade');
            $table->string('transection_id');
            $table->string('transection_amount');
            $table->enum('type', ['credit', 'debit'])->comment('credit = lend Amount , debit=return Amount ');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrower_transection_historeys');
    }
};
