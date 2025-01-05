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
        Schema::create('details_purchase', function (Blueprint $table) {
            $table->id();
            $table->decimal('purchasePrice');
            $table->decimal('salePrice');
            $table->int('stock');
            $table->foreignId('product_id')->nullable()->constrained();
            $table->foreignId('purchase_id')->nullable()->constrained();
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
        Schema::dropIfExists('details_purchase');
    }
};
