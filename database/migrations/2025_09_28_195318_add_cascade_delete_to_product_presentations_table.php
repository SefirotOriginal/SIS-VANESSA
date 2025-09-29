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
        Schema::table('product_presentations', function (Blueprint $table) {
            // Primero, eliminamos la llave foránea existente.
            // El nombre 'product_presentations_product_id_foreign' es el estándar de Laravel.
            // Si le pusiste otro nombre, ajústalo aquí.
            $table->dropForeign('product_presentations_product_id_foreign');

            // Luego, la volvemos a crear con la regla 'cascadeOnDelete()'
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->cascadeOnDelete(); // <-- ¡Esta es la magia!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_presentations', function (Blueprint $table) {
            // Esto es para poder revertir la migración si es necesario
            $table->dropForeign(['product_id']);
            
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products'); // La dejamos sin la regla de cascada
        });
    }
};