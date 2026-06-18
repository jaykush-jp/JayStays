<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $req)
    {
        $from = $req->from ?: now()->startOfMonth()->format('Y-m-d');
        $to   = $req->to   ?: now()->format('Y-m-d');
        $data = [
            'total_revenue'    => Booking::where('status','completed')->whereBetween('created_at',[$from,$to])->sum('room_rate'),
            'total_commission' => Booking::whereBetween('created_at',[$from,$to])->whereIn('payment_status',['advance_paid','fully_paid'])->sum('advance_amount'),
            'total_bookings'   => Booking::whereBetween('created_at',[$from,$to])->count(),
            'completed'        => Booking::where('status','completed')->whereBetween('created_at',[$from,$to])->count(),
            'cancelled'        => Booking::where('status','cancelled')->whereBetween('created_at',[$from,$to])->count(),
            'city_breakdown'   => DB::table('bookings')->join('hotels','bookings.hotel_id','=','hotels.id')->selectRaw('hotels.city,count(*) as cnt,sum(room_rate) as rev')->whereBetween('bookings.created_at',[$from,$to])->groupBy('hotels.city')->orderByDesc('rev')->get(),
        ];
        return view('admin.reports', compact('data','from','to'));
    }
}
