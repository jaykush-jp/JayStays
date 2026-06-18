<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api as RazorpayApi;

class BookingService
{
    public function __construct(private NotificationService $notifications) {}

    /**
     * Create a booking from the booking form request
     */
    public function createBooking(Request $request, Hotel $hotel, Room $room): Booking
    {
        $stayType  = $request->stay_type;
        $hours     = $request->hours ?? 2;

        // Calculate room cost
        if ($stayType === 'hourly') {
            $roomRate = $room->getPriceForHours((int) $hours);
        } else {
            $roomRate = $room->overnight_price;
        }

        // Apply offer
        $discount  = 0;
        $offerCode = null;
        if ($request->offer_code) {
            $offer = Offer::where('code', strtoupper($request->offer_code))
                ->where('is_active', true)
                ->first();
            if ($offer && $offer->isValid($roomRate)) {
                $discount  = $offer->calculateDiscount($roomRate);
                $offerCode = $offer->code;
                $offer->increment('used_count');
            }
        }

        $netRate = $roomRate - $discount;
        $commPct = $hotel->effective_commission;

        // Advance = commission % of net rate
        $advance = round($netRate * $commPct / 100, 2);
        $balance = max(0, $netRate - $advance);

        // If customer chose full payment
        $paymentType = $request->payment_type ?? 'partial';
        if ($paymentType === 'full') {
            $advance = $netRate;
            $balance = 0;
        }

        // Checkout time
        $checkoutAt = $stayType === 'hourly'
            ? (new \DateTime($request->checkin_at))->modify("+{$hours} hours")->format('Y-m-d H:i:s')
            : (new \DateTime($request->checkin_at))->modify('+24 hours')->format('Y-m-d H:i:s');

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'customer_id'    => auth()->id(),
                'hotel_id'       => $hotel->id,
                'room_id'        => $room->id,
                'guest_name'     => $request->guest_name,
                'guest_phone'    => $request->guest_phone,
                'guest_email'    => $request->guest_email,
                'stay_type'      => $stayType,
                'checkin_at'     => $request->checkin_at,
                'checkout_at'    => $checkoutAt,
                'hours'          => $stayType === 'hourly' ? $hours : null,
                'room_rate'      => $netRate,
                'advance_amount' => $advance,
                'balance_amount' => $balance,
                'discount_amount'=> $discount,
                'offer_code'     => $offerCode,
                'payment_type'   => $paymentType,
                'status'         => 'pending',
                'payment_status' => 'pending',
                'special_requests' => $request->special_requests,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $booking;
    }

    /**
     * Are valid Razorpay credentials configured?
     * Returns false when keys are missing or still placeholder values,
     * which activates DEMO MODE so the booking flow can be tested.
     */
    public static function razorpayConfigured(): bool
    {
        $key    = env('RAZORPAY_KEY_ID');
        $secret = env('RAZORPAY_KEY_SECRET');
        if (empty($key) || empty($secret)) return false;
        // Reject the placeholder values shipped in .env.example
        if (str_contains($key, 'xxxx') || str_contains($secret, 'xxxx')) return false;
        return str_starts_with($key, 'rzp_');
    }

    /**
     * Create Razorpay order for advance payment.
     * Falls back to DEMO MODE when no valid keys are present.
     */
    public function createRazorpayOrder(Booking $booking): array
    {
        $amount = (int) round($booking->advance_amount * 100);

        // ── DEMO MODE: no real keys → simulate an order ──────────────
        if (!self::razorpayConfigured()) {
            $demoOrderId = 'order_demo_' . strtoupper(\Illuminate\Support\Str::random(14));
            Payment::create([
                'booking_id'       => $booking->id,
                'amount'           => $booking->advance_amount,
                'type'             => $booking->payment_type === 'full' ? 'full' : 'advance',
                'gateway'          => 'demo',
                'gateway_order_id' => $demoOrderId,
                'status'           => 'created',
            ]);
            return [
                'demo'        => true,
                'order_id'    => $demoOrderId,
                'key_id'      => null,
                'amount'      => $amount,
                'booking_ref' => $booking->booking_ref,
                'guest_name'  => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'guest_phone' => $booking->guest_phone,
            ];
        }

        // ── LIVE MODE: real Razorpay order ───────────────────────────
        $api   = new RazorpayApi(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        $order = $api->order->create([
            'receipt'  => $booking->booking_ref,
            'amount'   => $amount,
            'currency' => 'INR',
            'notes'    => [
                'booking_ref' => $booking->booking_ref,
                'guest_name'  => $booking->guest_name,
                'hotel'       => $booking->hotel->name ?? '',
            ],
        ]);

        Payment::create([
            'booking_id'       => $booking->id,
            'amount'           => $booking->advance_amount,
            'type'             => $booking->payment_type === 'full' ? 'full' : 'advance',
            'gateway'          => 'razorpay',
            'gateway_order_id' => $order['id'],
            'status'           => 'created',
        ]);

        return [
            'demo'        => false,
            'order_id'    => $order['id'],
            'key_id'      => env('RAZORPAY_KEY_ID'),
            'amount'      => $amount,
            'currency'    => 'INR',
            'booking_ref' => $booking->booking_ref,
            'guest_name'  => $booking->guest_name,
            'guest_email' => $booking->guest_email,
            'guest_phone' => $booking->guest_phone,
        ];
    }

    /**
     * DEMO MODE: confirm a simulated payment without a gateway.
     */
    public function confirmDemoPayment(Booking $booking): Booking
    {
        Payment::where('booking_id', $booking->id)
            ->where('gateway', 'demo')
            ->update([
                'gateway_payment_id' => 'pay_demo_' . strtoupper(\Illuminate\Support\Str::random(14)),
                'status'             => 'paid',
            ]);

        $paymentStatus = $booking->payment_type === 'full' ? 'fully_paid' : 'advance_paid';
        $booking->update(['payment_status' => $paymentStatus, 'status' => 'pending']);

        $this->notifications->bookingCreated($booking->fresh(['hotel', 'room', 'customer']));
        return $booking->fresh();
    }

    /**
     * Verify Razorpay payment and confirm booking
     */
    public function verifyAndConfirm(array $data): Booking
    {
        $booking = Booking::findOrFail($data['booking_id']);

        // Verify signature
        $sig = hash_hmac(
            'sha256',
            $data['razorpay_order_id'].'|'.$data['razorpay_payment_id'],
            env('RAZORPAY_KEY_SECRET')
        );

        if ($sig !== $data['razorpay_signature']) {
            throw new \Exception('Payment signature verification failed');
        }

        // Update payment record
        Payment::where('gateway_order_id', $data['razorpay_order_id'])->update([
            'gateway_payment_id' => $data['razorpay_payment_id'],
            'gateway_signature'  => $data['razorpay_signature'],
            'status'             => 'paid',
        ]);

        // Update booking — goes to pending (waiting hotel acceptance)
        $paymentStatus = $booking->payment_type === 'full' ? 'fully_paid' : 'advance_paid';
        $booking->update([
            'payment_status' => $paymentStatus,
            'status'         => 'pending', // hotel must still accept
        ]);

        // Fire notifications
        $this->notifications->bookingCreated($booking->fresh(['hotel', 'room', 'customer']));

        return $booking->fresh();
    }

    /**
     * Hotel accepts booking
     */
    public function acceptBooking(Booking $booking, ?string $hotelNotes = null): Booking
    {
        $booking->update([
            'status'            => 'confirmed',
            'hotel_accepted_at' => now(),
            'hotel_notes'       => $hotelNotes,
        ]);

        $this->notifications->bookingAccepted($booking->fresh(['hotel', 'room']));

        return $booking->fresh();
    }

    /**
     * Hotel rejects booking
     */
    public function rejectBooking(Booking $booking, string $reason): Booking
    {
        $booking->update([
            'status'             => 'rejected',
            'hotel_rejected_at'  => now(),
            'rejection_reason'   => $reason,
        ]);

        // Initiate refund if payment was made
        if ($booking->payment_status !== 'pending') {
            $this->initiateRefund($booking);
        }

        $this->notifications->bookingRejected($booking->fresh(['hotel']));

        return $booking->fresh();
    }

    /**
     * Mark as checked in
     */
    public function checkIn(Booking $booking): Booking
    {
        $booking->update(['status' => 'checked_in']);
        return $booking->fresh();
    }

    /**
     * Mark as completed
     */
    public function complete(Booking $booking): Booking
    {
        $booking->update([
            'status'         => 'completed',
            'payment_status' => 'fully_paid',
        ]);
        return $booking->fresh();
    }

    /**
     * Initiate refund (real Razorpay, or simulated in demo mode)
     */
    private function initiateRefund(Booking $booking): void
    {
        try {
            $payment = $booking->payments()->where('status', 'paid')->first();
            if (!$payment?->gateway_payment_id) return;

            // DEMO MODE — just mark refunded, no gateway call
            if ($payment->gateway === 'demo' || !self::razorpayConfigured()) {
                $payment->update(['status' => 'refunded']);
                return;
            }

            $api = new RazorpayApi(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
            $api->payment->fetch($payment->gateway_payment_id)->refund([
                'amount' => (int) round($payment->amount * 100),
            ]);

            $payment->update(['status' => 'refunded']);
        } catch (\Exception $e) {
            \Log::error("Refund failed for booking {$booking->booking_ref}: " . $e->getMessage());
        }
    }
}
