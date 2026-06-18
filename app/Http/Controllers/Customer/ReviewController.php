<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Review};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $req, string $ref)
    {
        $booking = Booking::where('booking_ref',strtoupper($ref))->where('customer_id',auth()->id())->where('status','completed')->firstOrFail();
        if ($booking->review) return back()->with('error','You already reviewed this booking.');
        $req->validate(['rating'=>'required|integer|between:1,5','comment'=>'nullable|string|max:1000']);
        Review::create(['booking_id'=>$booking->id,'customer_id'=>auth()->id(),'hotel_id'=>$booking->hotel_id,'rating'=>$req->rating,'comment'=>$req->comment,'status'=>'approved']);
        $avg   = Review::where('hotel_id',$booking->hotel_id)->where('status','approved')->avg('rating');
        $total = Review::where('hotel_id',$booking->hotel_id)->where('status','approved')->count();
        $booking->hotel()->update(['avg_rating'=>round($avg,2),'total_reviews'=>$total]);
        return back()->with('success','Review submitted! Thank you.');
    }
}
