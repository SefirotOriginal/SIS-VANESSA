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
        //Tabla de compras
        Schema::create("purchases", function (Blueprint $table) {
            $table->id();
            $table->integer("reference_number")->unique();
            $table->string("receipt_type");
            $table->decimal("amountTotal", 10, 2);

            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('provider_id')->constrained();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("purchases");
    }
};
