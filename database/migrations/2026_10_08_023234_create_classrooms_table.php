<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('major_id')
                ->nullable()
                ->constrained('majors')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('name', 100);

            $table->unsignedInteger('grade_level');

            $table->foreignId('homeroom_teacher_id')
                ->nullable()
                ->constrained('teachers')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('capacity')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'school_year_id',
                'name',
            ]);

            $table->index('grade_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
