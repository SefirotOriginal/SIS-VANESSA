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
        // 
        Schema::create('sale', function (Blueprint $table) {
            $table->id();
            $table->int('registerNumber');
            $table->string('receiptType');
            $table->decimal('amountPayment');
            $table->decimal('amountExchange');
            $table->decimal('amountTotal');
            $table->int('stock');
            $table->foreignId('user_id')->nullable()->constrained();
            // $table->foreignId('client_id')->nullable()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('sale');
    }
};
