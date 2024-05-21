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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('ledger_guid');
            $table->foreign('ledger_guid')->references('guid')->on('tally_ledgers')->onDelete('cascade');

            $table->string('type')->nullable();
            $table->json('json')->nullable();
            $table->string('voucher_number')->nullable();
            $table->date('voucher_date')->nullable();
            $table->decimal('amount')->nullable();
            $table->mediumText('narration')->nullable();
            // $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
