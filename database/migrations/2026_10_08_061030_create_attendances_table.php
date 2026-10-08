<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attendance_session_id')
                ->constrained('attendance_sessions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('status', 20)->default('present');

            $table->time('check_in')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'attendance_session_id',
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
        Schema::dropIfExists('attendances');
    }
};
