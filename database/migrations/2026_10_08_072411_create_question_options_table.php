<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('option_label', 10);
            $table->text('option_text');

            $table->boolean('is_correct')->default(false);

            $table->unsignedInteger('option_order')->default(1);

            $table->timestamps();

            $table->index(['question_id', 'option_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
