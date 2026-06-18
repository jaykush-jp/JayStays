<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Offer;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function index()
    {
        $seo = (new SeoService())
            ->title('Book Hourly & Overnight Hotel Rooms in India')
            ->description("India's best hourly hotel booking. Book rooms by the hour or overnight in Delhi, Noida, Mumbai & 50+ cities. Pay advance online, settle at hotel.")
            ->keywords(['hourly hotel booking India','book hotel by hour','short stay hotel','MyRoom'])
            ->schemaWebsite()->schemaOrganization()
            ->schemaFaq([
                ['q'=>'How does MyRoom work?','a'=>'Search → Book → Pay advance online → Hotel confirms → Walk in with Booking ID → Pay balance at hotel.'],
                ['q'=>'Do I need an account?','a'=>'No. Guests can book without registration using just name and phone.'],
                ['q'=>'What is the advance payment?','a'=>'The advance is ~10% of the room rate. It secures your room. Pay the remaining balance at the hotel.'],
            ]);
        $featured = Hotel::active()->ordered()->where('is_featured', true)->with(['rooms','reviews','offers'=>fn($q)=>$q->active()])->limit(8)->get();
        if ($featured->isEmpty()) {
            $featured = Hotel::active()->ordered()->with(['rooms','reviews','offers'=>fn($q)=>$q->active()])->limit(8)->get();
        }
        $cities = Hotel::active()->selectRaw('city, count(*) as count, avg(avg_rating) as rating')->groupBy('city')->orderByDesc('count')->limit(12)->get();
        $offers = Offer::active()->limit(4)->get();
        $stats  = [
            'hotels'   => Hotel::active()->count() ?: 500,
            'cities'   => Hotel::active()->distinct('city')->count('city') ?: 50,
            'bookings' => \App\Models\Booking::where('status','confirmed')->count() ?: 50000,
        ];
        return view('public.home', compact('seo','featured','cities','offers','stats'));
    }
}
