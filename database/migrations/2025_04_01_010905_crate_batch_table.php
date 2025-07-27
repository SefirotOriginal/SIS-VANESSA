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
        //Tabla de lotes
        Schema::create("batches", function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string("batch_number");
            $table->date("creation_date");
            $table->date("expiration_date");
            $table->integer("stock")->default(0);
            $table->integer("min_stock")->default(0);
            $table->integer("max_stock")->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'batch_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists("batches");
    }
};
