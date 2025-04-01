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
        //Tabla de productos
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->integer("barCode");
            $table->string("name");
            $table->string("description"); 
            $table->integer("currentStock");
            $table->integer("mintStock");
            $table->integer("maxStock");
            $table->decimal("purchasePrice");
            $table->decimal("salePrice"); 
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('batch_id')->nullable()->constrained();
            $table->foreignId('laboratory_id')->nullable()->constrained();
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
        Schema::dropIfExists('products');
    }
};
