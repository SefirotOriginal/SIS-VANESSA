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
        Schema::table('batches', function (Blueprint $table) {
            $table->dropForeign('batches_product_presentation_id_foreign');
    
            $table->foreign('product_presentation_id')
                  ->references('id')
                  ->on('product_presentations')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            // 1. Elimina la llave foránea que se creó en el método up()
            $table->dropForeign(['product_presentation_id']);
            
            // 2. Vuelve a crear la llave foránea original (sin borrado en cascada)
            $table->foreign('product_presentation_id')
                  ->references('id')
                  ->on('product_presentations')
                  ->onDelete('restrict'); // 'restrict' es el comportamiento por defecto
        });
    }
};
