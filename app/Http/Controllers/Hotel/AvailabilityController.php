<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\{Hotel, Room, RoomAvailability};
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(int $hotelId, int $roomId)
    {
        Hotel::where('id',$hotelId)->where('user_id',auth()->id())->firstOrFail();
        $room  = Room::where('id',$roomId)->where('hotel_id',$hotelId)->firstOrFail();
        $dates = RoomAvailability::where('room_id',$roomId)->where('date','>=',now()->format('Y-m-d'))->get()->keyBy(fn($a)=>$a->date->format('Y-m-d'));
        return view('hotel.availability', compact('room','dates','hotelId'));
    }
    public function toggle(Request $req, int $hotelId, int $roomId)
    {
        Hotel::where('id',$hotelId)->where('user_id',auth()->id())->firstOrFail();
        Room::where('id',$roomId)->where('hotel_id',$hotelId)->firstOrFail();
        $req->validate(['date'=>'required|date|after_or_equal:today']);
        $existing = RoomAvailability::where('room_id',$roomId)->where('date',$req->date)->first();
        if ($existing) { $existing->update(['is_blocked'=>!$existing->is_blocked,'block_reason'=>$req->reason]); }
        else { RoomAvailability::create(['room_id'=>$roomId,'date'=>$req->date,'is_blocked'=>true,'block_reason'=>$req->reason]); }
        return response()->json(['success'=>true]);
    }
}
