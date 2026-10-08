<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semesters')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->time('opened_at')->nullable();
            $table->time('closed_at')->nullable();

            $table->string('status', 20)->default('open');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'schedule_id',
                'attendance_date',
            ]);

            $table->index([
                'classroom_id',
                'attendance_date',
            ]);

            $table->index([
                'teacher_id',
                'attendance_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
