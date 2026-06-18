<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref', 14)->unique();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            // Guest info (needed for guest bookings)
            $table->string('guest_name');
            $table->string('guest_phone', 15);
            $table->string('guest_email')->nullable();
            // Stay details
            $table->enum('stay_type', ['hourly', 'overnight']);
            $table->dateTime('checkin_at');
            $table->dateTime('checkout_at')->nullable();
            $table->unsignedTinyInteger('hours')->nullable();
            // Pricing
            $table->decimal('room_rate', 10, 2);
            $table->decimal('advance_amount', 10, 2);   // paid online
            $table->decimal('balance_amount', 10, 2);   // pay at hotel
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('offer_code')->nullable();
            // Payment type chosen by customer
            $table->enum('payment_type', ['partial', 'full'])->default('partial');
            // Booking status lifecycle
            $table->enum('status', [
                'pending',      // awaiting hotel acceptance
                'accepted',     // hotel accepted, awaiting payment confirmation
                'confirmed',    // payment confirmed, booking locked
                'rejected',     // hotel rejected
                'checked_in',   // customer arrived
                'completed',    // stay complete
                'cancelled',    // cancelled by customer/admin
                'no_show',      // customer didn't arrive
            ])->default('pending');
            $table->enum('payment_status', ['pending', 'advance_paid', 'fully_paid'])->default('pending');
            // Hotel response
            $table->timestamp('hotel_accepted_at')->nullable();
            $table->timestamp('hotel_rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('hotel_notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['guest_phone', 'status']);
            $table->index(['hotel_id', 'status']);
            $table->index(['customer_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};
