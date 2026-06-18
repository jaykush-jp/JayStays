<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\{BookingService, NotificationService};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function verifyRazorpay(Request $req)
    {
        $req->validate(['razorpay_order_id'=>'required','razorpay_payment_id'=>'required','razorpay_signature'=>'required','booking_id'=>'required']);
        try {
            $booking = $this->bookingService->verifyAndConfirm($req->all());
            return response()->json(['success'=>true,'redirect'=>route('booking.confirmation',$booking->booking_ref)]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>'Payment verification failed: '.$e->getMessage()],422);
        }
    }

    public function phonePeCallback(Request $req, Booking $booking)
    {
        $booking->update(['payment_status'=>'advance_paid','status'=>'pending']);
        (new NotificationService())->bookingCreated($booking->load(['hotel','room','customer']));
        return redirect()->route('booking.confirmation',$booking->booking_ref);
    }

    /**
     * DEMO MODE — confirm simulated payment (no real gateway keys configured).
     */
    public function confirmDemo(Request $req)
    {
        $req->validate(['booking_id'=>'required']);
        try {
            $booking = Booking::findOrFail($req->booking_id);
            $this->bookingService->confirmDemoPayment($booking);
            return response()->json(['success'=>true,'redirect'=>route('booking.confirmation',$booking->booking_ref)]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message'=>$e->getMessage()],422);
        }
    }
}
