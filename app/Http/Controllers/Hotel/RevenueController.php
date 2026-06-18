<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Hotel};

class RevenueController extends Controller
{
    public function index()
    {
        $hotelIds = Hotel::where('user_id',auth()->id())->pluck('id');
        $revenue  = [
            'total' => Booking::whereIn('hotel_id',$hotelIds)->where('status','completed')->sum('room_rate'),
            'month' => Booking::whereIn('hotel_id',$hotelIds)->where('status','completed')->whereMonth('created_at',now()->month)->sum('room_rate'),
            'week'  => Booking::whereIn('hotel_id',$hotelIds)->where('status','completed')->whereBetween('created_at',[now()->startOfWeek(),now()->endOfWeek()])->sum('room_rate'),
        ];
        $chart = Booking::whereIn('hotel_id',$hotelIds)->where('status','completed')->selectRaw('DATE(created_at) as date,SUM(room_rate) as revenue,COUNT(*) as cnt')->where('created_at','>=',now()->subDays(30))->groupBy('date')->orderBy('date')->get();
        return view('hotel.revenue', compact('revenue','chart'));
    }
}
