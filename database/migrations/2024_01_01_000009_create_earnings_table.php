<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('session_duration_hours', 5, 2); // e.g. 1.0, 0.75, 0.5
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('amount', 10, 2); // session_duration_hours * hourly_rate
            $table->decimal('platform_fee', 10, 2)->default(0.00); // admin cut
            $table->decimal('net_amount', 10, 2); // amount - platform_fee
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->string('payout_ref')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'status']);
            $table->index('created_at'); // for monthly reports
        });

        // Payout requests
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['requested', 'approved', 'processing', 'completed', 'rejected'])->default('requested');
            $table->string('payment_method')->nullable(); // bank, paypal, etc.
            $table->json('payment_details')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('earnings');
    }
};
