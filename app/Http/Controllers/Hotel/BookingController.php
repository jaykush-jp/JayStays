<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Hotel};
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}
    private function myHotelIds(): array { return Hotel::where('user_id',auth()->id())->pluck('id')->toArray(); }
    public function index(Request $req)
    {
        $q = Booking::whereIn('hotel_id',$this->myHotelIds())->with(['hotel','room'])->orderByRaw("FIELD(status,'pending','accepted','confirmed','checked_in','completed','rejected','cancelled','no_show')")->orderByDesc('created_at');
        if ($req->status) $q->where('status',$req->status);
        if ($req->date)   $q->whereDate('checkin_at',$req->date);
        $bookings = $q->paginate(20)->withQueryString();
        return view('hotel.bookings.index', compact('bookings'));
    }
    public function show(int $id)
    {
        $booking = Booking::whereIn('hotel_id',$this->myHotelIds())->with(['hotel','room','payments'])->findOrFail($id);
        return view('hotel.bookings.show', compact('booking'));
    }
    public function accept(Request $req, int $id)
    {
        $booking = Booking::whereIn('hotel_id',$this->myHotelIds())->findOrFail($id);
        if (!$booking->isPending()) return back()->with('error','Only pending bookings can be accepted.');
        $this->bookingService->acceptBooking($booking, $req->hotel_notes);
        return back()->with('success','Booking accepted! Customer has been notified via SMS.');
    }
    public function reject(Request $req, int $id)
    {
        $booking = Booking::whereIn('hotel_id',$this->myHotelIds())->findOrFail($id);
        if (!$booking->isPending()) return back()->with('error','Only pending bookings can be rejected.');
        $req->validate(['reason'=>'required|string|max:500']);
        $this->bookingService->rejectBooking($booking,$req->reason);
        return back()->with('success','Booking rejected. Customer notified and refund initiated.');
    }
    public function checkIn(int $id)  { $this->bookingService->checkIn(Booking::whereIn('hotel_id',$this->myHotelIds())->findOrFail($id)); return back()->with('success','Guest checked in!'); }
    public function complete(int $id) { $this->bookingService->complete(Booking::whereIn('hotel_id',$this->myHotelIds())->findOrFail($id)); return back()->with('success','Booking completed!'); }
    public function noShow(int $id)   { Booking::whereIn('hotel_id',$this->myHotelIds())->findOrFail($id)->update(['status'=>'no_show']); return back()->with('success','Marked as no-show.'); }
}
