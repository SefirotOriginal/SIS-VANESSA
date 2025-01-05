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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('barCode');
            $table->string('name');
            $table->string('description');
            $table->decimal('unitePrice');
            $table->int('currentStock');
            $table->int('maxStock');
            $table->int('minStock');
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('batch_id')->nullable()->constrained();
            $table->foreignId('laboratory_id')->constrained();
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
