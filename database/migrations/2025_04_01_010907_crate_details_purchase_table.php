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
        Schema::create("detailsPurchases", function (Blueprint $table) {
            $table->id();
            $table->decimal("purchasePrice"); 
            $table->decimal("salePrice");
            $table->integer("stock");
            $table->decimal("amountTotal");
            $table->foreignId('purchase_id')->nullable()->constrained();
            $table->foreignId('product_id')->nullable()->constrained();
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
