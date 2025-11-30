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
        Schema::create('cash_cut_has_purchases', function (Blueprint $table) {
            $table->id();

            // Relación con la tabla cash_cuts
            $table->foreignId('cash_cut_id')
                  ->constrained('cash_cuts')
                  ->onDelete('cascade'); // Si se borra el corte, se borra la relación

            // Relación con la tabla purchases
            $table->foreignId('purchase_id')
                  ->constrained('purchases')
                  ->onDelete('cascade'); // Si se borra la compra, se borra la relación

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_cut_has_purchases');
    }
};