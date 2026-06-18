<?php
namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    private function myHotel(int $id): Hotel { return Hotel::where('id',$id)->where('user_id',auth()->id())->firstOrFail(); }
    public function index()     { $hotels = Hotel::where('user_id',auth()->id())->withCount(['rooms','bookings'])->get(); return view('hotel.properties.index', compact('hotels')); }
    public function create()    { return view('hotel.properties.form', ['hotel'=>null]); }
    public function store(Request $req)
    {
        $req->validate(['name'=>'required|max:150','city'=>'required','address'=>'required']);
        Hotel::create(array_merge($req->only(['name','city','area','address','description','star_rating','couple_friendly','accepts_local_id','cover_image']),['user_id'=>auth()->id(),'amenities'=>$req->amenities??[],'status'=>'pending']));
        return redirect()->route('hotel.properties')->with('success','Property submitted for admin approval!');
    }
    public function edit(int $id)   { $hotel = $this->myHotel($id); return view('hotel.properties.form', compact('hotel')); }
    public function update(Request $req, int $id)
    {
        $this->myHotel($id)->update($req->only(['name','city','area','address','description','star_rating','couple_friendly','accepts_local_id','cover_image','amenities']));
        return back()->with('success','Property updated!');
    }
}
