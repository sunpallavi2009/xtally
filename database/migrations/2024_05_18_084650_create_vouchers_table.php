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
            $table->string('company_guid')->nullable();
            $table->string('created_by')->nullable();
            $table->enum('type', ['receipt', 'payment', 'journal', 'contra', 'sales', 'unknown'])->nullable();
            $table->string('type_desc')->nullable();
            $table->string('voucher_number')->nullable();
            $table->date('voucher_date')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->mediumText('narration')->nullable();
            $table->tinyInteger('status')->default(0);
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