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
        //tabla de proveedores
        Schema::create("providers", function (Blueprint $table) {
            $table->id();
            $table->integer("referenceNumber");
            $table->string("companyName");
            $table->string("email");
            $table->integer("phoneNumber");
            $table->string("address");
            $table->string("city");
            $table->string("state");
            $table->string("country");
            $table->string("zipCode");
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
        Schema::dropIfExists('providers');
    }
};
