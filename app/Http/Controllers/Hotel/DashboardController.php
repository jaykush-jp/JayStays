<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Hotel};

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $hotelIds = Hotel::where('user_id',$user->id)->pluck('id');
        $stats    = [
            'pending'       => Booking::whereIn('hotel_id',$hotelIds)->where('status','pending')->count(),
            'confirmed'     => Booking::whereIn('hotel_id',$hotelIds)->where('status','confirmed')->count(),
            'today'         => Booking::whereIn('hotel_id',$hotelIds)->whereDate('checkin_at',today())->whereIn('status',['confirmed','accepted'])->count(),
            'revenue'       => Booking::whereIn('hotel_id',$hotelIds)->where('status','completed')->sum('room_rate'),
            'month_revenue' => Booking::whereIn('hotel_id',$hotelIds)->where('status','completed')->whereMonth('created_at',now()->month)->sum('room_rate'),
        ];
        $pendingBookings = Booking::whereIn('hotel_id',$hotelIds)->where('status','pending')->with(['hotel','room'])->orderBy('created_at')->limit(10)->get();
        $todayBookings   = Booking::whereIn('hotel_id',$hotelIds)->whereDate('checkin_at',today())->whereIn('status',['confirmed','accepted','checked_in'])->with(['hotel','room'])->get();
        $recentBookings  = Booking::whereIn('hotel_id',$hotelIds)->orderByDesc('created_at')->with(['hotel','room'])->limit(10)->get();
        $myHotels        = Hotel::where('user_id',$user->id)->withCount(['rooms','bookings'])->get();
        return view('hotel.dashboard', compact('stats','pendingBookings','todayBookings','recentBookings','myHotels','user'));
    }
}
