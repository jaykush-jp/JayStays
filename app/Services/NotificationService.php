<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Dispatch all notifications when a booking is created
     */
    public function bookingCreated(Booking $booking): void
    {
        $booking->load(['hotel', 'room', 'customer']);

        // 1. Notify hotel owner
        $this->notifyUser(
            $booking->hotel->user_id,
            'booking_new',
            '🔔 New Booking Request!',
            "New booking #{$booking->booking_ref} for {$booking->room->name}. Check-in: {$booking->checkin_at->format('d M Y, h:i A')}. Guest: {$booking->guest_name}.",
            ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
        );

        // 2. Notify admins
        User::where('role', 'admin')->where('status', 'active')->each(function ($admin) use ($booking) {
            $this->notifyUser(
                $admin->id,
                'booking_new_admin',
                "New Booking: #{$booking->booking_ref}",
                "Booking at {$booking->hotel->name}, {$booking->hotel->city}. Guest: {$booking->guest_name}.",
                ['booking_id' => $booking->id]
            );
        });

        // 3. Notify customer (SMS)
        $this->sendSms(
            $booking->guest_phone,
            "MyRoom: Booking #{$booking->booking_ref} received! Awaiting hotel confirmation. We'll notify you shortly. Track: myroom.in/track"
        );

        // 4. Notify customer in-app if logged in
        if ($booking->customer_id) {
            $this->notifyUser(
                $booking->customer_id,
                'booking_created',
                "Booking Submitted — #{$booking->booking_ref}",
                "Your booking at {$booking->hotel->name} is pending hotel confirmation.",
                ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
            );
        }

        // 5. Send hotel owner SMS
        $hotelOwner = $booking->hotel->owner;
        if ($hotelOwner?->phone) {
            $this->sendSms(
                $hotelOwner->phone,
                "MyRoom: New booking #{$booking->booking_ref} for {$booking->room->name}. Check-in: {$booking->checkin_at->format('d M h:i A')}. Login to accept/reject."
            );
        }
    }

    /**
     * Hotel accepted the booking
     */
    public function bookingAccepted(Booking $booking): void
    {
        $booking->load(['hotel', 'room']);

        // Notify customer
        $this->sendSms(
            $booking->guest_phone,
            "✅ MyRoom: Booking #{$booking->booking_ref} CONFIRMED by {$booking->hotel->name}! Check-in: {$booking->checkin_at->format('d M Y, h:i A')}. Balance: ₹{$booking->balance_amount} at hotel."
        );

        if ($booking->customer_id) {
            $this->notifyUser(
                $booking->customer_id,
                'booking_accepted',
                "✅ Booking Confirmed — #{$booking->booking_ref}",
                "{$booking->hotel->name} has confirmed your booking. See you on {$booking->checkin_at->format('d M Y')}!",
                ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
            );
        }
    }

    /**
     * Hotel rejected the booking
     */
    public function bookingRejected(Booking $booking): void
    {
        $booking->load(['hotel']);

        $reason = $booking->rejection_reason ? " Reason: {$booking->rejection_reason}." : '';
        $this->sendSms(
            $booking->guest_phone,
            "❌ MyRoom: Booking #{$booking->booking_ref} was declined by {$booking->hotel->name}.{$reason} Refund initiated in 5-7 days."
        );

        if ($booking->customer_id) {
            $this->notifyUser(
                $booking->customer_id,
                'booking_rejected',
                "Booking Declined — #{$booking->booking_ref}",
                "{$booking->hotel->name} declined your booking.{$reason} Your payment will be refunded.",
                ['booking_id' => $booking->id, 'booking_ref' => $booking->booking_ref]
            );
        }
    }

    /**
     * Booking cancelled by customer/admin
     */
    public function bookingCancelled(Booking $booking): void
    {
        $booking->load(['hotel']);

        // Notify hotel owner
        $this->notifyUser(
            $booking->hotel->user_id,
            'booking_cancelled',
            "Booking Cancelled — #{$booking->booking_ref}",
            "Booking #{$booking->booking_ref} for {$booking->checkin_at->format('d M Y')} has been cancelled.",
            ['booking_id' => $booking->id]
        );

        // Notify customer
        $this->sendSms(
            $booking->guest_phone,
            "MyRoom: Booking #{$booking->booking_ref} cancelled. Refund (if applicable) in 5-7 days."
        );
    }

    /**
     * Core: Create in-app notification
     */
    public function notifyUser(int $userId, string $type, string $title, string $message, array $data = []): void
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            Log::error("Notification failed for user {$userId}: " . $e->getMessage());
        }
    }

    /**
     * Send SMS via Fast2SMS
     */
    public function sendSms(string $phone, string $message): void
    {
        try {
            $apiKey = env('FAST2SMS_API_KEY');
            if (!$apiKey) return;

            Http::withHeaders(['authorization' => $apiKey])
                ->post('https://www.fast2sms.com/dev/bulkV2', [
                    'route'    => 'q',
                    'numbers'  => $phone,
                    'message'  => $message,
                    'language' => 'english',
                    'flash'    => 0,
                ]);
        } catch (\Exception $e) {
            Log::error("SMS failed to {$phone}: " . $e->getMessage());
        }
    }

    /**
     * Send OTP SMS
     */
    public function sendOtp(string $phone, string $otp): void
    {
        $this->sendSms($phone, "Your MyRoom OTP is {$otp}. Valid for 10 minutes. Do not share.");
    }
}
