<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('nip', 30)->nullable()->unique();
            $table->string('nuptk', 30)->nullable()->unique();

            $table->string('full_name', 150);

            $table->string('gender', 20);
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();

            $table->string('religion', 30)->nullable();

            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();

            $table->string('education', 100)->nullable();

            $table->string('employment_status', 50)->nullable();

            $table->date('join_date')->nullable();

            $table->string('photo')->nullable();

            $table->string('status', 20)->default('active');

            $table->timestamps();

            $table->index('full_name');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
