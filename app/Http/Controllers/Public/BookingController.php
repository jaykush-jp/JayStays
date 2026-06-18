<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Hotel, Offer, Room};
use App\Services\{BookingService, SeoService};
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function create(Hotel $hotel, Room $room)
    {
        abort_unless($hotel->status==='active',404);
        abort_unless($room->hotel_id===$hotel->id && $room->is_available,404);
        $offers     = Offer::active()->where(fn($q)=>$q->whereNull('hotel_id')->orWhere('hotel_id',$hotel->id))->get();
        $commission = $hotel->effective_commission;
        $stayType   = request('type','hourly');
        $seo        = (new SeoService())->title("Book {$room->name} at {$hotel->name}")->noIndex();
        return view('public.booking-create', compact('seo','hotel','room','offers','commission','stayType'));
    }

    public function store(Request $req, Hotel $hotel, Room $room)
    {
        $req->validate(['guest_name'=>'required|string|max:100','guest_phone'=>'required|digits:10','guest_email'=>'nullable|email','stay_type'=>'required|in:hourly,overnight','checkin_at'=>'required|date|after:now','hours'=>'nullable|integer|min:1|max:12','special_requests'=>'nullable|string|max:500']);
        try {
            $booking   = $this->bookingService->createBooking($req,$hotel,$room);
            $orderData = $this->bookingService->createRazorpayOrder($booking);
            session(['last_booking_phone'=>$req->guest_phone]);
            return view('public.booking-payment', compact('booking','orderData'));
        } catch (\Exception $e) {
            \Log::error('Booking store error: '.$e->getMessage());
            return back()->withInput()->with('error','Booking failed: '.$e->getMessage());
        }
    }

    public function confirmation(string $ref)
    {
        $phone   = session('last_booking_phone');
        $query   = Booking::where('booking_ref',strtoupper($ref))->with(['hotel','room']);
        $booking = $phone ? $query->where('guest_phone',$phone)->first() : null;
        if (!$booking) $booking = Booking::where('booking_ref',strtoupper($ref))->with(['hotel','room'])->firstOrFail();
        $seo = (new SeoService())->title("Booking #{$booking->booking_ref}")->noIndex();
        return view('public.booking-confirmation', compact('seo','booking'));
    }

    public function trackForm()
    {
        $seo = (new SeoService())->title('Track Your Booking');
        return view('public.booking-track', compact('seo'));
    }

    public function track(Request $req)
    {
        $req->validate(['booking_ref'=>'required','phone'=>'required|digits:10']);
        $booking = Booking::where('booking_ref',strtoupper($req->booking_ref))->where('guest_phone',$req->phone)->with(['hotel','room'])->first();
        $seo     = (new SeoService())->title('Booking Status')->noIndex();
        if (!$booking) return view('public.booking-track', compact('seo'))->with('error','Booking not found. Check your Booking ID and mobile number.');
        return view('public.booking-track', compact('seo','booking'));
    }
}
