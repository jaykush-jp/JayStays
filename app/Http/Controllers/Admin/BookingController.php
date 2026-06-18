<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $req)
    {
        $q = Booking::with(['hotel','room'])->orderByDesc('created_at');
        if ($req->status) $q->where('status',$req->status);
        if ($req->city)   $q->whereHas('hotel',fn($h)=>$h->where('city',$req->city));
        if ($req->search) $q->where(fn($x)=>$x->where('booking_ref','like',"%{$req->search}%")->orWhere('guest_name','like',"%{$req->search}%")->orWhere('guest_phone','like',"%{$req->search}%"));
        $bookings = $q->paginate(20)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }
    public function updateStatus(Request $req, int $id)
    {
        $req->validate(['status'=>'required|in:pending,confirmed,completed,cancelled']);
        Booking::findOrFail($id)->update(['status'=>$req->status]);
        return back()->with('success','Status updated!');
    }
}
