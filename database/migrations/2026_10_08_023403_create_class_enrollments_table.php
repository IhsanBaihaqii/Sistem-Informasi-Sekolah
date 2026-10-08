<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semesters')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->date('enrolled_at')
                ->nullable();

            $table->date('ended_at')
                ->nullable();

            $table->string('status', 30)
                ->default('active');

            $table->string('notes')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'student_id',
                'classroom_id',
                'semester_id',
            ]);

            $table->index([
                'student_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};
