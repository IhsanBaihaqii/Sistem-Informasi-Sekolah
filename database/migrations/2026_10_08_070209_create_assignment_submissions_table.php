<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('submitted_at')->nullable();

            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();

            $table->text('answer')->nullable();

            $table->decimal('score', 5, 2)->nullable();

            $table->text('teacher_feedback')->nullable();

            $table->string('status', 30)->default('draft');

            $table->timestamps();

            $table->unique([
                'assignment_id',
                'student_id',
            ]);

            $table->index([
                'student_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
