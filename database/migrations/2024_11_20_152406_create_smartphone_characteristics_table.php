<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('smartphone_characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smartphone_id')->constrained()->onDelete('cascade');
            $table->string('characteristic');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('smartphone_characteristics');
    }
};
