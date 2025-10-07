<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['short_text', 'radio', 'checkbox', 'dropdown', 'date']);
            $table->boolean('required')->default(false);
            $table->json('options')->nullable(); // For radio, checkbox, dropdown options
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};