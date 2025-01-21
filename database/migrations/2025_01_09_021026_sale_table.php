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
        //tabla de ventas
        Schema::create("sales", function (Blueprint $table) {
            $table->id();
            $table->integer("referenceNumber");
            $table->string("receiptType"); 
            $table->decimal("amountPayment");
            $table->decimal("amountExchange");
            $table->decimal("amountTotal");
            // llave foranea para registrar el id del usuario 
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('sales');
    }
};
