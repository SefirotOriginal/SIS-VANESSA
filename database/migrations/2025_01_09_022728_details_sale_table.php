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
        Schema::create("detailsSales", function (Blueprint $table) {
            $table->id();
            $table->decimal("salePrice");
            $table->integer("quantityProduct"); 
            $table->decimal("subTotal");
            // llave foranea para registrar el id del usuario 
            $table->foreignId('product_id')->nullable()->constrained();
            $table->foreignId('sale_id')->nullable()->constrained();
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
    }
};
