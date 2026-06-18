<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Offer;
use App\Services\SeoService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $req)
    {
        $city     = trim($req->city ?? '');
        $stayType = $req->type ?? '';
        $date     = $req->date ?: now()->format('Y-m-d');
        $time     = $req->time ?: '17:00';
        $maxPrice = $req->max_price ?: 5000;
        $minRating= $req->min_rating ?: 0;
        $sort     = $req->sort ?? '';
        $amenity  = $req->amenity ?? '';
        $tags     = $req->tags ?? '';

        $q = Hotel::active()->with(['rooms'=>fn($q)=>$q->where('is_available',true),'offers'=>fn($q)=>$q->active()]);

        if ($city)      $q->where(fn($q)=>$q->where('city','like',"%{$city}%")->orWhere('area','like',"%{$city}%")->orWhere('name','like',"%{$city}%"));
        if ($stayType)  $q->whereHas('rooms',fn($q)=>$q->where('is_available',true)->where(fn($q2)=>$q2->where('stay_type',$stayType)->orWhere('stay_type','both')));
        if ($maxPrice)  $q->whereHas('rooms',fn($q)=>$q->where('is_available',true)->where(fn($x)=>$x->where('hourly_price','<=',$maxPrice)->orWhere('overnight_price','<=',$maxPrice)));
        if ($minRating) $q->where('avg_rating','>=',$minRating);
        if ($req->area) $q->where('area','like',"%{$req->area}%");

        // Amenity filter (matches JSON amenities column)
        if ($amenity) {
            $amenityMap = ['wifi'=>'WiFi','parking'=>'Parking','ac'=>'AC','restaurant'=>'Restaurant','pool'=>'Pool'];
            if (isset($amenityMap[$amenity])) $q->whereJsonContains('amenities', $amenityMap[$amenity]);
        }

        // Tag filter
        if ($tags === 'couple_friendly') $q->where('couple_friendly', true);
        if ($tags === 'local_id')        $q->where('accepts_local_id', true);

        // Sorting
        match($sort) {
            'price_low'  => $q->withMin('rooms','hourly_price')->orderBy('rooms_min_hourly_price'),
            'price_high' => $q->withMin('rooms','hourly_price')->orderByDesc('rooms_min_hourly_price'),
            'rating'     => $q->orderByDesc('avg_rating'),
            default      => $q->ordered(),
        };

        $hotels    = $q->paginate(12)->withQueryString();
        $allCities = Hotel::active()->selectRaw('city,count(*) as count')->groupBy('city')->orderByDesc('count')->get();
        $seo       = (new SeoService())
            ->title(($city ? "Hotels in {$city}" : "Hourly Hotels")." — Book Short Stay")
            ->description("Find ".($city ? "hotels in {$city} " : "hourly hotels ")."on MyRoom. {$hotels->total()} verified properties. Pay advance online.")
            ->robots($hotels->total() > 0 ? 'index,follow' : 'noindex,follow');
        return view('public.search', compact('seo','hotels','city','stayType','date','time','allCities','maxPrice','minRating'));
    }

    public function city(string $city)
    {
        $cityName = ucwords(str_replace('-',' ',$city));
        $hotels   = Hotel::active()->ordered()->where('city','like',"%{$cityName}%")->with(['rooms'=>fn($q)=>$q->where('is_available',true),'offers'=>fn($q)=>$q->active()])->paginate(12);
        $seo      = (new SeoService())->title("Hourly Hotels in {$cityName} — Book Short Stay")->description("Book hourly hotel rooms in {$cityName}. {$hotels->total()} verified hotels. Pay advance online.")->canonical(route('search.city',$city));
        return view('public.search-city', compact('seo','hotels','cityName','city'));
    }

    public function cities()
    {
        $cities = Hotel::active()->selectRaw('city,count(*) as hotel_count,avg(avg_rating) as avg_rating')->groupBy('city')->orderByDesc('hotel_count')->get();
        $seo    = (new SeoService())->title('All Cities — Hourly Hotels Across India')->description('Hourly hotel bookings across '.count($cities).'+ cities in India.');
        return view('public.cities', compact('seo','cities'));
    }

    public function checkOffer(Request $req)
    {
        $req->validate(['code'=>'required','amount'=>'required|numeric','stay_type'=>'required']);
        $offer = Offer::where('code',strtoupper($req->code))->where('is_active',true)->first();
        if (!$offer || !$offer->isValid((float)$req->amount)) {
            return response()->json(['success'=>false,'message'=>'Invalid or expired offer code'],422);
        }
        return response()->json(['success'=>true,'discount'=>$offer->calculateDiscount((float)$req->amount),'offer'=>['id'=>$offer->id,'title'=>$offer->title,'code'=>$offer->code]]);
    }
}
