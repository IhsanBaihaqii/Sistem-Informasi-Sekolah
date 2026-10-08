<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semesters')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title', 200);
            $table->text('description')->nullable();

            $table->timestamp('start_at')->nullable();
            $table->timestamp('due_at')->nullable();

            $table->decimal('max_score', 5, 2)->default(100);

            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();

            $table->boolean('is_published')->default(false);
            $table->boolean('allow_late_submission')->default(false);

            $table->timestamps();

            $table->index([
                'classroom_id',
                'subject_id',
            ]);

            $table->index('due_at');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
