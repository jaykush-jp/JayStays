<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $req)
    {
        $q = Hotel::with('owner')->orderByDesc('created_at');
        if ($req->status) $q->where('status',$req->status);
        if ($req->city)   $q->where('city',$req->city);
        if ($req->search) $q->where(fn($x)=>$x->where('name','like',"%{$req->search}%")->orWhere('city','like',"%{$req->search}%"));
        $hotels = $q->withCount('rooms')->paginate(20)->withQueryString();
        return view('admin.hotels.index', compact('hotels'));
    }
    public function approve(int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->update(['status'=>'active','rejection_reason'=>null]);
        (new NotificationService())->notifyUser($hotel->user_id,'hotel_approved','Your hotel is now live!','Your hotel has been approved and is visible to customers.',['hotel_id'=>$id]);
        return back()->with('success','Hotel approved and live!');
    }
    public function reject(Request $req, int $id)
    {
        $req->validate(['reason'=>'required|string|max:500']);
        $hotel = Hotel::findOrFail($id);
        $hotel->update(['status'=>'rejected','rejection_reason'=>$req->reason]);
        (new NotificationService())->notifyUser($hotel->user_id,'hotel_rejected','Hotel approval rejected','Reason: '.$req->reason,['hotel_id'=>$id]);
        return back()->with('success','Hotel rejected.');
    }
    public function update(Request $req, int $id)
    {
        Hotel::findOrFail($id)->update($req->only(['status','listing_priority','listing_order','commission_percent','is_featured','couple_friendly']));
        return back()->with('success','Updated!');
    }
}
