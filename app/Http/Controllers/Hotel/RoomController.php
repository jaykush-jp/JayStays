<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\{Hotel, Room};
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(int $hotelId)
    {
        Hotel::where('id',$hotelId)->where('user_id',auth()->id())->firstOrFail();
        $hotel = Hotel::findOrFail($hotelId);
        $rooms = Room::where('hotel_id',$hotelId)->get();
        return view('hotel.rooms.index', compact('hotel','rooms'));
    }
    public function store(Request $req, int $hotelId)
    {
        Hotel::where('id',$hotelId)->where('user_id',auth()->id())->firstOrFail();
        $req->validate(['name'=>'required','stay_type'=>'required|in:hourly,overnight,both','capacity'=>'nullable|integer|min:1']);
        Room::create(array_merge($req->only(['name','description','stay_type','hourly_price','min_hours','overnight_price','price_3hr','price_6hr','price_12hr','capacity']),['hotel_id'=>$hotelId,'is_available'=>true,'amenities'=>$req->amenities??[]]));
        return back()->with('success','Room added!');
    }
    public function update(Request $req, int $hotelId, int $id)
    {
        Hotel::where('id',$hotelId)->where('user_id',auth()->id())->firstOrFail();
        Room::where('id',$id)->where('hotel_id',$hotelId)->firstOrFail()->update($req->except(['_token','_method']));
        return back()->with('success','Room updated!');
    }
    public function destroy(int $hotelId, int $id)
    {
        Hotel::where('id',$hotelId)->where('user_id',auth()->id())->firstOrFail();
        Room::where('id',$id)->where('hotel_id',$hotelId)->firstOrFail()->delete();
        return back()->with('success','Room deleted!');
    }
}
