<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Wishlist};

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $upcoming   = Booking::where('customer_id',$user->id)->whereIn('status',['pending','accepted','confirmed','checked_in'])->with(['hotel','room'])->orderBy('checkin_at')->limit(5)->get();
        $recent     = Booking::where('customer_id',$user->id)->whereIn('status',['completed','cancelled','rejected','no_show'])->with(['hotel','room'])->orderByDesc('created_at')->limit(5)->get();
        $totalSpent = Booking::where('customer_id',$user->id)->where('status','completed')->sum('room_rate');
        $stats = [
            'total_bookings'  => Booking::where('customer_id',$user->id)->count(),
            'upcoming_count'  => $upcoming->count(),
            'completed_count' => Booking::where('customer_id',$user->id)->where('status','completed')->count(),
            'total_spent'     => $totalSpent,
            'wishlist_count'  => Wishlist::where('customer_id',$user->id)->count(),
        ];
        return view('customer.dashboard', compact('upcoming','recent','stats','user'));
    }
}
