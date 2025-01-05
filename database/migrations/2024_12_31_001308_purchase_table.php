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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->float('totalCost');
            $table->int('Foil');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('purchase');
    }
};
