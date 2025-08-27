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
        Schema::create("purchase_details", function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_presentation_id')->constrained()->onDelete('restrict');
            $table->foreignId('batch_id')->nullable()->constrained()->onDelete('set null');

            $table->decimal("purchase_price", 10, 2);
            $table->decimal("sale_price", 10, 2);
            $table->integer("stock"); // Cantidad ingresada en esta compra
            $table->decimal("amount_total", 10, 2);

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
        Schema::dropIfExists("detailsPurchase");
    }
};
