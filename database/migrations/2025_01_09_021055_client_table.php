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
        Schema::create("clients", function (Blueprint $table) {
            $table->id();
            $table->integer("clientNumber");
            $table->string("name");
            $table->string("email");
            $table->string("phoneNumber");
            $table->string("address");
            $table->decimal("currentBalance");
            $table->decimal("limitBalance");
            $table->decimal("outstandingBalance");
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
        Schema::dropIfExists("clients");
    }
};
