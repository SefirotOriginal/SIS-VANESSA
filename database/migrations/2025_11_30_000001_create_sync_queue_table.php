<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sync_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_type');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->json('payload')->nullable();
            $table->integer('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sync_queue');
    }
};
