<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->text('question_text');

            $table->string('question_type', 30)->default('multiple_choice');

            $table->decimal('points', 5, 2)->default(1);

            $table->unsignedInteger('question_order')->default(1);

            $table->timestamps();

            $table->index(['exam_id', 'question_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
