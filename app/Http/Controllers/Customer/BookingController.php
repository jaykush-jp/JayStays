<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $req)
    {
        $q = Booking::where('customer_id',auth()->id())->with(['hotel','room'])->orderByDesc('created_at');
        if ($req->status) $q->where('status',$req->status);
        $bookings = $q->paginate(10)->withQueryString();
        return view('customer.bookings', compact('bookings'));
    }

    public function show(string $ref)
    {
        $booking = Booking::where('booking_ref',strtoupper($ref))->where('customer_id',auth()->id())->with(['hotel','room','payments','review'])->firstOrFail();
        return view('customer.booking-detail', compact('booking'));
    }

    public function cancel(Request $req, string $ref)
    {
        $booking = Booking::where('booking_ref',strtoupper($ref))->where('customer_id',auth()->id())->firstOrFail();
        if (!in_array($booking->status,['pending','confirmed'])) return back()->with('error','This booking cannot be cancelled.');
        $booking->update(['status'=>'cancelled']);
        (new NotificationService())->bookingCancelled($booking->load('hotel'));
        return back()->with('success','Booking cancelled.');
    }

    public function downloadPdf(string $ref)
    {
        $booking = Booking::where('booking_ref',strtoupper($ref))->where('customer_id',auth()->id())->with(['hotel','room'])->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.booking-receipt', compact('booking'));
        return $pdf->download("MyRoom-{$booking->booking_ref}.pdf");
    }
}
