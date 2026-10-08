<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

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

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedTinyInteger('day_of_week');

            $table->time('start_time');
            $table->time('end_time');

            $table->string('notes')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index([
                'school_year_id',
                'semester_id',
                'day_of_week',
            ]);

            $table->index([
                'teacher_id',
                'day_of_week',
            ]);

            $table->index([
                'classroom_id',
                'day_of_week',
            ]);

            $table->index([
                'room_id',
                'day_of_week',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
