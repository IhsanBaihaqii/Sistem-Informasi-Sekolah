<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_academic_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semesters')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('final_score', 5, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->json('attendance_summary')->nullable();
            $table->string('academic_status', 50)->default('active'); // active, promoted, retained, graduated
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'student_id',
                'school_year_id',
                'semester_id',
                'classroom_id',
            ], 'unique_student_academic_history');

            $table->index(['student_id', 'school_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_academic_histories');
    }
};
