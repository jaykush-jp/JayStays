<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\{Hotel, Review};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $hotelIds = Hotel::where('user_id',auth()->id())->pluck('id');
        $reviews  = Review::whereIn('hotel_id',$hotelIds)->with(['customer','hotel'])->latest()->paginate(20);
        return view('hotel.reviews', compact('reviews'));
    }
    public function reply(Request $req, int $id)
    {
        $hotelIds = Hotel::where('user_id',auth()->id())->pluck('id');
        $review   = Review::whereIn('hotel_id',$hotelIds)->findOrFail($id);
        $req->validate(['reply'=>'required|string|max:1000']);
        $review->update(['hotel_reply'=>$req->reply,'hotel_replied_at'=>now()]);
        return back()->with('success','Reply posted!');
    }
}
