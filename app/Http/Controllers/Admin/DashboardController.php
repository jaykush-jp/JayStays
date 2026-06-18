<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Hotel, User};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_hotels'   => Hotel::where('status','active')->count(),
            'pending_hotels'  => Hotel::where('status','pending')->count(),
            'total_bookings'  => Booking::count(),
            'confirmed'       => Booking::where('status','confirmed')->count(),
            'commission'      => Booking::whereIn('payment_status',['advance_paid','fully_paid'])->sum('advance_amount'),
            'total_customers' => User::where('role','customer')->count(),
            'total_owners'    => User::where('role','hotel_owner')->count(),
        ];
        $recentBookings = Booking::with(['hotel','room'])->orderByDesc('created_at')->limit(10)->get();
        $pendingHotels  = Hotel::where('status','pending')->with('owner')->latest()->limit(5)->get();
        $revenueChart   = Booking::where('status','completed')->selectRaw('DATE(created_at) as date,SUM(room_rate) as revenue,COUNT(*) as cnt')->where('created_at','>=',now()->subDays(7))->groupBy('date')->orderBy('date')->get();
        return view('admin.dashboard', compact('stats','recentBookings','pendingHotels','revenueChart'));
    }
}
