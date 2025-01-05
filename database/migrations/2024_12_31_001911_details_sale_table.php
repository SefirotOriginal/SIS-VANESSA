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
        Schema::create('details_sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('salePrice');
            $table->int('quantityProduct');
            $table->decimal('subTotal');
            $table->int('stock');
            $table->foreignId('sale_id')->nullable()->constrained();
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
        Schema::dropIfExists('details_sales');
    }
};
