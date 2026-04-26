<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('curriculum')->nullable();
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced, all
            $table->decimal('price_per_session', 10, 2)->default(0.00);
            $table->decimal('monthly_price', 10, 2)->default(0.00);
            $table->integer('sessions_per_week')->default(1);
            $table->integer('duration_minutes')->default(60); // session duration
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('enrolled_count')->default(0);
            $table->timestamps();
        });

        // Student course enrollments
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->integer('progress_percentage')->default(0);
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_student');
        Schema::dropIfExists('courses');
    }
};
