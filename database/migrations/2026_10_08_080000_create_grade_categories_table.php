<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semesters')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('code', 30);
            $table->decimal('weight', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique([
                'school_year_id',
                'semester_id',
                'code',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_categories');
    }
};
