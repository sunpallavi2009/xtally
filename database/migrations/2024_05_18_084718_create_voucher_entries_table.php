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
        Schema::create('voucher_entries', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_id')->nullable();
            //$table->foreign('voucher_id')->references('ledger_guid')->on('vouchers')->onDelete('cascade');
            $table->string('ledger')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('account')->nullable();
            $table->string('type')->nullable();
            $table->mediumText('narration')->nullable();
            $table->enum('entry_type', ['debit', 'credit', 'unknown'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_entries');
    }
};
