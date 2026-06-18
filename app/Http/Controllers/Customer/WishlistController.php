<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\{Hotel, Wishlist};

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('customer_id',auth()->id())->with(['hotel'=>fn($q)=>$q->with('rooms')])->latest()->paginate(12);
        return view('customer.wishlist', compact('wishlists'));
    }

    public function toggle(Hotel $hotel)
    {
        $w = Wishlist::where('customer_id',auth()->id())->where('hotel_id',$hotel->id)->first();
        if ($w) { $w->delete(); $msg='Removed from wishlist'; $added=false; }
        else    { Wishlist::create(['customer_id'=>auth()->id(),'hotel_id'=>$hotel->id]); $msg='Added to wishlist'; $added=true; }
        if (request()->expectsJson()) return response()->json(['success'=>true,'added'=>$added,'message'=>$msg]);
        return back()->with('success',$msg);
    }
}
