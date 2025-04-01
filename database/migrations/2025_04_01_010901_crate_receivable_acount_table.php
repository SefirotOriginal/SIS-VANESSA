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
        //Tabla de cuentas por cobrar
        Schema::create("receivableAccounts", function (Blueprint $table) {
            $table->id();
            $table->integer("movementType"); 
            $table->decimal("amount");
            $table->decimal("balancePostMovement");
            $table->string("description");
            // llave foranea para registrar el id del usuario 
            $table->foreignId('client_id')->nullable()->constrained();
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
        Schema::dropIfExists('receivableAccounts');
    }
};
