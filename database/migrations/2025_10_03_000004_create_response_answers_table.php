<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('response_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('answer')->nullable(); // For checkbox, store as JSON array
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('response_answers');
    }
};