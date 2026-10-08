<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_attempt_id')
                ->constrained('exam_attempts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('question_option_id')
                ->nullable()
                ->constrained('question_options')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->text('answer_text')->nullable();

            $table->decimal('points_earned', 5, 2)->nullable();

            $table->timestamps();

            $table->unique(['exam_attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
