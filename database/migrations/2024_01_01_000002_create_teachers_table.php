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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('specialization')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0.00);
            $table->json('subjects')->nullable(); // courses they teach
            $table->string('education')->nullable();
            $table->string('certification')->nullable();
            $table->string('nationality')->nullable();
            $table->string('language')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0.00);
            $table->decimal('pending_payout', 12, 2)->default(0.00);
            $table->enum('status', ['pending', 'approved', 'suspended'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
