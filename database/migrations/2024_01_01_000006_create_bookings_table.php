<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref')->unique(); // PI-2024-XXXXXX
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('scheduled_at'); // UTC datetime
            $table->integer('duration')->default(60); // minutes
            $table->enum('status', ['pending', 'approved', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->enum('booking_type', ['single', 'package'])->default('single');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('meeting_link')->nullable();
            $table->string('meeting_id')->nullable();
            $table->text('student_notes')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'status']);
            $table->index(['teacher_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
