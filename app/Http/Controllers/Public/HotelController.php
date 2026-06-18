<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Wishlist;
use App\Services\SeoService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function show(string $slug)
    {
        $hotel = Hotel::active()->where('slug',$slug)->with([
            'rooms'   => fn($q)=>$q->where('is_available',true)->orderBy('hourly_price'),
            'offers'  => fn($q)=>$q->active(),
            'reviews' => fn($q)=>$q->where('status','approved')->with('customer')->latest()->limit(10),
        ])->firstOrFail();
        $related = Hotel::active()->ordered()->where('city',$hotel->city)->where('id','!=',$hotel->id)->limit(4)->get();
        $minHourly    = $hotel->rooms->whereNotNull('hourly_price')->min('hourly_price');
        $minOvernight = $hotel->rooms->whereNotNull('overnight_price')->min('overnight_price');
        $minPrice     = collect([$minHourly,$minOvernight])->filter()->min() ?? 0;
        $isWishlisted = auth()->check() ? Wishlist::where('customer_id',auth()->id())->where('hotel_id',$hotel->id)->exists() : false;
        $seo = (new SeoService())
            ->title("{$hotel->name} — Hourly Hotel in {$hotel->city}")
            ->description("Book {$hotel->name} in {$hotel->city}. Hourly & overnight stays from ₹{$minPrice}. ★{$hotel->avg_rating}. Pay advance online.")
            ->canonical(route('hotel.show',$slug))->ogType('place');
        if ($hotel->cover_image) $seo->ogImage($hotel->cover_image);
        return view('public.hotel', compact('seo','hotel','related','minPrice','isWishlisted'));
    }

    public function toggleWishlist(Request $req, Hotel $hotel)
    {
        if (!auth()->check()) return response()->json(['success'=>false,'message'=>'Login to save hotels'],401);
        $exists = Wishlist::where('customer_id',auth()->id())->where('hotel_id',$hotel->id)->exists();
        if ($exists) Wishlist::where('customer_id',auth()->id())->where('hotel_id',$hotel->id)->delete();
        else Wishlist::create(['customer_id'=>auth()->id(),'hotel_id'=>$hotel->id]);
        return response()->json(['success'=>true,'wishlisted'=>!$exists]);
    }
}
